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
use Openpay\Client\Adapter\OpenpayFeeAdapterInterface;
use Openpay\Client\Exception\OpenpayException;

class OpenpayChargeFeeMethod extends AbstractMethod
{
    const METHOD_CODE = 'openpay-charge-fee';

    const OPENPAY_PAYMENT_METHOD_FEE = 'fee';

    protected $_code = self::METHOD_CODE;

    protected $_isGateway                   = true;
    protected $_canAuthorize                = false;
    protected $_canCapture                  = true;
    protected $_canRefund                   = false;

    /**
     * @var OpenpayFeeAdapterInterface
     */
    protected $feeAdapter;
    protected $config;
    protected $customerRepository;
    protected $openpayCustomerRepository;

    /**
     * OpenpayChargeCustomerCardMethod constructor.
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

        $order = $payment->getOrder();
        $useOrderId = $this->getConfigData('useOrderId');
        $paymentLeyend = $this->getConfigData('paymentLeyend');
        $orderId = $order->getIncrementId();
        $params = [
            'customer_id' => $openpayCustomerId,
            'amount' => $amount,
            'description' => __($paymentLeyend)->getText(),
            'order_id' => ($useOrderId) ? $orderId : null,
        ];

        try {
            $transaction = $this->feeAdapter->chargeFee($params);

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

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isActive($storeId = null)
    {
        if (!$this->config->getValue('payment/openpay/chargeFeeActive')) {
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

    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {

        return parent::refund($payment, $amount);
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
