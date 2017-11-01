<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2017 Serhii Popov
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Popov
 * @package Popov_<package>
 * @author Serhii Popov <popow.sergiy@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */
class Popov_Description_Helper_Resolver_Mana extends Mage_Core_Helper_Abstract
{
    /**
     * @return void
     */
    public function resolve()
    {
        $category = Mage::getModel('catalog/layer')->getCurrentCategory();

        if (!$category) {
            return;
        }

        $fitting = $this->getFittingAttributes();

        if ($fitting['value']['page'] > 1) {
            $category->setDescription('');

            return;
        }
		


        // only for Megamuscle
        if (!isset($fitting['value']['manufacturer'])) {
            return;
        } elseif (count($fitting['value']['manufacturer']) > 0) {
            $category->setDescription('');

            return;
        }

        return; // як вияснилось потрібно тягнути геть з іншого місця опис

        /* @var $optionPage Mana_AttributePage_Model_OptionPage_Store */
        $optionPage = Mage::getModel('mana_attributepage/optionPage_store');
        $optionPage->setData('store_id', Mage::app()->getStore()->getId());
        $optionPage->load($fitting['value']['manufacturer'][0], 'title');

        $category->setDescription($optionPage->getData('description'));
    }

    protected function getFittingAttributes()
    {
        /** @var Mage_Catalog_Block_Product_List_Toolbar $toolbar */
        $toolbar = Mage::getBlockSingleton('catalog/product_list_toolbar');

        $fitting = [];
        //if (($currentPage = $toolbar->getCurrentPage()) > 1) {
            $currentPage = $toolbar->getCurrentPage();
            $seoAttr = 'page';
            $fitting['id'][$seoAttr] = $currentPage;
            $fitting['value'][$seoAttr] = $currentPage;
        //}

        /** @var Mage_Catalog_Model_Layer_Filter_Item $filter */
        /** @var Mage_Eav_Model_Entity_Attribute_Frontend_Default $attrFront */
        $filters = Mage::getSingleton('catalog/layer')->getState()->getFilters();
        foreach ($filters as $filter) {
            $attribute = $filter->getFilter()->getData('attribute_model');
            if (is_null($attribute)) {
                //$attribute = Mage::getModel('catalog/resource_eav_attribute');
                // Mage::throwException(Mage::helper('catalog')->__('The attribute model is not defined'));
                continue;
            }


            $attrFront = $filter->getFilter()->getAttributeModel()->getFrontend();
            $attr = $attrFront->getAttribute();
            $seoAttr = $attr->getAttributeCode();
            $id = $attr->getSource()->getOptionId($filter->getValue());
            $value = $attrFront->getOption($filter->getValue());
            $fitting['id'][$seoAttr][] = $id;
            $fitting['value'][$seoAttr][] = $value;
        }

        return $fitting;
    }
}