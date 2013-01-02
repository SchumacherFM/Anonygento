<?php

/* @var $installer SchumacherFM_Demo1_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->addAttribute('customer', 'mydemo1', array(
    'type'                    => 'varchar',
    'backend'                 => '',
    'frontend'                => '',
    'label'                   => 'Demo1 Attribute',
    'input'                   => 'text',
    'class'                   => '',
    'source'                  => '',
    'global'                  => 0, // 2 website, 0 storeview
    'visible'                 => TRUE,
    'required'                => FALSE,
    'user_defined'            => TRUE,
    'default'                 => '',
    'searchable'              => FALSE,
    'filterable'              => FALSE,
    'comparable'              => FALSE,
    'visible_on_front'        => FALSE,
    'unique'                  => FALSE,
    'is_configurable'         => FALSE,
    'used_in_product_listing' => FALSE,
));

$installer->endSetup();
