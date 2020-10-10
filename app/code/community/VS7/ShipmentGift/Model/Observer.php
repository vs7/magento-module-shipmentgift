<?php

class VS7_ShipmentGift_Model_Observer
{
    public function modifyForm($data)
    {
        if (Mage::app()->getStore()->isAdmin()) {
            $layout = $data->getLayout();
            if ($layout) {
                $formBlock = $data->getLayout()->getBlock('form');
                if ($formBlock) {
                    if ($formBlock->getTemplate() == 'sales/order/shipment/view/form.phtml') {
                        $formBlock->setTemplate('vs7_shipmentgift/sales_order_shipment_view_form.phtml');
                        $giftBlock = $layout->createBlock('adminhtml/template');
                        $giftBlock->setTemplate('vs7_shipmentgift/view_gift.phtml');
                        $formBlock->setChild('shipment_gift', $giftBlock);
                    }
                    if ($formBlock->getTemplate() == 'sales/order/shipment/create/form.phtml') {
                        $formBlock->setTemplate('vs7_shipmentgift/sales_order_shipment_create_form.phtml');
                        $giftBlock = $layout->createBlock('adminhtml/template');
                        $giftBlock->setTemplate('vs7_shipmentgift/create_gift.phtml');
                        $formBlock->setChild('shipment_gift', $giftBlock);
                    }
                }
            }
        }
    }

    public function saveGift($observer)
    {
        $shipmentGiftPostArray = Mage::app()->getRequest()->getPost('shipmentgift');
        $storeId = $observer->getEvent()->getShipment()->getStoreId();

        // remove the __index__ array index which is used for JS row generating
        unset($shipmentGiftPostArray['__index__']);

        foreach ($shipmentGiftPostArray as $key => $giftItem) {
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $giftItem['number']);
            if ($giftItem['quantity'] > Mage::helper('vs7_shipmentgift')->getStocksForProduct($product, $storeId)) {
                return false;
            } else {
                $shipmentGift = Mage::getModel('vs7_shipmentgift/shipmentgift');
                $shipmentGift->setParentId(Mage::app()->getRequest()->getParam('order_id'));
                $shipmentGift->setSku($giftItem['number']);
                $shipmentGift->setData('qty', $giftItem['quantity']);
                Mage::helper('vs7_shipmentgift')->decreaseQuantity($giftItem['quantity'], $product, $storeId);
                $shipmentGift->save();
            }
        }
    }
}