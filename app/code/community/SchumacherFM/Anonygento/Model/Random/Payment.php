<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Random_Payment extends SchumacherFM_Anonygento_Model_Random_AbstractWeird
{

    /**
     * @param Mage_Customer_Model_Customer $customer
     *
     * @return Mage_Customer_Model_Customer|Varien_Object
     */
    public function getPayment(Mage_Customer_Model_Customer $customer = null)
    {
        $this->_initCurrentCustomer($customer);

        $this->_addPaymentRandom();

        Mage::dispatchEvent('anonygento_random_customer_getpayment_after', array(
            'payment' => $this,
        ));

        return $this->_currentCustomer;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _initCurrentCustomer(Mage_Customer_Model_Customer $customer)
    {
        if ($customer === null) {
            $this->_currentCustomer = new Varien_Object();
            $this->_currentCustomer->setEntityId(mt_rand());

            $data = array(
                'prefix'     => $this->_getCustomerPrefixString(),
                'firstname'  => $this->_getCustomerFirstName(),
                'middlename' => $this->_getCustomerFirstName(),
                'lastname'   => $this->_getCustomerLastName(),
                'anonymized' => 1,
            );

            $this->_currentCustomer->addData($data);
            $this->_getRandEmail();

        } else {
            $this->_currentCustomer = $customer;
        }

    }

    /**
     *
     */
    protected function _addPaymentRandom()
    {
        $attr = Mage::getSingleton('schumacherfm_anonygento/random_mappings')->setOrderPayment();

//        $xxx = $attr->getData();
//        ksort($xxx);
//        var_export($xxx);
//        exit;

    }
}
