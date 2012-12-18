<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     sql
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
$installer = $this;
/* @var $installer SchumacherFM_Anonygento_Model_Resource_Setup */

$installer->startSetup();

$entities = array(
    $installer->getTable('customer/entity'),
    $installer->getTable('customer/address_entity'),

    $installer->getTable('sales/order'),
    $installer->getTable('sales/order_address'),
    $installer->getTable('sales/order_grid'),
    $installer->getTable('sales/order_payment'),

    $installer->getTable('sales/quote'),
    $installer->getTable('sales/quote_address'),
    $installer->getTable('sales/quote_payment'),

    $installer->getTable('sales/creditmemo'),
    $installer->getTable('sales/creditmemo_grid'),
    $installer->getTable('sales/invoice'),
    $installer->getTable('sales/invoice_grid'),
    $installer->getTable('sales/shipment'),
    $installer->getTable('sales/shipment_grid'),

    $installer->getTable('newsletter/subscriber'),

    $installer->getTable('giftmessage/message'),

    /*
     * @todo add tables from enterprise
     * enterprise_giftregistry_entity
     * enterprise_rma
     * enterprise_rma_grid
     * enterprise_scheduled_operations ?
     *
     */
);

if ($this->isEnterpriseEdition()) {
    $entities[] = $installer->getTable('enterprise_salesarchive/order_grid');
    $entities[] = $installer->getTable('enterprise_salesarchive/creditmemo_grid');
    $entities[] = $installer->getTable('enterprise_salesarchive/invoice_grid');
    $entities[] = $installer->getTable('enterprise_salesarchive/shipment_grid');
}

//Zend_Debug::dump($entities);
//exit;

foreach ($entities as $tableName) {

    $installer->getConnection()
        ->addColumn($tableName, 'anonymized', array(
            'type'    => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'comment' => 'Is anonymized',
        ));

}




$attributeEntities = array(
    'customer',
    'customer_address',
    'creditmemo',
    'order',
    'invoice',
    'shipment',

);
foreach ($attributeEntities as $name) {

    $installer->addAttribute($name, 'anonymized', array(
        'type'     => 'static',
        'default'  => 0,
        'input'    => 'boolean',
        'backend'  => 'customer/attribute_backend_data_boolean',
        'visible'  => TRUE,
        'required' => FALSE,
        'label'    => 'Is anonymized'
    ));
}

/*
 * fill values
 *
 * insert into `customer_entity_int` (entity_type_id,attribute_id,entity_id,value)
 SELECT entity_type_id,206,entity_id,0 FROM `customer_entity`
 * */

$installer->endSetup();