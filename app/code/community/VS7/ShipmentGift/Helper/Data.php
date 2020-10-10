<?php

class VS7_ShipmentGift_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getStocksForProduct(Mage_Catalog_Model_Product $product, $storeId)
    {
        $localStores = Mage::getSingleton('Wyomind_Localstores_Model_Localstoresplace');
        $places = $localStores->getPlacesByStoreId($storeId);

        $advancedinventory = Mage::getModel("advancedinventory/advancedinventory");
        $stock = $advancedinventory->getCollection()
            ->addFieldToFilter(
                array('product_id'),
                array(
                    array('eq' => $product->getId())
                )
            )
            ->addFieldToFilter(
                array('place_id'),
                array(
                    array('eq' => $places->getFirstItem()->getPlaceId())
                )
            );

        return $stock->getFirstItem()->getQuantityInStock();
    }

    public function decreaseQuantity($quantity, Mage_Catalog_Model_Product $product, $storeId)
    {
        $placeId = Mage::getSingleton('Wyomind_Localstores_Model_Localstoresplace')
            ->getPlacesByStoreId($storeId)
            ->getFirstItem()
            ->getPlaceId();

        $productData = array(
            'id'  => $product->getId(),
            'qty' => $quantity
        );

        // decrement local stock
        Mage::helper('advancedinventory')->decrementLocalStock(array($productData), $placeId);

        // due advancedinventory does not automatically decrease global stock we need to do that by our own :|
        $productStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
        $productStock->subtractQty($quantity);
        $productStock->save();
    }
}