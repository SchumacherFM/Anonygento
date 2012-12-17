<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     sql
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */

$installer = $this;
$installer->startSetup();

// Add reset password link token creation date attribute
$installer->addAttribute('customer', 'anonymized', array(
    'type'     => 'static',
    'input'    => 'boolean',
    'backend'  => 'customer/attribute_backend_data_boolean',
    'visible'  => TRUE,
    'required' => FALSE
));

$installer->endSetup();