<?php
namespace Degaray\Openpay\Api\Data;

use \Magento\Framework\Api\ExtensibleDataInterface;
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/11/15
 * Time: 01:39 PM
 *
 * Interface CardInterface
 * @package Degaray\Openpay\Api\Data
 */
interface CardInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const CUSTOMER_ID = 'customer_id';
    const OPENPAY_CARD_ID = 'openpay_card_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**#@-*/

    /**
     * Get ID
     *
     * @api
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @api
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get customer ID
     *
     * @api
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer ID
     *
     * @api
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);


    /**
     * Get Openpay ID
     *
     * @api
     * @return string|null
     */
    public function getOpenpayCardId();

    /**
     * Set Openpay Card ID
     *
     * @api
     * @param string $cardId
     * @return $this
     */
    public function setOpenpayCardId($cardId);

    /**
     * Set creation at datetime
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set creation time
     *
     * @param \DateTime $created_at
     * @return CardInterface
     */
    public function setCreatedAt($created_at);

    /**
     * Set updated at datetime
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * Set updated at time
     *
     * @param \DateTime $updated_at
     * @return CardInterface
     */
    public function setUpdatedAt($updated_at);
}