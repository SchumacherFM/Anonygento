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

    public function run()
    {
        parent::run($this->_getCollection(), '_anonymizeQuoteAddress');
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $quoteAddress
     */
    protected function _anonymizeQuoteAddress(Mage_Sales_Model_Quote_Address $quoteAddress)
    {
        $randomCustomer = $this->_getRandomCustomer()->getCustomer();
        $this->_copyObjectData($randomCustomer, $quoteAddress, $this->_getMappings('quoteAddress'));
        $quoteAddress->getResource()->save($quoteAddress);
//        $quoteAddress->save();
        $quoteAddress = null;
    }

    /**
     * @param Mage_Sales_Model_Quote       $quote
     * @param Mage_Customer_Model_Customer $customer
     */
    public function anonymizeByQuote(Mage_Sales_Model_Quote $quote, Mage_Customer_Model_Customer $customer = null)
    {
        $address = $this->_getRandomCustomer()->getCustomer();
        if ($customer !== null) {

            $addressCollection = $customer->getAddressesCollection();

            if ($addressCollection) {
                $address = $addressCollection->getFirstItem();
            } else {
                $address = $customer;
            }
            $addressCollection = null;

            $this->_mergeMissingAttributes($customer, $address, 'quoteAddress');

        }

        $quoteAddressCollection = $quote->getAddressesCollection();
        /* @var $quoteAddressCollection Mage_Sales_Model_Resource_Quote_Address_Collection */

        foreach ($quoteAddressCollection as $quoteAddress) {
            $this->_copyObjectData($address, $quoteAddress, $this->_getMappings('quoteAddress'));
            $quoteAddress->getResource()->save($quoteAddress);
//            $quoteAddress->save();
            $quoteAddress = null;
        }
        $quoteAddressCollection = null;

    }

    /**
     * @return Mage_Sales_Model_Resource_Quote_Address_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('sales/quote_address', 'quoteAddress');
    }
}