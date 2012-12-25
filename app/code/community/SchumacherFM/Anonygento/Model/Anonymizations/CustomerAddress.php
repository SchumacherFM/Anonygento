<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_CustomerAddress extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    public function run()
    {
        $customerAddressCollection = $this->_getCollection();

        $i = 0;
        foreach ($customerAddressCollection as $address) {
            $this->_anonymizeByAddress($address);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    /**
     * @param Mage_Customer_Model_Address $address
     */
    protected function _anonymizeByAddress(Mage_Customer_Model_Address $address)
    {
        $customer       = $this->_getRandomCustomer()->getCustomer();
        $addressMapping = $this->_getMappings('CustomerAddress');
        $this->_copyObjectData($customer, $address, $addressMapping);
        $address->getResource()->save($address);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    public function anonymizeByCustomer(Mage_Customer_Model_Customer $customer)
    {
        $addressCollection = $customer->getAddressesCollection();
        /* @var $addressCollection Mage_Customer_Model_Resource_Address_Collection */
        $this->_collectionAddStaticAnonymized($addressCollection);

        $size           = (int)$addressCollection->getSize();
        $addressMapping = $this->_getMappings('CustomerAddress');

        if ($size === 1) {
            $address = $addressCollection->getFirstItem();
            $this->_copyObjectData($customer, $address, $addressMapping);
            $address->save();
        } elseif ($size > 1) {

            $i = 0;
            foreach ($addressCollection as $address) {

                $randomCustomer = $i === 0
                    ? $customer
                    : $this->_getRandomCustomer();

                $this->_copyObjectData($randomCustomer, $address, $addressMapping);
                $address->getResource()->save($address);
                $i++;
            }
        }

    }

    /**
     * @return Mage_Customer_Model_Resource_Address_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('customer/address')
            ->getCollection()
            ->addAttributeToSelect($this->_getMappings('CustomerAddress')->getEntityAttributes());
        /* @var $collection Mage_Customer_Model_Resource_Address_Collection */

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}