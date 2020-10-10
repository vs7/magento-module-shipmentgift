<?php
 
class VS7_ShipmentGift_Model_Resource_Shipmentgift extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('vs7_shipmentgift/shipmentgift', 'entity_id');
    }

    public function loadByOrderId($id)
    {
        $select = $this->_getReadAdapter()
            ->select()
            ->from($this->getTable('vs7_shipmentgift/shipmentgift'))
            ->where('parent_id=:parent_id');
        return $this->_getReadAdapter()->fetchAll($select, array('parent_id'=>$id));
    }
}