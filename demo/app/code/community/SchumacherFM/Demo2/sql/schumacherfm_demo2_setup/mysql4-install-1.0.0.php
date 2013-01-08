<?php

/* @var $installer SchumacherFM_Demo2_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->addAnonymizedColumn('catalog/product');

// only for EAV models
$installer->addAnonymizedAttribute('catalog_product');

$installer->endSetup();
