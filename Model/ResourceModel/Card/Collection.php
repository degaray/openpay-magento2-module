<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 8/12/15
 * Time: 04:42 PM
 */

namespace Degaray\Openpay\Model\ResourceModel\Card;


use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Name of card model
     */
    const CARD_MODEL_NAME = 'Degaray\Openpay\Model\Data\Card';

    /**
     *
     * Name of card resource model
     */
    const CARD_RESOURCE_MODEL_NAME = 'Degaray\Openpay\Model\ResourceModel\Card';

    /**
     * Field name of customer_id in card_entity table
     */
    const CUSTOMER_ID_FIELD_NAME = 'customer_id';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::CARD_MODEL_NAME, self::CARD_RESOURCE_MODEL_NAME);
    }

    /**
     * @param int $customerId
     * @return $this
     */
    public function getCustomerCardsByCustomerId($customerId)
    {
        if (is_null($customerId)) {
            $this->_logger->error('You must set the customer first');
        }

        $this->addFilter(self::CUSTOMER_ID_FIELD_NAME, $customerId);
        $this->loadData();

        /** @var \Magento\Customer\Api\Data\CustomerInterface[] $items */
        $items = $this->getItems();
        
        return $items;
    }
}
