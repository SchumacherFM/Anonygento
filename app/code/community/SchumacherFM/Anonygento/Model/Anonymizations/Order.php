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

        $orderCollection = $this->_getCollection();

        $i = 0;
        foreach ($orderCollection as $order) {
            $this->_anonymizeOrder($order);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    protected function _anonymizeOrder(Mage_Sales_Model_Order $order)
    {
        if ($order->getCustomerId()) {
            $customer = $order->getCustomer();
            if (!$customer) {
                throw new Exception('Customer is null in _anonymizeOrder');
            }
        } else {
            $customer = $this->_getRandomCustomer()->getCustomer();
        }

        $this->_copyObjectData($customer, $order, $this->_getMappings('Order'));

        $this->_anonymizeOrderAddresses($order);

        $order->getResource()->save($order);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     *
     * @return integer
     */
    public function anonymizeByCustomer(Mage_Customer_Model_Customer $customer)
    {

        $orderCollection = $this->_getCollection()
            ->addAttributeToFilter('customer_id', array('eq' => $customer->getId()));

        $orderCollectionSize = $orderCollection->getSize();

        if ($orderCollectionSize == 0) {
            return 0;
        }

        foreach ($orderCollection as $order) {

            $this->_copyObjectData($customer, $order, $this->_getMappings('Order'));

            $this->_anonymizeOrderAddresses($order, $customer);
            $this->_anonymizeQuote($order, $customer);

            $order->getResource()->save($order);
        }

        return $orderCollectionSize;
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeOrderAddresses(Mage_Sales_Model_Order $order, Mage_Customer_Model_Customer $customer = null)
    {
        $this->_getInstance('schumacherfm_anonygento/anonymizations_orderAddress')->anonymizeByOrder($order, $customer);
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeQuote(Mage_Sales_Model_Order $order, Mage_Customer_Model_Customer $customer)
    {
        $this->_getInstance('schumacherfm_anonygento/anonymizations_quote')->anonymizeByOrder($order, $customer);
    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('sales/order')
            ->getCollection();
        /* @var $collection Mage_Sales_Model_Resource_Order_Collection */

        $this->_collectionAddAttributeToSelect($collection,
            $this->_getMappings('Order')
        );

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}