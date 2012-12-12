<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Mapping extends Varien_Object
{

    /**
     * @return array
     */
    public function getCustomerMapping()
    {
        return array(
            'prefix' => 'prefix',
            'firstname' => 'first_name',
            'middlename' => '',
            'lastname' => 'last_name',
            'suffix' => 'suffix',
            'email' => 'email',
        );
    }

    /**
     * @return array
     */
    public function getQuoteMapping()
    {
        return array(
            'customer_prefix' => 'prefix',
            'customer_firstname' => 'first_name',
            'customer_middlename' => '',
            'customer_lastname' => 'last_name',
            'customer_suffix' => 'suffix',
            'customer_email' => 'email',
            'customer_taxvat' => '',
            'remote_ip' => 'ip_v4_address',
        );
    }

    /**
     * @return array
     */
    public function getOrderMapping()
    {
        return array(
            'customer_prefix' => 'prefix',
            'customer_firstname' => 'first_name',
            'customer_middlename' => '',
            'customer_lastname' => 'last_name',
            'customer_suffix' => 'suffix',
            'customer_email' => 'email',
            'customer_taxvat' => '',
            'remote_ip' => 'ip_v4_address',
        );
    }

    /**
     * @return array
     */
    public function getAddressMapping()
    {
        return array(
            'prefix' => 'prefix',
            'firstname' => 'first_name',
            'middlename' => '',
            'lastname' => 'last_name',
            'suffix' => 'suffix',
            'company' => 'bs',
            'street' => 'street_address',
            'telephone' => 'zip_code',
            'fax' => '',
            'vat_id' => '',
            'email' => 'email',
        );
    }

}