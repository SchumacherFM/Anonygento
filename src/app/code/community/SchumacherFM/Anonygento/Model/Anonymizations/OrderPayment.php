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
     * @param Varien_Object                  $customer
     */
    protected function _anonymizeOrderPayment(Mage_Sales_Model_Order_Payment $orderPayment, Varien_Object $customer = null)
    {
        $randomPayment = Mage::getSingleton('schumacherfm_anonygento/random_payment')->getPayment($customer);
        $this->_copyObjectData($randomPayment, $orderPayment);
        $orderPayment->getResource()->save($orderPayment);
        $orderPayment = null;
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Varien_Object                $customer
     */
    public function anonymizeByOrder(Mage_Sales_Model_Order $order, Varien_Object $customer = null)
    {
        $paymentCollection = $order->getPaymentsCollection();

        foreach ($paymentCollection as $payment) {
            $this->_anonymizeOrderPayment($payment, $customer);
        }
        $paymentCollection = null;

    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('sales/order_payment');
    }

}