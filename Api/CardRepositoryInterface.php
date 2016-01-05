<?php

namespace Degaray\Openpay\Api;

use Degaray\Openpay\Api\Data\CardInterface;

/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/11/15
 * Time: 01:53 PM
 *
 * Interface CardRepositoryInterface
 * @package Degaray\Openpay\Api
 */
interface CardRepositoryInterface
{
    /**
     * Save customer card.
     *
     * @api
     * @param string $customerId
     * @param CardInterface $card
     * @return \Degaray\Openpay\Api\Data\CardInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save($customerId, CardInterface $card);

    /**
     * Retrieve customer card.
     *
     * @api
     * @param int $cardId
     * @return \Degaray\Openpay\Api\Data\CardInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($cardId);

    /**
     * Delete customer card.
     *
     * @api
     * @param \Degaray\Openpay\Api\Data\CardInterface $cardInterface
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(CardInterface $cardInterface);

    /**
     * Delete customer card by ID.
     *
     * @api
     * @param int $cardId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($cardId);

    /**
     * @param $openpayCustomerId
     * @return array
     */
    public function getCardsByOpenpayCustomerId($openpayCustomerId);
}
