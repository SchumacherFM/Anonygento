<?php

/* @var $installer SchumacherFM_Demo2_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$entity = 'catalog/product';

$installer
    ->getConnection()
    ->addColumn($installer->getTable($entity), 'anonymized', 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 0');

$installer->removeAttribute($entity, 'anonymized');

$installer->addAttribute($entity, 'anonymized', array(
    'type'         => 'static',
    'group'        => 'General',
    'label'        => 'Is anonymized',
    'is_visible'   => TRUE,
    'visible'      => TRUE,
    'default'      => 0,
    'user_defined' => FALSE,
    'input'        => 'boolean',
    'backend'      => 'customer/attribute_backend_data_boolean',
    'required'     => FALSE,
    'is_system'    => TRUE,
    'position'     => 200,
    'sort_order'   => 200,
));

$installer->endSetup();
