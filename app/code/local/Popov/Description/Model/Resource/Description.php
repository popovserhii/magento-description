<?php

class Popov_Description_Model_Resource_Description extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('stgdescription/table_description', 'id');
    }

}