<?php

/* @var $installer SchumacherFM_Demo2_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = 'catalog/product';

$installer->addAnonymizedColumn($entity)->addAnonymizedAttribute($entity);

$installer->endSetup();
