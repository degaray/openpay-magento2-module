<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 4/12/15
 * Time: 04:35 PM
 */

namespace Degaray\Openpay\Model\Data;

use Degaray\Openpay\Api\Data\CardInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class Card
 * @package Degaray\Openpay\Model\Data
 */
class Card extends AbstractModel implements CardInterface
{
    /**
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->setData(self::ENTITY_ID, $id);
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
        return $this;
    }

    /**
     * @return string
     */
    public function getOpenpayCardId()
    {
        return $this->getData(self::OPENPAY_CARD_ID);
    }

    /**
     * @param string $cardId
     * @return $this
     */
    public function setOpenpayCardId($cardId)
    {
        $this->setData(self::OPENPAY_CARD_ID, $cardId);
        return $this;
    }

    /**
     * @return Datetime
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param DateTime $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->setData(self::CREATED_AT, $created_at);
        return $this;
    }

    /**
     * @return Datetime
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param DateTime $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->setData(self::UPDATED_AT, $updated_at);
        return $this;
    }
}
