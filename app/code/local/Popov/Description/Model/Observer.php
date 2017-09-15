<?php

/**
 * Change category description before layout render
 *
 * @category Popov
 * @package Popov_Description
 * @author Serhii Popov <popow.sergiy@gmail.com>
 * @datetime: 23.11.16 11:02
 */
class Popov_Description_Model_Observer extends Varien_Event_Observer
{
    public function hookToChangeCategoryDescription(Varien_Event_Observer $observer)
    {
        if (!Mage::app()->getStore()->isAdmin()) {
            Mage::helper('popov_description')->resolve();
        }
    }
}