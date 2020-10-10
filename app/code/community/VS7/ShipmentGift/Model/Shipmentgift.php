<?php

class VS7_ShipmentGift_Model_Shipmentgift extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('vs7_shipmentgift/shipmentgift');
    }
    
    public function loadByOrderId($id)
    {
        $this->setData($this->getResource()->loadByOrderId($id));
        return $this;
    }
}