<?php

class VS7_ShipmentGift_Adminhtml_ShipmentgiftController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $this->getRequest()->getParam('sku'));
        $quantity = ($this->getRequest()->getParam('quantity') >= 1) ? $this->getRequest()->getParam('quantity') : 1;

        if ($product instanceof Mage_Catalog_Model_Product) {
            $result = array();
            if (Mage::helper('vs7_shipmentgift')->getStocksForProduct($product, 1) < $quantity) {
                $result['error'] = 'Not enough products in stock!';
            } else {
                $result['sku'] = $product->getSku();
                $result['name'] = $product->getName();
                try {
                    $result['image'] = (string)Mage::helper('catalog/image')->init($product, 'image')->resize(80);
                } catch (Exception $e) {
                    $result['image'] = '';
                }
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        } else {
            $result = array();
            $result['error'] = 'No product has been found for this SKU';
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }

        return $this;
    }


}