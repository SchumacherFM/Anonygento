<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Order extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{
    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeOrder');
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     *
     * @throws Exception
     */
    protected function _anonymizeOrder(Mage_Sales_Model_Order $order)
    {
        /** @var $quote Mage_Sales_Model_Quote */
        $quote   = Mage::getModel('sales/quote')->loadByIdWithoutStore((int)$order->getQuoteId());
        $quoteId = (int)$quote->getId();

        if ($quoteId > 0) {
            $initCustomer = $quote;
        } else {
            // it is possible that the quote did not exists, so we load the customer or random if guest
            $customerId   = (int)$order->getCustomerId();
            $initCustomer = $customerId > 0 ? Mage::getModel('customer/customer')->load($customerId) : null;
        }
        $customer = $this->_getRandomCustomer()->setCurrentCustomer($initCustomer)->getCustomer();
        $this->_copyObjectData($customer, $order);

        $this->_anonymizeOrderAddresses($order);
        $this->_anonymizeOrderPayment($order, $customer);
        $this->_anonymizeOrderCreditmemo($order);
        $this->_anonymizeOrderInvoice($order);
        $this->_anonymizeOrderShipment($order);

        $order->getResource()->save($order);
        // update OrderGrid after order has been saved
        // @see Mage_Sales_Model_Resource_Order_Abstract
        $order->getResource()->updateGridRecords($order->getId());
        $initCustomer = $quote = $order = null;
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     */
    protected function _anonymizeOrderAddresses(Mage_Sales_Model_Order $order)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_orderAddress')->anonymizeByOrder($order);
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Varien_Object                $customer
     */
    protected function _anonymizeOrderPayment(Mage_Sales_Model_Order $order, Varien_Object $customer = null)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_orderPayment')->anonymizeByOrder($order, $customer);
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     */
    protected function _anonymizeOrderCreditmemo(Mage_Sales_Model_Order $order)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_creditmemo')->anonymizeByOrder($order);
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     */
    protected function _anonymizeOrderInvoice(Mage_Sales_Model_Order $order)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_invoice')->anonymizeByOrder($order);
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     */
    protected function _anonymizeOrderShipment(Mage_Sales_Model_Order $order)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_shipment')->anonymizeByOrder($order);
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('sales/order');
    }

}