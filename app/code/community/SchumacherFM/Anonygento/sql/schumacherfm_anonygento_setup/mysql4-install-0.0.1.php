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

    $installer->getTable('sales/creditmemo_grid'),
    $installer->getTable('sales/invoice_grid'),
    $installer->getTable('sales/shipment_grid'),

    $installer->getTable('newsletter/subscriber'),

    $installer->getTable('giftmessage/message'),

    /*
     * @todo add tables from enterprise
     * enterprise_giftregistry_entity
     * enterprise_rma
     * enterprise_rma_grid
     * enterprise_sales_creditmemo_grid_archive
     * enterprise_sales_invoice_grid_archive
     * enterprise_sales_order_grid_archive
     * enterprise_sales_shipment_grid_archive
     * enterprise_scheduled_operations ?
     */
);

foreach ($entities as $name) {

    /**
     * instead of adding it as an attribute we directly altering the main table
     */
    if ($installer->tableExists($name)) {
        $installer->run('ALTER TABLE  ' . $name . ' ADD `anonymized` tinyint(1) not null default 0;');
    }
}


$installer->endSetup();