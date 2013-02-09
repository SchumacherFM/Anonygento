<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_NewsletterSubscriber extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeNewsletter');
    }

    /**
     * @param Mage_Newsletter_Model_Subscriber $subscriber
     * @param Mage_Customer_Model_Customer     $customer null
     */
    protected function _anonymizeNewsletter(Mage_Newsletter_Model_Subscriber $subscriber, Mage_Customer_Model_Customer $customer = null)
    {
        $customer = $this->_getRandomCustomer()->setCurrentCustomer($customer)->getCustomer();
        $this->_copyObjectData($customer, $subscriber);
        $subscriber->getResource()->save($subscriber);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    public function anonymizeByCustomer(Mage_Customer_Model_Customer $customer)
    {
        $subscriber = Mage::getModel('newsletter/subscriber');
        /* @var $subscriber Mage_Newsletter_Model_Subscriber */
        $subscriber->loadByCustomer($customer);

        if ($subscriber->getId()) {
            $this->_anonymizeNewsletter($subscriber, $customer);
        }
        $subscriber = $customer = null;
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Mage_Newsletter_Model_Resource_Subscriber_Collection
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('newsletter/subscriber');
    }

}