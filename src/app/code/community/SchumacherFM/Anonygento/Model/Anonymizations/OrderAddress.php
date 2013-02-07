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
        $randomCustomer = $this->_getRandomCustomer()->setCurrentCustomer($address)->getCustomer();
        $this->_copyObjectData($randomCustomer, $orderAddress);
        $orderAddress->getResource()->save($orderAddress);
        $orderAddress = null;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     *
     * @return null
     */
    public function anonymizeByOrder(Mage_Sales_Model_Order $order)
    {
        // getaddress data from quote
        /** @var $quoteAddressCollection Mage_Sales_Model_Resource_Quote_Address_Collection */
        $quoteAddressCollection = Mage::getModel('sales/quote_address')->getCollection()->setQuoteFilter((int)$order->getQuoteId());

        /* @var $orderAddressCollection Mage_Sales_Model_Resource_Order_Address_Collection */
        $orderAddressCollection = $order->getAddressesCollection();

        // if a quote is equal to the order
        if ((int)$quoteAddressCollection->count() === (int)$orderAddressCollection->count()) {
            foreach ($quoteAddressCollection as $quoteAddress) {
                /** @var $quoteAddress Mage_Sales_Model_Quote_Address */
                foreach ($orderAddressCollection as $orderAddress) {
                    /** @var $orderAddress Mage_Sales_Model_Order_Address */
                    if ($quoteAddress->getAddressType() === $orderAddress->getAddressType()) {
                        $this->_anonymizeOrderAddress($orderAddress, $quoteAddress);
                    }
                }
            }
            $quoteAddressCollection = $orderAddressCollection = $quoteAddress = $orderAddress = null;
            return null;
        }

        /** @var $customer Mage_Customer_Model_Customer */
        $customer          = Mage::getModel('customer/customer')->load((int)$order->getCustomerId());
        $addressCollection = $customer->getAddressesCollection();
        // quote has been deleted and customer is NOT a guest
        if ((int)$addressCollection->count() >= (int)$orderAddressCollection->count()) {
            foreach ($orderAddressCollection as $orderAddress) {
                $address = $this->_getAddressByType($customer, $orderAddress->getAddressType());
                $this->_anonymizeOrderAddress($orderAddress, $address);
            }
            $quoteAddressCollection = $orderAddressCollection = $addressCollection = $customer = $address = $orderAddress = null;
            return null;
        }

        // guest checkout with deleted quote and also if quoteAddress count is <> than orderAddress
        foreach ($orderAddressCollection as $orderAddress) {
            /** @var $orderAddress Mage_Sales_Model_Order_Address */
            $this->_anonymizeOrderAddress($orderAddress);
        }
        $quoteAddressCollection = $orderAddressCollection = $addressCollection = $customer = $orderAddress = null;
        return null;
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