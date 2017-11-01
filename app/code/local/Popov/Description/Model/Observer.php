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

    public function stub()
    {}

    public function hookToChangeCategoryDescription(Varien_Event_Observer $observer)
    {
        if (!Mage::app()->getStore()->isAdmin()) {
            Mage::helper('popov_description')->resolve();
        }
    }

    public function addCustomAttributeOutputHandler(Varien_Event_Observer $observer)
    {
        $outputHelper = $observer->getEvent()->getHelper();
        //$outputHelper->addHandler('productAttribute', $this);
        $outputHelper->addHandler('categoryAttribute', $this);
    }

    public function categoryAttribute(Mage_Catalog_Helper_Output $outputHelper, $outputHtml, $params)
    {
        $category = $params['category'];
        if (($params['attribute'] === 'name')) {
            if (Mage::helper('mageworx_seoxtemplates/config')->isUseCategorySeoName()
                && $category->getCategorySeoName()
            ) {
                $outputHtml = $this->modifyCategoryName($category);
            }
        } elseif ($params['attribute'] === 'description') {
            $outputHtml = $category->getDescription();
        }
        return $outputHtml;
    }

    /**
     * @param Mage_Catalog_Model_Category $category
     * @return string
     */
    public function modifyCategoryName($category)
    {
        if ($this->_isConvertedName) {
            return $this->_isConvertedName;
        }

        /** @var MageWorx_SeoXTemplates_Model_Converter_Category_Metatitle $metaTitleConverter */
        $metaTitleConverter = Mage::getSingleton('mageworx_seoxtemplates/converter_category_metatitle');
        $name = Mage::helper('mageworx_seoall/title')->cutPrefixSuffix($category->getCategorySeoName());

        $convertedName = $metaTitleConverter->convert($category, $name, true);

        if (!empty($convertedName) && $name != $convertedName) {
            $convertedName = trim(htmlspecialchars(html_entity_decode($convertedName, ENT_QUOTES, 'UTF-8')));
            if ($convertedName) {
                //$category->setName($convertedName);
                //return true;
                $this->_isConvertedName = $convertedName;
            }
        }

        return $this->_isConvertedName;
    }
}