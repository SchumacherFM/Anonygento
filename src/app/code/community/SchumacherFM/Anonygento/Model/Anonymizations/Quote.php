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

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeQuote');
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     *
     * @throws Exception
     */
    protected function _anonymizeQuote(Mage_Sales_Model_Quote $quote)
    {

        $customerId = (int)$quote->getCustomerId();

        if ($customerId > 0) {
            $customer = $quote->getCustomer();
            /* getCustomer does not always return a customer model */
            $customerId = $customer !== null ? (int)$customer->getId() : 0;
        }

        if ($customerId === 0) {
            $customer = $this->_getRandomCustomer()->getCustomer();
        }

        $this->_copyObjectData($customer, $quote);
        $this->_anonymizeQuoteAddresses($quote, $customer);
        $this->_anonymizeQuotePayment($quote, $customer);

        $quote->getResource()->save($quote);
        $customer = $quote = null;
    }

    /**
     * @param Mage_Sales_Model_Order       $order
     * @param Varien_Object                $customer
     *
     * @return boolean
     */
//    public function anonymizeByOrder(Mage_Sales_Model_Order $order, Varien_Object $customer)
//    {
//        if (!$order->getQuoteId()) {
//            Mage::throwException('Missing QuoteId in Order Model!');
//        }
//
//        $quoteCollection = $this->_getCollection()
//            ->addFieldToFilter('entity_id', array('eq' => (int)$order->getQuoteId()));
//        /** @var $quoteCollection Mage_Sales_Model_Resource_Quote_Collection */
//
//        foreach ($quoteCollection as $quote) {
//            /** @var $quote Mage_Sales_Model_Quote */
//            $this->_anonymizeQuote($quote, $customer);
//        }
//        $customer = $quoteCollection = null;
//    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @param Varien_Object          $customer
     */
    protected function _anonymizeQuoteAddresses(Mage_Sales_Model_Quote $quote, Varien_Object $customer)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_quoteAddress')->anonymizeByQuote($quote, $customer);
    }

    /**
     * @param Mage_Sales_Model_Quote       $quote
     * @param Varien_Object                $customer
     */
    protected function _anonymizeQuotePayment(Mage_Sales_Model_Quote $quote, Varien_Object $customer = null)
    {
        Mage::getSingleton('schumacherfm_anonygento/anonymizations_quotePayment')->anonymizeByQuote($quote, $customer);
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('sales/quote');
    }
}