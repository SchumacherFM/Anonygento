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

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeByAddress');
    }

    /**
     * @param Mage_Customer_Model_Address $address
     */
    protected function _anonymizeByAddress(Mage_Customer_Model_Address $address)
    {
        $customer = $this->_getRandomCustomer()->getCustomer();
        $this->_copyObjectData($customer, $address);
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

        $size = (int)$addressCollection->getSize();

        if ($size === 1) {
            $address = $addressCollection->getFirstItem();
            $this->_anonymizeByAddress($address);
            $address = null;
        } elseif ($size > 1) {
            foreach ($addressCollection as $address) {
                $this->_anonymizeByAddress($address);
                $address = null;
            }
        }
        $addressCollection = null;

    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('customer/address');
    }
}