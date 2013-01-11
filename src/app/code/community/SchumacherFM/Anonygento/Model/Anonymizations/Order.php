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

    public function run()
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

        if ($orderCollectionSize === 0) {
            return FALSE;
        }

        foreach ($orderCollection as $order) {
            $this->_anonymizeOrder($order, $customer);
        }
        $orderCollection = null;

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

        $this->_copyObjectData($customer, $order, $this->_getMappings('Order'));

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
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('sales/order', 'Order');
    }
}