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

        $quoteAddressCollection = $this->_getCollection();

        $i = 0;
        foreach ($quoteAddressCollection as $quoteAddress) {
            $this->_anonymizeQuoteAddress($quoteAddress);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    /**
     * @param Mage_Sales_Model_Quote_Address $quoteAddress
     */
    protected function _anonymizeQuoteAddress(Mage_Sales_Model_Quote_Address $quoteAddress)
    {

        $randomCustomer = $this->_getRandomCustomer()->getCustomer();

        $this->_copyObjectData($randomCustomer, $quoteAddress,
            SchumacherFM_Anonygento_Model_Random_Mappings::getQuoteAddress());

        $quoteAddress->getResource()->save($quoteAddress);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param Mage_Sales_Model_Quote       $quote
     */
    public function anonymizeByQuote(Mage_Customer_Model_Customer $customer, Mage_Sales_Model_Quote $quote)
    {

        $quoteAddressCollection = $quote->getAddressesCollection();
        /* @var $quoteAddressCollection Mage_Sales_Model_Resource_Quote_Address_Collection */

        foreach ($quoteAddressCollection as $quoteAddress) {

            $this->_copyObjectData($customer, $quoteAddress,
                SchumacherFM_Anonygento_Model_Random_Mappings::getQuoteAddress());

            $quoteAddress->getResource()->save($quoteAddress);
        }

    }

    /**
     * @return Mage_Sales_Model_Resource_Quote_Address_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('sales/quote_address')
            ->getCollection()
            ->addAttributeToSelect('entity_id');

        /* @var $collection Mage_Sales_Model_Resource_Quote_Collection */

        $quoteFields = SchumacherFM_Anonygento_Model_Random_Mappings::getQuoteAddress();

        $this->_collectionAddAttributeToSelect($collection, $quoteFields);

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}