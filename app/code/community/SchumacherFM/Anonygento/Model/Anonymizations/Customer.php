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

        $cu = $this->_randomCustomerModel->getCustomer();

        Zend_Debug::dump($cu);
        exit;

        $i = 0;
        foreach ($customerCollection as $customer) {
            $this->_anonymizeCustomer($customer);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

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

        $collection->getSelect()->where('anonymized=0');

        return $collection;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomer($customer)
    {


//        Zend_Debug::dump($this->_whatsMyName());
//        Zend_Debug::dump($mapping);
//        exit;

        usleep(800);
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

}