<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 6/29/16
 * Time: 1:07 PM
 */

namespace Degaray\Openpay\Model\Method;

use Degaray\Openpay\Model\Adapter\OpenpayTransferAdapter;
use Degaray\Openpay\Model\Customer\OpenpayCustomerRepositoryInterface;
use Degaray\Openpay\Model\Exception\OpenpayException;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Api\Data\CartInterface;

class OpenpayCustomerTransferMethod extends AbstractMethod
{
    const METHOD_CODE = 'openpay-customer-transfer';

    const OPENPAY_PAYMENT_METHOD_TRANSFER = 'transfer';

    protected $_code = self::METHOD_CODE;

    protected $_isGateway                   = true;
    protected $_canAuthorize                = false;
    protected $_canCapture                  = true;
    protected $_canRefund                   = true;

    protected $transferAdapter;
    protected $config;
    protected $customerRepository;
    protected $openpayCustomerRepository;

    /**
     * OpenpayChargeCustomerCardMethod constructor.
     * @param OpenpayTransferAdapter $transferAdapter
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
        OpenpayTransferAdapter $transferAdapter,
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
        $this->transferAdapter = $transferAdapter;
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

    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $customerId = $payment->getOrder()->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);

        $openpayCustomerId = $customer->getCustomAttribute('openpay_customer_id')->getValue();

        $destinationCustomer = $payment->getAdditionalInformation('destination_customer');

        $order = $payment->getOrder();
        $useOrderId = $this->getConfigData('useOrderId');
        $paymentLeyend = $this->getConfigData('paymentLeyend');
        $orderId = $order->getIncrementId();
        $params = [
            'customer_id' => $destinationCustomer,
            'amount' => $amount,
            'description' => __($paymentLeyend)->getText(),
            'order_id' => ($useOrderId) ? $orderId : null,
        ];

        try {
            $transaction = $this->transferAdapter->transfer($openpayCustomerId, $params);

            $payment
                ->setTransactionId($transaction->getId())
                ->setIsTransactionClosed(0);

            $this->openpayCustomerRepository->clearCustomerCache($openpayCustomerId);
            $this->openpayCustomerRepository->clearCustomerCache($destinationCustomer);
        } catch (OpenpayException $e) {
            $this->debugData(['request' => $params, 'exception' => $e->getMessage()]);
            $this->_logger->error(__('Payment capturing error.'));
            throw new LocalizedException(__('[' . $e->getErrorCode() . ']' . $e->getMessage()));
        }

        parent::capture($payment, $amount);

        return $this;
    }

    /**
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws LocalizedException
     */
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $senderCustomer = $payment->getAdditionalInformation('destination_customer');

        $customerId = $payment->getOrder()->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);

        $destinationCustomer = $customer->getCustomAttribute('openpay_customer_id')->getValue();

        $paymentLeyend = __('Refund for: ') . __($this->getConfigData('paymentLeyend'))->getText();

        $order = $payment->getOrder();
        $useOrderId = $this->getConfigData('useOrderId');
        $orderId = $order->getIncrementId();

        $params = [
            'customer_id' => $destinationCustomer,
            'amount' => $amount,
            'description' => $paymentLeyend,
            'order_id' => ($useOrderId) ? $orderId . '-refund' : null,
        ];

        try {
            $transaction = $this->transferAdapter->transfer($senderCustomer, $params);

            $payment
                ->setTransactionId($transaction->getId())
                ->setIsTransactionClosed(0);

            $this->openpayCustomerRepository->clearCustomerCache($senderCustomer);
            $this->openpayCustomerRepository->clearCustomerCache($destinationCustomer);
        } catch (OpenpayException $e) {
            $this->debugData(['request' => $params, 'exception' => $e->getMessage()]);
            $this->_logger->error(__('Payment capturing error.'));
            throw new LocalizedException(__('[' . $e->getErrorCode() . ']' . $e->getMessage()));
        }

        return parent::refund($payment, $amount); // TODO: Change the autogenerated stub
    }

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isActive($storeId = null)
    {
        if (!$this->config->getValue('payment/openpay/customerTransferActive')) {
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

        $customer = $quote->getCustomer();
        $openpayCustomer = $customer->getExtensionAttributes()->getOpenpayCustomer();

        if (($openpayCustomer->getBalance() < $quote->getData('grand_total')) ||
            ($quote->getData('grand_total') <= 0)
        ){
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

        $additionalData = $data->getData()['additional_data'];

        if (is_null($additionalData) || !array_key_exists('destination_customer', $additionalData)) {
            $additionalData['destination_customer'] = $this->config->getValue('payment/openpay/concentratorAccount');
        }

        $infoInstance->setAdditionalInformation('destination_customer', $additionalData['destination_customer']);

        return $this;
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
