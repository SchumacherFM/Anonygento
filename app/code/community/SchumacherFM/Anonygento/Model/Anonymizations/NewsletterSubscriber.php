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

    protected function _construct()
    {
        parent::_construct();

    }

    public function run()
    {
        $newsLetterSubscriberCollection = $this->_getCollection();

        $i = 0;
        foreach ($newsLetterSubscriberCollection as $subscriber) {
            $this->_anonymizeNewsletter($subscriber);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    /**
     * @param Mage_Newsletter_Model_Subscriber $subscriber
     */
    protected function _anonymizeNewsletter($subscriber)
    {

        $customer = $this->_randomCustomerModel->getCustomer();

        $this->_copyObjectData($customer, $subscriber,
            SchumacherFM_Anonygento_Model_Random_Mappings::getNewsletterSubscriber());

        $subscriber->save();

    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    public function anonymizeNewsletterByCustomer($customer)
    {
        $subscriber = Mage::getModel('newsletter/subscriber');
        /* @var $subscriber Mage_Newsletter_Model_Subscriber */
        $subscriber->loadByCustomer($customer);

        $this->_copyObjectData($customer, $subscriber,
            SchumacherFM_Anonygento_Model_Random_Mappings::getNewsletterSubscriber());

        $subscriber->save();

    }

    /**
     * @return Mage_Newsletter_Model_Resource_Subscriber_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('newsletter/subscriber')
            ->getCollection()
            ->addAttributeToSelect(array('subscriber_email'));
        /* @var $collection Mage_Newsletter_Model_Resource_Subscriber_Collection */

        $this->_collectionAddStaticAnonymized($collection, 0);

        return $collection;
    }

}