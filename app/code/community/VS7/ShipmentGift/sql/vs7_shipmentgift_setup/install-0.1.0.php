<?php

$installer = $this;
$installer->startSetup();
$installer->run("

/* Shipment gift */
CREATE TABLE `{$installer->getTable('sales_flat_shipment_gift')}` (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `parent_id` int(10) unsigned NOT NULL,
    `sku` text,
    `qty` text,
    PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();