<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Random_Mappings
{
    /**
     * these mappings defined the fields which can be overwritten
     * base model is: SchumacherFM_Anonygento_Model_Random_Customer
     *
     * RandomCusteomer => CustomerAddress
     */

    public static function getCustomer()
    {
        return array(
            'prefix'    => 'prefix',
            'email'     => 'email',
            'firstname' => 'firstname',
            'lastname'  => 'lastname',
            'suffix'    => 'suffix',
            'anonymized' => 'anonymized',
        );
    }

    public static function getCustomerAddress()
    {

        return array(
            'anonymized' => 'anonymized',
            'prefix'     => 'prefix',
            'firstname'  => 'firstname',
            'lastname'   => 'lastname',
            'company'    => 'company',
            'telephone'  => 'telephone',
            'fax'        => 'fax',
            'street'     => 'street',

        );

    }

    /**
     * key = from customer model
     * value = newsletter subscriber column name
     *
     * @return array
     */
    public static function getNewsletterSubscriber()
    {

        return array(
            'anonymized' => 'anonymized',
            'email'      => 'subscriber_email',
        );

    }

    public static function getGiftMessage()
    {
        return array(
            'anonymized' => 'anonymized',
            'email'      => 'sender',
        );

    }

    /**
     * customer fields => sales_flat_order fields
     *
     * @return array
     */
    public static function getOrder()
    {
        return array(
            'email'      => 'customer_email',
            'firstname'  => 'customer_firstname',
            'lastname'   => 'customer_lastname',
            'middle'     => 'customer_middlename',
            'suffix'     => 'customer_suffix',
            'prefix'     => 'customer_prefix',
            'taxvat'     => 'customer_taxvat',
            'remote_ip'  => 'remote_ip',
            'anonymized' => 'anonymized',

        );

    }

}