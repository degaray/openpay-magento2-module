<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 16/12/15
 * Time: 10:19 AM
 */

namespace Degaray\Openpay\Model\Method;

use Degaray\Openpay\Model\Customer\OpenpayCustomerRepositoryInterface;
use Degaray\Openpay\Model\Product\Type\OpenpayRecharge;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Api\Data\CartInterface;
use Openpay\Client\Adapter\OpenpayChargeAdapterInterface;
use Openpay\Client\Adapter\OpenpayFeeAdapterInterface;
use Openpay\Client\Exception\OpenpayException;

class OpenpayChargeCustomerCardMethod extends AbstractMethod
{
    const METHOD_CODE = 'openpay-charge-customer-card';

    const OPENPAY_PAYMENT_METHOD_CARD = 'card';

    protected $_code = self::METHOD_CODE;

    protected $_isGateway                   = true;
    protected $_canAuthorize                = true;
    protected $_canCapture                  = true;
    protected $_canRefund                   = true;

    /**
     * @var OpenpayChargeAdapterInterface
     */
    protected $chargeAdapter;

    /**
     * @var ScopeConfigInterface
     */
    protected $config;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var OpenpayCustomerRepositoryInterface
     */
    protected $openpayCustomerRepository;

    /**
     * @var OpenpayFeeAdapterInterface
     */
    protected $feeAdapter;

    /**
     * OpenpayChargeCustomerCardMethod constructor.
     * @param OpenpayChargeAdapterInterface $chargeAdapter
     * @param OpenpayFeeAdapterInterface $feeAdapter
     * @param ScopeConfigInterface $config
     * @param CustomerRepositoryInterface $customerRepository
     * @param OpenpayCustomerRepositoryInterface $openpayCustomerRepository
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        OpenpayChargeAdapterInterface $chargeAdapter,
        OpenpayFeeAdapterInterface $feeAdapter,
        ScopeConfigInterface $config,
        CustomerRepositoryInterface $customerRepository,
        OpenpayCustomerRepositoryInterface $openpayCustomerRepository,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->chargeAdapter = $chargeAdapter;
        $this->feeAdapter = $feeAdapter;
        $this->config = $config;
        $this->customerRepository = $customerRepository;
        $this->openpayCustomerRepository = $openpayCustomerRepository;

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @param InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws LocalizedException
     */
    public function capture(InfoInterface $payment, $amount)
    {
        parent::capture($payment, $amount);

        $customerId = $payment->getOrder()->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);

        $openpayCustomerId = $customer->getCustomAttribute('openpay_customer_id')->getValue();

        $cardId = $payment->getAdditionalInformation('customer_card_id');
        $deviceSessionId = $payment->getAdditionalInformation('device_session_id');

        $order = $payment->getOrder();
        $currency = $order->getOrderCurrencyCode();
        $useOrderId = $this->getConfigData('useOrderId');
        $paymentLeyend = $this->getConfigData('paymentLeyend');
        $orderId = $order->getIncrementId();
        $params = [
            'source_id' => $cardId,
            'method' => self::OPENPAY_PAYMENT_METHOD_CARD,
            'amount' => $amount,
            'currency' => $currency,
            'description' => __($paymentLeyend)->getText(),
            'order_id' => ($useOrderId) ? $orderId : null,
            'device_session_id' => $deviceSessionId
        ];

        try {
            $transaction = $this->chargeAdapter->chargeCustomerCard($openpayCustomerId, $params);

            $payment
                ->setTransactionId($transaction->getId())
                ->setIsTransactionClosed(0);
            $this->openpayCustomerRepository->clearCustomerCache($openpayCustomerId);
        } catch (OpenpayException $e) {
            $this->debugData(['request' => $params, 'exception' => $e->getMessage()]);
            $this->_logger->error(__('Payment capturing error.'));
            throw new LocalizedException(new Phrase('[' . $e->getErrorCode() . ']' . $e->getMessage()));
        }

        return $this;
    }

    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $paymentLeyend = __('Refund for: ') . __($this->getConfigData('paymentLeyend'))->getText();
        $order = $payment->getOrder();
        $customerId = $order->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);

        $customerOpenpayId = $customer->getCustomAttribute('openpay_customer_id')->getValue();
        $parentTransactionId = $payment->getParentTransactionId();

        $refundParams = [
            'amount' => $amount,
            'description' => $paymentLeyend,
        ];
        
        try {
            $refundTransaction = $this->chargeAdapter->refundCustomerCard(
                $customerOpenpayId,
                $parentTransactionId,
                $refundParams
            );

            $payment
                ->setTransactionId($refundTransaction->getId())
                ->setIsTransactionClosed(0);

            $this->openpayCustomerRepository->clearCustomerCache($customerOpenpayId);
        } catch (OpenpayException $e) {
            $this->debugData(['request' => $refundParams, 'exception' => $e->getMessage()]);
            $this->_logger->error(__('Payment capturing error.'));
            throw new LocalizedException(__('[' . $e->getErrorCode() . ']' . $e->getMessage()));
        }

        return parent::refund($payment, $amount);
    }

    /**
     * Assign corresponding data
     *
     * @param \Magento\Framework\DataObject|mixed $data
     * @return $this
     * @throws LocalizedException
     */
    public function assignData(\Magento\Framework\DataObject $data)
    {
        parent::assignData($data);

        $infoInstance = $this->getInfoInstance();

        $openpayCard = $data->getData()['additional_data']['extension_attributes']->getOpenpayCard();
        $infoInstance->setAdditionalInformation('customer_card_id', $openpayCard->getCardId());
        $infoInstance->setAdditionalInformation('device_session_id', $openpayCard->getDeviceSessionId());

        return $this;
    }

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isActive($storeId = null)
    {
        if (!$this->config->getValue('payment/openpay/chargeCustomerCardActive')) {
            return false;
        }

        return parent::isActive($storeId); // TODO: Change the autogenerated stub
    }

    /**
     * @param CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(CartInterface $quote = null)
    {
        if (!parent::isAvailable($quote)) {
            return false;
        }

        foreach ($quote->getAllItems() as $item) {
            if ($item->getProductType() !== OpenpayRecharge::TYPE_CODE) {
                return false;
            }
        }

        $customer = $quote->getCustomer();
        $openpayCards = $customer->getExtensionAttributes()->getOpenpayCard();

        if (count($openpayCards) === 0) {
            return false;
        }

        $openpayConfigValues = $this->config->getValue('payment/openpay');
        $chargeMinAmount = $openpayConfigValues['chargeMinAmount'];
        if ($chargeMinAmount > $quote->getGrandTotal()) {
            return false;
        }

        $currency = $quote->getCurrency()->getQuoteCurrencyCode();
        $acceptedCurrencies = $this->getCurrenciesAccepted();
        if (!in_array($currency, $acceptedCurrencies)) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getCurrenciesAccepted()
    {
        $currenciesAccepted = $this->getConfigData('currencies_accepted');
        $currenciesAcceptedArray = explode(',', $currenciesAccepted);
        return $currenciesAcceptedArray;
    }

    /**
     * Retrieve information from payment FEE configuration
     *
     * @param string $field
     * @param int|string|null|\Magento\Store\Model\Store $storeId
     *
     * @return mixed
     */
    public function getFeeConfigData($field, $storeId = null)
    {
        if ('order_place_redirect_url' === $field) {
            return $this->getOrderPlaceRedirectUrl();
        }
        if (null === $storeId) {
            $storeId = $this->getStore();
        }
        $path = 'payment/' . OpenpayChargeFeeMethod::METHOD_CODE . '/' . $field;
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
}
