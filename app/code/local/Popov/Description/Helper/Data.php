<?php
class Popov_Description_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function resolve()
    {
        $problemed = 'mana';

        $helperClass = Mage::getConfig()->getHelperClassName($helperKey = 'popov_description/resolver_' . $problemed);
        if (class_exists($helperClass)) {
            /**
             * @todo For next case it'll be the best to create option page Adapter which can return needed data for all cases (mana, std...)
             */
            $request = Mage::app()->getRequest();
            $helper = Mage::helper($helperKey);
            $helper->resolve($request);
        }
    }
}