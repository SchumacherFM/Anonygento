<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Customer extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * anonymizes customer and related customer addresses
     */

    public function run()
    {
        parent::run($this->_getCollection(), '_anonymizeCustomer');
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomer(Mage_Customer_Model_Customer $customer)
    {
        $randomCustomer = $this->_getRandomCustomer()->getCustomer($customer);

        $this->_copyObjectData($randomCustomer, $customer, $this->_getMappings('Customer'));

        $this->_anonymizeCustomerAddresses($randomCustomer);
        $this->_anonymizeCustomerNewsletter($randomCustomer);

        $this->_anonymizeOrder($randomCustomer);
        $this->_anonymizeQuote($randomCustomer);

        // save the customer at the end to ensure that all other entities have been
        // anonymized .. just in case the user aborts the script
        $customer->save();
//        $customer->getResource()->save($customer);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomerNewsletter(Mage_Customer_Model_Customer $customer)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_newsletterSubscriber')->anonymizeByCustomer($customer);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeQuote(Mage_Customer_Model_Customer $customer)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_quote')->anonymizeByCustomer($customer);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeOrder(Mage_Customer_Model_Customer $customer)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_order')->anonymizeByCustomer($customer);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomerAddresses(Mage_Customer_Model_Customer $customer)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_customerAddress')->anonymizeByCustomer($customer);
    }

    /**
     * @return Mage_Customer_Model_Resource_Customer_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('customer/customer', 'Customer');
    }
}