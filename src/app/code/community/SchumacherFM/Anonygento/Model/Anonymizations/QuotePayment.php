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

    public function run()
    {
        parent::run($this->_getCollection(), '_anonymizeQuotePayment');
    }

    /**
     * @param Mage_Sales_Model_Quote_Payment $quotePayment
     * @param Mage_Customer_Model_Customer   $customer
     */
    protected function _anonymizeQuotePayment(Mage_Sales_Model_Quote_Payment $quotePayment, Mage_Customer_Model_Customer $customer = null)
    {
        $randomPayment = Mage::getSingleton('schumacherfm_anonygento/random_payment')->getPayment($customer);
        $this->_copyObjectData($randomPayment, $quotePayment, $this->_getMappings('quotePayment'));
        $quotePayment->getResource()->save($quotePayment);
        $quotePayment = null;
    }

    /**
     * @param Mage_Sales_Model_Quote       $quote
     * @param Mage_Customer_Model_Customer $customer
     */
    public function anonymizeByQuote(Mage_Sales_Model_Quote $quote, Mage_Customer_Model_Customer $customer = null)
    {
        $paymentCollection = $quote->getPaymentsCollection();
        foreach ($paymentCollection as $payment) {
            $this->_anonymizeQuotePayment($payment, $customer);
        }
        $paymentCollection = null;
    }

    /**
     * @return Mage_Sales_Model_Resource_Quote_Payment_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('sales/quote_payment', 'quotePayment');
    }

}