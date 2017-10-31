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
    protected $_isConvertedName;

    public function hookToChangeCategoryDescription(Varien_Event_Observer $observer)
    {
        if (!Mage::app()->getStore()->isAdmin()) {
            Mage::helper('popov_description')->resolve();
        }
    }

    /**
     * Modify category data and meta head
     * Event: core_block_abstract_to_html_before
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function hookToModifyCategoryName(Varien_Event_Observer $observer)
    {
        /** @var Mage_Page_Block_Html_Head $block */
        $block = $observer->getBlock();

        if ($block->getNameInLayout() != 'head') {
            return false;
        }

        if ('catalog_category_view' == Mage::helper('mageworx_seoall/request')->getCurrentFullActionName()) {
            $category = Mage::registry('current_category');
            if (is_object($category)
                && Mage::helper('mageworx_seoxtemplates/config')->isUseCategorySeoName()
                && $category->getCategorySeoName()
            ) {
                $this->modifyCategoryName($category);

                /** @var MageWorx_SeoXTemplates_Model_DynamicRenderer_Category $dynamicRenderer */
                /*$dynamicRenderer = Mage::getSingleton('mageworx_seoxtemplates/dynamicRenderer_category');
                $dynamicRenderer->modifyCategoryTitle($category, $block);
                $dynamicRenderer->modifyCategoryMetaDescription($category, $block);
                $dynamicRenderer->modifyCategoryMetaKeywords($category, $block);
                $dynamicRenderer->modifyCategoryDescription($category);*/
            }
        }
    }

    /**
     * @param Mage_Catalog_Model_Category $category
     * @param Mage_Page_Block_Html_Head $block
     * @return boolean
     */
    public function modifyCategoryName($category)
    {
        if ($this->_isConvertedName) {
            return true;
        }

        /** @var MageWorx_SeoXTemplates_Model_Converter_Category_Metatitle $metaTitleConverter */
        $metaTitleConverter = Mage::getSingleton('mageworx_seoxtemplates/converter_category_metatitle');
        $name = Mage::helper('mageworx_seoall/title')->cutPrefixSuffix($category->getCategorySeoName());

        $convertedName = $metaTitleConverter->convert($category, $name, true);

        if (!empty($convertedName) && $name != $convertedName) {
            $convertedName = trim(htmlspecialchars(html_entity_decode($convertedName, ENT_QUOTES, 'UTF-8')));
            if ($convertedName) {
                $category->setName($convertedName);
                $this->_isConvertedName = true;
                return true;
            }
        }

        return false;
    }
}