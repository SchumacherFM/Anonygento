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

    public function run()
    {
        $customerCollection = $this->_getCollection();

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
    protected function _anonymizeCustomer($customer)
    {

        $customer = $this->_randomCustomerModel->getCustomer($customer);
        $customer->save();

        // customer address
        $this->_anonymizeCustomerAddresses($customer);

        /**
         * @todo now find all entities where a customer id is
         */
//
//        Zend_Debug::dump($cu);
//        exit;

    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomerAddresses($customer)
    {
        $addressCollection = $customer->getAddressesCollection();
        /* @var $addressCollection Mage_Customer_Model_Resource_Address_Collection */

        $size           = (int)$addressCollection->getSize();
        $addressMapping = SchumacherFM_Anonygento_Model_Random_Mappings::getCustomerAddress();

        if ($size === 1) {

            $address = $addressCollection->getFirstItem();
            $this->_copyObjectData($customer, $address, $addressMapping);
            $address->save();

        } elseif ($size > 1) {

            foreach ($addressCollection as $address) {
                $this->_copyObjectData($customer, $address, $addressMapping);
                $address->save();
            }
        }

    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function XX_anonymizeCustomerXX($customer)
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

    /**
     * @return Mage_Customer_Model_Resource_Customer_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('customer/customer')
            ->getCollection()
            ->addAttributeToSelect(array('prefix', 'firstname', 'lastname', 'suffix'));
        /* @var $collection Mage_Customer_Model_Resource_Customer_Collection */

        $collection->addStaticField('anonymized');
        $collection->addAttributeToFilter('anonymized', 0);

        return $collection;
    }
}