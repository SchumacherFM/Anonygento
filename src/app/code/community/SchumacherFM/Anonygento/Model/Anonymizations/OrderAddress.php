<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_OrderAddress extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeOrderAddress');
    }

    /**
     * @param Mage_Sales_Model_Order_Address $orderAddress
     */
    protected function _anonymizeOrderAddress(Mage_Sales_Model_Order_Address $orderAddress)
    {
        $randomCustomer = $this->_getRandomCustomer()->getCustomer();
        $this->_copyObjectData($randomCustomer, $orderAddress);
        $orderAddress->getResource()->save($orderAddress);
        $orderAddress = null;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param Varien_Object          $customer
     */
    public function anonymizeByOrder(Mage_Sales_Model_Order $order, Varien_Object $customer)
    {
        $orderAddressCollection = $order->getAddressesCollection();
        /* @var $orderAddressCollection Mage_Sales_Model_Resource_Order_Address_Collection */
        $addressCollection = null;

        if ($customer instanceof Mage_Customer_Model_Address) {
            /** @var $customer Mage_Customer_Model_Address */
            /** @var $addressCollection Mage_Customer_Model_Resource_Address_Collection */
            $addressCollection = $customer->getResourceCollection();
            $addressCollection
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('parent_id', array('eq' => $customer->getId()));
        } elseif ($customer->getId() !== null) {
            $addressCollection = $customer->getAddressesCollection();
            echo '$addressCollection:' . PHP_EOL;
            Zend_Debug::dump(get_class($addressCollection));
            exit;
        }

        if ($addressCollection !== null && $orderAddressCollection->getSize() === $addressCollection->getSize()) {

            $i = 0;
            foreach ($addressCollection as $address) {
                // this could lead to the same email address for each order address
                $this->_mergeMissingAttributes($customer, $address);
                $j = 0;
                foreach ($orderAddressCollection as $orderAddress) {
                    // $address->getDefaultBilling() $orderAddress->getDefaultBilling

//                    Zend_Debug::dump($address->getData());
//                    Zend_Debug::dump($orderAddress->getData());
//                    exit;

                    if ($i === $j) { // copy only once!
                        $this->_copyObjectData($address, $orderAddress);
                        $orderAddress->getResource()->save($orderAddress);
                    }
                    $j++;
                }
                $i++;
            }
            $customer = $orderAddressCollection = $addressCollection = null;
            return TRUE;
        }

        // processing non guest checkouts
        $address = $this->_getRandomCustomer()->getCustomer();
        if ($customer !== null) {

            if ($addressCollection) {
                $address = $addressCollection->getFirstItem();
            } else {
                $address = $customer;
            }
            $addressCollection = null;
            $this->_mergeMissingAttributes($customer, $address);
        }

        foreach ($orderAddressCollection as $orderAddress) {
            $this->_copyObjectData($address, $orderAddress);
            $orderAddress->getResource()->save($orderAddress);
        }
        $orderAddress           = null;
        $address                = null;
        $orderAddressCollection = null;

    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('sales/order_address');
    }
}