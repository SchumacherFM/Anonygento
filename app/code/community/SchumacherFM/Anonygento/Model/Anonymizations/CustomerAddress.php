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
            $this->_anonymizeCustomerAddress($address);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    /**
     * @param Mage_Customer_Model_Address $address
     */
    protected function _anonymizeCustomerAddress(Mage_Customer_Model_Address $address)
    {
        $customer       = $this->_randomCustomerModel->getCustomer();
        $addressMapping = SchumacherFM_Anonygento_Model_Random_Mappings::getCustomerAddress();
        $this->_copyObjectData($customer, $address, $addressMapping);
        $address->save();
    }

    /**
     * @return Mage_Customer_Model_Resource_Address_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('customer/address')
            ->getCollection()
            ->addAttributeToSelect(array_values(SchumacherFM_Anonygento_Model_Random_Mappings::getCustomerAddress()));
        /* @var $collection Mage_Customer_Model_Resource_Address_Collection */

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}