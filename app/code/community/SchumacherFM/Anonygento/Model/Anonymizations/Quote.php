<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Quote extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    public function run()
    {

        $quoteCollection = $this->_getCollection();

        $i = 0;
        foreach ($quoteCollection as $quote) {
//            $this->_anonymizeCustomerAddress($address);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    public function anonymizeByOrder(Mage_Sales_Model_Order $order)
    {

        /* @var $quote Mage_Sales_Model_Quote */
//        $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
//        if ($quote->getId()) {
//            $this->_anonymizeQuote($quote, $randomData);
//        }

//        $orderCollection     = $this->_getCollection()->addAttributeToFilter('customer_id', array('eq' => $customer->getId()));
//        $orderCollectionSize = $orderCollection->getSize();
//
//        if ($orderCollectionSize == 0) {
//            return $orderCollectionSize;
//        }
//
//        foreach ($orderCollection as $order) {
//
//            $this->_copyObjectData($customer, $order,
//                SchumacherFM_Anonygento_Model_Random_Mappings::getOrder());
//
//
//            $order->getResource()->save($order);
//        }
//
//        return $orderCollectionSize;
    }



    /**
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('sales/quote')
            ->getCollection()
            ->addAttributeToSelect('entity_id');

        /* @var $collection Mage_Sales_Model_Resource_Order_Collection */

        $orderFields = SchumacherFM_Anonygento_Model_Random_Mappings::getQuote();

        foreach ($orderFields as $field) {
            $collection->addAttributeToSelect($field);
        }

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}