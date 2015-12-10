<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 9/12/15
 * Time: 11:37 AM
 */

namespace Degaray\Openpay\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Card extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('card_entity', 'entity_id');
    }
}
