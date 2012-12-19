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
     * @var SchumacherFM_Anonygento_Model_Anonymizations_NewsletterSubscriber
     */
    protected $_anonNlSubscriberModel = null;

    protected function _construct()
    {
        $this->_anonNlSubscriberModel = Mage::getModel('schumacherfm_anonygento/anonymizations_newsletterSubscriber');
    }

    /**
     * anonymizes customer and related customer addresses
     */

    public function run()
    {
        $customerCollection = $this->_getCustomerCollection(array('prefix', 'email', 'firstname', 'lastname', 'suffix'));

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
        $this->_anonymizeCustomerAddresses($customer);
        $this->_anonymizeCustomerNewsletter($customer);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomerNewsletter($customer)
    {
        $this->_anonNlSubscriberModel->anonymizeNewsletterByCustomer($customer);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomerAddresses($customer)
    {
        $addressCollection = $customer->getAddressesCollection();
        /* @var $addressCollection Mage_Customer_Model_Resource_Address_Collection */
        $this->_collectionAddStaticAnonymized($addressCollection);

        $size           = (int)$addressCollection->getSize();
        $addressMapping = SchumacherFM_Anonygento_Model_Random_Mappings::getCustomerAddress();

        if ($size === 1) {
            $address = $addressCollection->getFirstItem();
            $this->_copyObjectData($customer, $address, $addressMapping);
            $address->save();
        } elseif ($size > 1) {
            // @todo remove bug: now every address has the same data.
            foreach ($addressCollection as $address) {
                $this->_copyObjectData($customer, $address, $addressMapping);
                $address->save();
            }
        }

    }

}