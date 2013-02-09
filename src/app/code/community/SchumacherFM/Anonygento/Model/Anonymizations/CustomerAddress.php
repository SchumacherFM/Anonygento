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
    }

    /**
     * @param Mage_Customer_Model_Address  $address
     * @param Mage_Customer_Model_Customer $customer null
     */
    protected function _anonymizeByAddress(Mage_Customer_Model_Address $address, Mage_Customer_Model_Customer $customer = null)
    {
        $randomCustomer = $this->_getRandomCustomer()->setCurrentCustomer($customer)->getCustomer();
        $this->_copyObjectData($randomCustomer, $address);
        $address->getResource()->save($address);
        $randomCustomer = $address = null;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     *
     * @return bool
     */
    public function anonymizeByCustomer(Mage_Customer_Model_Customer $customer)
    {
        $addressCollection = $customer->getAddressesCollection();
        /* @var $addressCollection Mage_Customer_Model_Resource_Address_Collection */
        $this->_collectionAddStaticAnonymized($addressCollection);

        $size = (int)$addressCollection->getSize();

        if ($size < 1) {
            $addressCollection = null;
            return FALSE;
        }

        if ($size === 1) {
            $address = $addressCollection->getFirstItem();
            $this->_anonymizeByAddress($address, $customer);
        } elseif ($size > 1) {
            $i = 0;
            foreach ($addressCollection as $address) {
                $this->_anonymizeByAddress($address, ($i === 0 ? $customer : null));
                $i++;
            }
        }
        $address = $addressCollection = null;
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return void
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
    }
}