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

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeOrderPayment');
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $orderPayment
     * @param Mage_Customer_Model_Customer   $customer
     */
    protected function _anonymizeOrderPayment(Mage_Sales_Model_Order_Payment $orderPayment, Mage_Customer_Model_Customer $customer = null)
    {
        $randomPayment = Mage::getSingleton('schumacherfm_anonygento/random_payment')->getPayment($customer);
        $this->_copyObjectData($randomPayment, $orderPayment);
        $orderPayment->getResource()->save($orderPayment);
        $orderPayment = null;
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
        $paymentCollection = null;

    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Payment_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('sales/order_payment');
    }

}