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
        $randomCustomer = $this->_getInstance('schumacherfm_anonygento/random_customer')->getCustomer();

        die('go on here 8-)');

        $this->_copyObjectData($randomCustomer, $order,
            SchumacherFM_Anonygento_Model_Random_Mappings::getOrder());

        $addresses = $order->getAddressesCollection();

    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     *
     * @return integer
     */
    public function anonymizeOrderByCustomer(Mage_Customer_Model_Customer $customer)
    {

        $orderCollection     = $this->_getCollection()->addAttributeToFilter('customer_id', array('eq' => $customer->getId()));
        $orderCollectionSize = $orderCollection->getSize();

        if ($orderCollectionSize == 0) {
            return $orderCollectionSize;
        }

        foreach ($orderCollection as $order) {

            $this->_copyObjectData($customer, $order,
                SchumacherFM_Anonygento_Model_Random_Mappings::getOrder());

            $this->_anonymizeOrderAddress($customer, $order);
//            $this->_anonymizeQuote($order);

            $order->getResource()->save($order);
        }

        return $orderCollectionSize;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param Mage_Sales_Model_Order       $order
     */
    protected function _anonymizeOrderAddress(Mage_Customer_Model_Customer $customer, Mage_Sales_Model_Order $order)
    {
        $this->_getInstance('schumacherfm_anonygento/anonymizations_orderAddress')->anonymizeByOrder($customer, $order);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    protected function _anonymizeQuote(Mage_Sales_Model_Order $order)
    {
//        $this->_getInstance('schumacherfm_anonygento/anonymizations_quote')->anonymizeByOrder($order);
    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('sales/order')
            ->getCollection()
            ->addAttributeToSelect('entity_id');

        /* @var $collection Mage_Sales_Model_Resource_Order_Collection */

        $orderFields = SchumacherFM_Anonygento_Model_Random_Mappings::getOrder();

        foreach ($orderFields as $field) {
            $collection->addAttributeToSelect($field);
        }

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}