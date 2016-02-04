<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 3/02/16
 * Time: 11:16 AM
 */

namespace Degaray\Openpay\Model\Plugin\Checkout;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Quote\Api\CartRepositoryInterface;
use Openpay\Client\Type\OpenpayCardType;

class HandleCard
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * HandleCard constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CartRepositoryInterface $cartRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param \Magento\Checkout\Api\PaymentInformationManagementInterface $subject
     * @param $cartId
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param \Magento\Quote\Api\Data\AddressInterface $billingAddress
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Api\PaymentInformationManagementInterface $subject,
        $cartId,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress
    ) {
        $card = $paymentMethod->getExtensionAttributes()->getOpenpayCard();
        $cardId = $card->getData('card_id');
        $deviceSessionId = $card->getData('device_session_id');

        $this->validate($cartId, $cardId, $deviceSessionId);

        $paymentMethod->setAdditionalInformation('customer_card_id', $cardId);
        $paymentMethod->setAdditionalInformation('device_session_id', $deviceSessionId);
    }

    /**
     * @param string $cartId
     * @param string $cardId
     * @param string $deviceSessionId
     * @return bool
     * @throws LocalizedException
     */
    protected function validate($cartId, $cardId, $deviceSessionId) {
        $cart = $this->cartRepository->get($cartId);
        $customer = $cart->getCustomer();

        /** @var OpenpayCardType[] $cards */
        $cards = $customer->getExtensionAttributes()->getOpenpayCard();

        if (!$this->cardExists($cardId, $cards)) {
            throw new LocalizedException(__('Card does not exist'));
        }

        if (is_null($deviceSessionId)) {
            throw new LocalizedException(__('You must provide a device sessionId'));
        }

        return true;
    }

    /**
     * @param string $cardId
     * @param OpenpayCardType[] $existingCards
     * @return bool
     */
    protected function cardExists($cardId, $existingCards)
    {
        foreach ($existingCards as $card) {
            if ($card->getCardId() === $cardId) {
                return true;
            }
        }

        return false;
    }
}