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
     * @param Mage_Sales_Model_Order       $order
     * @param Varien_Object                $customer
     */
    public function anonymizeByOrder(Mage_Sales_Model_Order $order, Varien_Object $customer = null)
    {
        $address = $this->_getRandomCustomer()->getCustomer();
        if ($customer !== null) {

            $addressCollection = $customer->getAddressesCollection();

            if ($addressCollection) {
                $address = $addressCollection->getFirstItem();
            } else {
                $address = $customer;
            }
            $addressCollection = null;

            $this->_mergeMissingAttributes($customer, $address);

        }

        $orderAddressCollection = $order->getAddressesCollection();
        /* @var $orderAddressCollection Mage_Sales_Model_Resource_Order_Address_Collection */

//        if ($order->getId() == 1562) {
//            Zend_Debug::dump($orderAddressCollection->load());
//            exit;
//        }
        // @todo possible bug that the company field will not be anonymized
        foreach ($orderAddressCollection as $orderAddress) {

//            if ($orderAddress->getid() == 3124) {
//                Zend_Debug::dump($orderAddress->getData());
//            }

            $this->_copyObjectData($address, $orderAddress);

//            if ($orderAddress->getid() == 3124) {
//                Zend_Debug::dump($orderAddress->getData());
//                exit;
//            }
            $orderAddress->getResource()->save($orderAddress);
        }
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