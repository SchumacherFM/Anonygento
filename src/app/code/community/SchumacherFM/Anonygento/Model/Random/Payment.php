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
        $paymentData = array(
            'month'  => mt_rand(1, 12),
            'year'   => mt_rand(2013, 2022),
            'last4'  => mt_rand(1000, 9999),
            'ccType' => $this->_getCcType(),
        );

        if ($customer === null) {
            $this->_currentCustomer = new Varien_Object();
            $this->_currentCustomer->setEntityId(mt_rand());

            $data = array(
                'prefix'     => $this->_getCustomerPrefixString(),
                'firstname'  => $this->_getCustomerFirstName(),
                'middlename' => $this->_getCustomerFirstName(),
                'lastname'   => $this->_getCustomerLastName(),
                'name'       => $this->_getCustomerFirstName() . ' ' . $this->_getCustomerLastName(),
            );

            $this->_currentCustomer->addData($data);
            $this->_getRandEmail();

        } else {
            $this->_currentCustomer = $customer;
            $this->_currentCustomer->setName($this->_currentCustomer->getFirstname() . ' ' . $this->_currentCustomer->getLastname());
        }

        $this->_currentCustomer->addData($paymentData);

    }

    /**
     * @return string
     */
    protected function _getCcType()
    {
        $types = array_keys(Mage::getModel('payment/config')->getCcTypes());
        return $types[mt_rand() % count($types)];

    }

    /**
     *
     */
    protected function _addPaymentRandom()
    {

     // hmmm...
     // $attr = Mage::getSingleton('schumacherfm_anonygento/random_mappings')->setOrderPayment();

    }
}
