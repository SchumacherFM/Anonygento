<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Customers extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    public function run()
    {

        $this->_anonymizeCustomers();

    }

    /**
     * @param Mage_Customer_Model_Resource_Customer_Collection $customers
     */
    protected function _anonymizeCustomers($customers)
    {
        foreach ($customers as $customer) {

            $this->_anonymizeCustomer($customer);
        }
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomer($customer)
    {
        $randomData = $this->_getRandomData();

        foreach ($this->_getCustomerMapping() as $customerKey => $randomDataKey) {
            if (!$customer->getData($customerKey)) {
                continue;
            }

            if (strlen($randomDataKey)) {
                $customer->setData($customerKey, $randomData[$randomDataKey]);
            } else {
                $customer->setData($customerKey, '');
            }
        }

        $customer->getResource()->save($customer);
        $this->_anonymizedCustomerIds[] = $customer->getId();

        /* @var $subscriber Mage_Newsletter_Model_Subscriber */
        $subscriber = Mage::getModel('newsletter/subscriber');
        $subscriber->loadByEmail($customer->getOrigData('email'));
        if ($subscriber->getId()) {
            $this->_anonymizeNewsletterSubscriber($subscriber, $randomData);
        }

        $this->_anonymizeQuotes($customer, $randomData);
        $this->_anonymizeOrders($customer, $randomData);
        $this->_anonymizeCustomerAddresses($customer, $randomData);
    }

}