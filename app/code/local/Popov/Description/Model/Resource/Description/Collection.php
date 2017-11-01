<?php

class Popov_Description_Model_Resource_Description_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('stgdescription/description');
    }

}