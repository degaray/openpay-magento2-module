<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 10/12/15
 * Time: 02:13 PM
 */

namespace Degaray\Openpay\Model;


use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Indexer\Model\ResourceModel\AbstractResource;

class Card extends AbstractModel
{
    /**
     * Initialize card model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Degaray\Openpay\Model\ResourceModel\Card');
    }

    public function updateData($card)
    {
        echo 'a';
        /*
        $customerDataAttributes = $this->dataObjectProcessor->buildOutputDataArray(
            $customer,
            '\Magento\Customer\Api\Data\CustomerInterface'
        );

        foreach ($customerDataAttributes as $attributeCode => $attributeData) {
            if ($attributeCode == 'password') {
                continue;
            }
            $this->setDataUsingMethod($attributeCode, $attributeData);
        }

        $customAttributes = $customer->getCustomAttributes();
        if ($customAttributes !== null) {
            foreach ($customAttributes as $attribute) {
                $this->setDataUsingMethod($attribute->getAttributeCode(), $attribute->getValue());
            }
        }

        $customerId = $customer->getId();
        if ($customerId) {
            $this->setId($customerId);
        }

        // Need to use attribute set or future updates can cause data loss
        if (!$this->getAttributeSetId()) {
            $this->setAttributeSetId(
                CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER
            );
        }

        return $this;
        */
    }
}