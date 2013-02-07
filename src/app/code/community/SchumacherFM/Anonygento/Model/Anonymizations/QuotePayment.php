<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_QuotePayment extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeQuotePayment');
    }

    /**
     * @param Mage_Sales_Model_Quote_Payment $quotePayment
     * @param Varien_Object                  $customer
     */
    protected function _anonymizeQuotePayment(Mage_Sales_Model_Quote_Payment $quotePayment, Varien_Object $customer = null)
    {
        $randomPayment = Mage::getSingleton('schumacherfm_anonygento/random_payment')->getPayment($customer);
        $this->_copyObjectData($randomPayment, $quotePayment);
        $quotePayment->getResource()->save($quotePayment);
        $quotePayment = $randomPayment = null;
    }

    /**
     * @param Mage_Sales_Model_Quote       $quote
     * @param Varien_Object                $customer
     */
    public function anonymizeByQuote(Mage_Sales_Model_Quote $quote, Varien_Object $customer)
    {
        $paymentCollection = $quote->getPaymentsCollection();
        foreach ($paymentCollection as $payment) {
            $this->_anonymizeQuotePayment($payment, $customer);
        }
        $customer = $paymentCollection = null;
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('sales/quote_payment');
    }

}