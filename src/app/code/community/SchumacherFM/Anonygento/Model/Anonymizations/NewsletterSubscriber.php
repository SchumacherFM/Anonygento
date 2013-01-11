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

    public function run()
    {
        $newsLetterSubscriberCollection = $this->_getCollection();

        $i = 0;
        foreach ($newsLetterSubscriberCollection as $subscriber) {
            $this->_anonymizeNewsletter($subscriber);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $newsLetterSubscriberCollection = null;
        $this->getProgressBar()->finish();
    }

    /**
     * @param Mage_Newsletter_Model_Subscriber $subscriber
     */
    protected function _anonymizeNewsletter(Mage_Newsletter_Model_Subscriber $subscriber)
    {

        $customer = $this->_getRandomCustomer()->getCustomer();

        $this->_copyObjectData($customer, $subscriber, $this->_getMappings('NewsletterSubscriber'));

        $subscriber->save();

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
            $this->_copyObjectData($customer, $subscriber, $this->_getMappings('NewsletterSubscriber'));
            $subscriber->save();
        }
        $subscriber = null;
    }

    /**
     * @return Mage_Newsletter_Model_Resource_Subscriber_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('newsletter/subscriber', 'NewsletterSubscriber');
    }

}