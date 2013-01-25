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
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeCustomer');
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomer(Mage_Customer_Model_Customer $customer)
    {
        $randomCustomer = $this->_getRandomCustomer()->getCustomer();
        $this->_copyObjectData($randomCustomer, $customer);

        $this->_anonymizeCustomerAddresses($customer);
        $this->_anonymizeCustomerNewsletter($customer);

        // save the customer at the end to ensure that all other entities have been
        // anonymized .. just in case the user aborts the script
        $customer->getResource()->save($customer);
        $customer = null;
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
    protected function _anonymizeCustomerAddresses(Mage_Customer_Model_Customer $customer)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_customerAddress')->anonymizeByCustomer($customer);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeQuote(Mage_Customer_Model_Customer $customer)
    {
        Mage::throwException('Method disabled');
//        Mage::getSingleton('schumacherfm_anonygento/anonymizations_quote')->anonymizeByCustomer($customer);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeOrder(Mage_Customer_Model_Customer $customer)
    {
        Mage::throwException('Method disabled');
//        Mage::getSingleton('schumacherfm_anonygento/anonymizations_order')->anonymizeByCustomer($customer);
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('customer/customer')->setOrder('entity_id', 'asc');
    }
}