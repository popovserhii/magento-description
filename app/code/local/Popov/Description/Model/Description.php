<?php

class Popov_Description_Model_Description extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('stgdescription/description');
    }

    public function loadStoreDescription($manufacturerId, $categoryId)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('manufacturer_id', $manufacturerId)
            ->addFieldToFilter('category_id', $categoryId)
            ->addFieldToFilter('store_id', array('in' => array(0, Mage::app()->getStore()->getId()))); // add in condition

        foreach ($collection as $item) {
            if (Mage::app()->getStore()->getId() == $item->getStoreId()) {
                break;
            }
        }

        if (isset($item)) {
            return $item;
        }
    }
}