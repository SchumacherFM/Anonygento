<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Random_Mappings extends Varien_Object
{
    /**
     * @return array
     */
    public function getEntityAttributes()
    {
        $data = $this->getData();

        if (isset($data['fill'])) {
            unset($data['fill']);
        }

        return array_values($data);
    }

    /**
     * these mappings defined the fields which can be overwritten
     * base model is: SchumacherFM_Anonygento_Model_Random_Customer
     *
     * RandomCusteomer => CustomerAddress
     */

    public function setCustomer()
    {
        return $this->addData(array(
            'prefix'     => 'prefix',
            'email'      => 'email',
            'firstname'  => 'firstname',
            'lastname'   => 'lastname',
            'suffix'     => 'suffix',
            'anonymized' => 'anonymized',
            'fill'       => array(
                'password_hash' => array(
                    'exec' => 'getRandomString',
                    'args' => array(32)
                ),
            ),
        ));
    }

    public function setCustomerAddress()
    {

        return $this->addData(array(
            'anonymized' => 'anonymized',
            'prefix'     => 'prefix',
            'firstname'  => 'firstname',
            'lastname'   => 'lastname',
            'company'    => 'company',
            'telephone'  => 'telephone',
            'fax'        => 'fax',
            'street'     => 'street',
        ));

    }

    /**
     * key = from customer model
     * value = newsletter subscriber column name
     *
     * @return array
     */
    public function setNewsletterSubscriber()
    {

        return $this->addData(array(
            'anonymized' => 'anonymized',
            'email'      => 'subscriber_email',
        ));

    }

    public function setGiftMessage()
    {
        return $this->addData(array(
            'anonymized' => 'anonymized',
            'email'      => 'sender',
        ));

    }

    /**
     * customer fields => sales_flat_order fields
     *
     * @return array
     */
    public function setOrder()
    {
        return $this->addData(array(
            'email'      => 'customer_email',
            'firstname'  => 'customer_firstname',
            'lastname'   => 'customer_lastname',
            'middle'     => 'customer_middlename',
            'suffix'     => 'customer_suffix',
            'prefix'     => 'customer_prefix',
            'taxvat'     => 'customer_taxvat',
            'remote_ip'  => 'remote_ip',
            'anonymized' => 'anonymized',

            // system attributes
            'customer_id',
            'entity_id',

        ));

    }

    public function setOrderAddress()
    {
        return $this->addData(array(
            'fax'        => 'fax',
            'street'     => 'street',
            'email'      => 'email',
            'telephone'  => 'telephone',
            'company'    => 'company',
            'firstname'  => 'firstname',
            'lastname'   => 'lastname',
            'middlename' => 'middlename',
            'suffix'     => 'suffix',
            'prefix'     => 'prefix',
            'taxvat'     => 'vat_id',
            'anonymized' => 'anonymized',

            // system attributes
            'entity_id',

        ));

    }

    /**
     * customer fields => sales_flat_quote fields
     *
     * @return array
     */
    public function setQuote()
    {
        return $this->addData(array(
            'email'      => 'customer_email',
            'firstname'  => 'customer_firstname',
            'lastname'   => 'customer_lastname',
            'middle'     => 'customer_middlename',
            'suffix'     => 'customer_suffix',
            'prefix'     => 'customer_prefix',
            'taxvat'     => 'customer_taxvat',
            'remote_ip'  => 'remote_ip',
            'anonymized' => 'anonymized',

            // @todo fields not in the customer object but needed
            'fill'       => array(
                'password_hash' => array(
                    'exec' => 'getRandomString',
                    'args' => array(32)
                ),
                'customer_note' => array(
                    'exec' => 'getLoremIpsum',
                    'args' => array(80)
                ),
            ),

            // system attributes
            'customer_id',
            'entity_id',

        ));

    }

    public function setQuoteAddress()
    {
        return $this->addData(array(
            'fax'        => 'fax',
            'street'     => 'street',
            'email'      => 'email',
            'telephone'  => 'telephone',
            'company'    => 'company',
            'firstname'  => 'firstname',
            'lastname'   => 'lastname',
            'middlename' => 'middlename',
            'suffix'     => 'suffix',
            'prefix'     => 'prefix',
            'taxvat'     => 'vat_id',
            'anonymized' => 'anonymized',

            // @todo fields not in the customer object but needed
            'fill'       => array(

                'customer_notes' => array(
                    'exec' => 'getLoremIpsum',
                    'args' => array(80)
                ),
            ),
            // system attributes
            'address_id',

        ));

    }

}