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
     * usually this wont run
     *
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeOrderAddress');
    }

    /**
     * @param Mage_Sales_Model_Order_Address $orderAddress
     * @param Varien_Object                  $address
     */
    protected function _anonymizeOrderAddress(Mage_Sales_Model_Order_Address $orderAddress, Varien_Object $address = null)
    {
        $randomCustomer = $address === null ? $this->_getRandomCustomer()->getCustomer() : $address;
        $this->_copyObjectData($randomCustomer, $orderAddress);
        $orderAddress->getResource()->save($orderAddress);
        $orderAddress = null;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    public function anonymizeByOrder(Mage_Sales_Model_Order $order)
    {
        // getaddress data from quote
        /** @var $quoteAddressCollection Mage_Sales_Model_Resource_Quote_Address_Collection */
        $quoteAddressCollection = Mage::getModel('sales/quote_address')->getCollection()->setQuoteFilter((int)$order->getQuoteId());

        if($quoteAddressCollection->count() > 0){
            Zend_Debug::dump(get_class($quoteAddressCollection));
            exit;
        }

        /* @var $orderAddressCollection Mage_Sales_Model_Resource_Order_Address_Collection */
        $orderAddressCollection = $order->getAddressesCollection();

        if($orderAddressCollection->count() > 0){
            Zend_Debug::dump(get_class($orderAddressCollection));
            exit;
        }


        /** @var $customer Mage_Customer_Model_Customer */
        $customer          = Mage::getModel('customer/customer')->load((int)$order->getCustomerId());
        $addressCollection = $customer->getAddressesCollection();

        // now check what if is set

        // customer is registered
        if ($customer !== null && $customer instanceof Mage_Customer_Model_Customer && $addressCollection->count() > 0) {
            // we have here customer adresses .. maybe ...
            $billingAddress  = $customer->getPrimaryBillingAddress();
            $shippingAddress = $customer->getPrimaryShippingAddress();

            foreach ($orderAddressCollection as $orderAddress) {
                if ($orderAddress->getAddressType() === Mage_Sales_Model_Order_Address::TYPE_BILLING) {
                    $this->_mergeMissingAttributes($customer, $billingAddress);
                    $this->_copyObjectData($billingAddress, $orderAddress);
                } elseif ($orderAddress->getAddressType() === Mage_Sales_Model_Order_Address::TYPE_SHIPPING) {
                    $this->_mergeMissingAttributes($customer, $shippingAddress);
                    $this->_copyObjectData($shippingAddress, $orderAddress);
                } else {
                    Mage::throwException('Missing orderAddress AddressType!: ' . var_export($orderAddress->getData(), 1));
                }
                $orderAddress->getResource()->save($orderAddress);
            }
            $orderAddress = $shippingAddress = $billingAddress = $orderAddressCollection = null;
            return TRUE;
        }

        // guest checkouts

//        Zend_Debug::dump(get_class($order));
//        Zend_Debug::dump($order->getData());
//        exit;

        // processing non guest checkouts
        $address = $this->_getRandomCustomer()->getCustomer();
        if ($customer !== null) {
            $this->_mergeMissingAttributes($customer, $address);
        }

        foreach ($orderAddressCollection as $orderAddress) {
            $this->_anonymizeOrderAddress($orderAddress, $address);
        }
        $orderAddress = $address = $orderAddressCollection = null;
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