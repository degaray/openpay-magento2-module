<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 16/12/15
 * Time: 10:19 AM
 */

namespace Degaray\Openpay\Model\Method;

use Degaray\Openpay\Model\Customer\OpenpayCustomerRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Api\Data\CartInterface;
use Openpay\Client\Adapter\OpenpayChargeAdapterInterface;
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
     * OpenpayChargeCustomerCardMethod constructor.
     * @param OpenpayChargeAdapterInterface $chargeAdapter
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

    public function validate()
    {
        parent::validate();
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
            $this->openpayCustomerRepository->clearCustomerCache($customerId);
        } catch (OpenpayException $e) {
            $this->debugData(['request' => $params, 'exception' => $e->getMessage()]);
            $this->_logger->error(__('Payment capturing error.'));
            throw new LocalizedException(new Phrase('[' . $e->getErrorCode() . ']' . $e->getMessage()));
        }

        return $this;
    }

    /**
     * @param CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(CartInterface $quote = null)
    {
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
}