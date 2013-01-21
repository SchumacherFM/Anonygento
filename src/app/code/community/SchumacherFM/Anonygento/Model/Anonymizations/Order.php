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
     * @param Mage_Customer_Model_Customer $customer
     *
     * @return boolean
     */
    public function anonymizeByCustomer(Mage_Customer_Model_Customer $customer)
    {

        $orderCollection = $this->_getCollection()
            ->addAttributeToFilter('customer_id', array('eq' => $customer->getId()));

        $orderCollectionSize = (int)$orderCollection->getSize();

        if ($orderCollectionSize > 0) {
            foreach ($orderCollection as $order) {
                $this->_anonymizeOrder($order, $customer);
                $order = null;
            }
        }
        $customer = $orderCollection = null;

    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Mage_Customer_Model_Customer $customer
     *
     * @throws Exception
     */
    protected function _anonymizeOrder(Mage_Sales_Model_Order $order, Mage_Customer_Model_Customer $customer = null)
    {
        if ($order->getCustomerId() && !$customer) {
            $customer = Mage::getModel('customer/customer')->load((int)$order->getCustomerId());
            if (!$customer) {
                throw new Exception('Cant find the customer, please contact the developer!');
            }
        } elseif (!$customer) {
            $customer = $this->_getRandomCustomer()->getCustomer();
        }

        $this->_copyObjectData($customer, $order, $this->_getMappings());

        // this could be buggy because we need from the customer the billing and/or shipping address
        $this->_anonymizeOrderAddresses($order, $customer);
        $this->_anonymizeOrderPayment($order, $customer);
        $this->_anonymizeOrderCreditmemo($order);
        $this->_anonymizeOrderInvoice($order);
        $this->_anonymizeOrderShipment($order);
        $this->_anonymizeQuote($order, $customer);

        $order->getResource()->save($order);
        // update OrderGrid after order has been saved
        // @see Mage_Sales_Model_Resource_Order_Abstract
        $order->getResource()->updateGridRecords($order->getId());
        $order = null;
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeOrderAddresses(Mage_Sales_Model_Order $order, Mage_Customer_Model_Customer $customer = null)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_orderAddress')->anonymizeByOrder($order, $customer);
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeOrderPayment(Mage_Sales_Model_Order $order, Mage_Customer_Model_Customer $customer = null)
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
     * @param Mage_Sales_Model_Order       $order
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeQuote(Mage_Sales_Model_Order $order, Mage_Customer_Model_Customer $customer)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_quote')->anonymizeByOrder($order, $customer);
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