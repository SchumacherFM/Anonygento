<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_OrderPayment extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    public function run()
    {
        $collection = $this->_getCollection();

        $i = 0;
        foreach ($collection as $orderPayment) {
            $this->_anonymizeOrderPayment($orderPayment);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $orderPayment
     * @param Mage_Customer_Model_Customer   $customer
     */
    protected function _anonymizeOrderPayment(Mage_Sales_Model_Order_Payment $orderPayment, Mage_Customer_Model_Customer $customer = null)
    {

        $randomPayment = $this->_getInstance('schumacherfm_anonygento/random_payment')->getPayment($customer);

        $this->_copyObjectData($randomPayment, $orderPayment, $this->_getMappings('OrderPayment'));

//        Zend_Debug::dump($orderPayment->getData());
//        exit;

        $orderPayment->getResource()->save($orderPayment);

    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Mage_Customer_Model_Customer $customer
     */
    public function anonymizeByOrder(Mage_Sales_Model_Order $order, Mage_Customer_Model_Customer $customer = null)
    {

        $paymentCollection = $order->getPaymentsCollection();

        foreach ($paymentCollection as $payment) {
            $this->_anonymizeOrderPayment($payment, $customer);
        }

    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Payment_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('sales/order_payment')
            ->getCollection()
            ->addFieldToSelect($this->_getMappings('OrderPayment')->getEntityAttributes());
        /* @var $collection Mage_Sales_Model_Resource_Order_Payment_Collection */

        $this->_collectionAddStaticAnonymized($collection, 0);

        return $collection;
    }

}