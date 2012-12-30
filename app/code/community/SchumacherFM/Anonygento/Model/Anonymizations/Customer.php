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
        $customerMap = $this->_getMappings('Customer');

        $customerCollection = $this->_getCustomerCollection($customerMap);

        $i = 0;
        foreach ($customerCollection as $customer) {
            $this->_anonymizeCustomer($customer);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomer(Mage_Customer_Model_Customer $customer)
    {
        // SchumacherFM_Anonygento_Model_Random_Customer
        $customer = $this->_getRandomCustomer()->getCustomer($customer);

        $this->_anonymizeCustomerAddresses($customer);
        $this->_anonymizeCustomerNewsletter($customer);

        $this->_anonymizeOrder($customer);
        $this->_anonymizeQuote($customer);

        // save the customer at the end to ensure that all other entities have been
        // anonymized .. just in case the user aborts the script
        $customer->save();
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

}