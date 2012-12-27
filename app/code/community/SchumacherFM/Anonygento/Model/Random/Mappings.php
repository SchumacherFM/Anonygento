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
            foreach ($data['fill'] as $attributeName => $options) {
                $data[] = $attributeName;
            }
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
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12),
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
            'quote_id',
            'shipping_address_id',
            'billing_address_id',

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

    public function setOrderGrid()
    {
        return $this->addData(array(
            'anonymized' => 'anonymized',

            // system attributes
            'entity_id',
            'customer_id',

        ));

    }

    public function setOrderPayment()
    {
        return $this->addData(array(

            'name'       => 'cc_owner',
            'month'      => 'cc_exp_month',
            'year'       => 'cc_exp_year',
            'last4'      => 'cc_last4',
            'ccType'     => 'cc_type',

            'fill'       => array(
                /**
                 * @todo use reality like values instead of the
                 *       getRandomString method :-(
                 */

                'account_status'               => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'additional_data'              => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'additional_information'       => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'address_status'               => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'anet_trans_method'            => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_approval'                  => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_avs_status'                => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_cid_status'                => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_debug_request_body'        => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_debug_response_body'       => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_debug_response_serialized' => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_number_enc'                => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_secure_verify'             => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_ss_issue'                  => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_ss_start_month'            => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_ss_start_year'             => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_status'                    => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_status_description'        => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'cc_trans_id'                  => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'echeck_account_name'          => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'echeck_account_type'          => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'echeck_bank_name'             => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'echeck_routing_number'        => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'echeck_type'                  => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'last_trans_id'                => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'paybox_request_number'        => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'po_number'                    => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'protection_eligibility'       => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),

            ),

            // system attributes
            'anonymized' => 'anonymized',
            'entity_id',
            'account_status',
            'additional_data',
            'additional_information',
            'address_status',
            'anet_trans_method',
            'cc_approval',
            'cc_avs_status',
            'cc_cid_status',
            'cc_debug_request_body',
            'cc_debug_response_body',
            'cc_debug_response_serialized',
            'cc_number_enc',
            'cc_secure_verify',
            'cc_ss_issue',
            'cc_ss_start_month',
            'cc_ss_start_year',
            'cc_status',
            'cc_status_description',
            'cc_trans_id',
            'echeck_account_name',
            'echeck_account_type',
            'echeck_bank_name',
            'echeck_routing_number',
            'echeck_type',
            'last_trans_id',
            'paybox_request_number',
            'po_number',
            'protection_eligibility',

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

            'fill'       => array(
                'password_hash' => array(
                    'model'  => NULL,
                    'helper' => 'core',
                    'method' => 'getRandomString',
                    'args'   => array(12)
                ),
                'customer_note' => array(
                    'model'  => 'schumacherfm_anonygento/random_loremIpsum',
                    'helper' => NULL,
                    'method' => 'getLoremIpsum',
                    'args'   => array(10, 'plain')
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
                    'model'  => 'schumacherfm_anonygento/random_loremIpsum',
                    'helper' => NULL,
                    'method' => 'getLoremIpsum',
                    'args'   => array(10, 'plain')
                ),

            ),
            // system attributes
            'address_id',

        ));

    }

}