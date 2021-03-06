<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_QuoteAddress extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * usually this won't run. if so you have errors in your data relationships
     *
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeQuoteAddress');
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $quoteAddress
     * @param Varien_Object                  $address
     */
    protected function _anonymizeQuoteAddress(Mage_Sales_Model_Quote_Address $quoteAddress, Varien_Object $address = null)
    {
        $random = $this->_getRandomCustomer()->setCurrentCustomer($address)->getCustomer();
        $this->_copyObjectData($random, $quoteAddress);
        $quoteAddress->getResource()->save($quoteAddress);
        $random = $quoteAddress = null;
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @param Varien_Object          $customer
     *
     * @return null
     */
    public function anonymizeByQuote(Mage_Sales_Model_Quote $quote, Varien_Object $customer)
    {
        /* @var $quoteAddressCollection Mage_Sales_Model_Resource_Quote_Address_Collection */
        $quoteAddressCollection = $quote->getAddressesCollection();
        $hasAccount             = ($customer instanceof Mage_Customer_Model_Customer);

        if (!$hasAccount) {
            // guest checkout
            foreach ($quoteAddressCollection as $quoteAddress) {
                $this->_anonymizeQuoteAddress($quoteAddress);
            }
            $quoteAddress = $quoteAddressCollection = null;
            return null;
        }

        // customer is registered
        $previousAddress = null; // check if shipping !== billing
        foreach ($quoteAddressCollection as $quoteAddress) {

            $address = $this->_compareVarienObjects($previousAddress,$quoteAddress)
                ? $previousAddress
                : $this->_getAddressByType($customer, $quoteAddress->getAddressType());

            $this->_anonymizeQuoteAddress($quoteAddress, $address);
            $previousAddress = clone $quoteAddress;
        }
        $previousAddress = $quoteAddress = $address = $quoteAddressCollection = null;
        return null;
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('sales/quote_address');
    }
}