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
            $this->_anonymizeQuote($quote);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    protected function _anonymizeQuote(Mage_Sales_Model_Quote $quote)
    {
        $randomCustomer = $this->_getRandomCustomer()->getCustomer();

        $this->_copyObjectData($randomCustomer, $quote,
            SchumacherFM_Anonygento_Model_Random_Mappings::getQuote());

        $addresses = $quote->getAddressesCollection();

        foreach ($addresses as $address) {
            /* @var $address Mage_Sales_Model_Quote_Address */
            $this->_copyObjectData($randomCustomer, $address,
                SchumacherFM_Anonygento_Model_Random_Mappings::getQuote());
            $address->getResource()->save($address);
        }

        $quote->getResource()->save($quote);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     *
     * @return integer
     */
    public function anonymizeQuoteByCustomer(Mage_Customer_Model_Customer $customer)
    {

        $quoteCollection     = $this->_getCollection()->addAttributeToFilter('customer_id', array('eq' => $customer->getId()));
        $quoteCollectionSize = $quoteCollection->getSize();

        if ($quoteCollectionSize == 0) {
            return $quoteCollectionSize;
        }

        foreach ($quoteCollection as $quote) {

            $this->_copyObjectData($customer, $quote,
                SchumacherFM_Anonygento_Model_Random_Mappings::getQuote());

            $this->_anonymizeQuoteAddress($customer, $quote);

            $quote->getResource()->save($quote);
        }

        return $quoteCollectionSize;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param Mage_Sales_Model_Quote       $quote
     */
    protected function _anonymizeQuoteAddress(Mage_Customer_Model_Customer $customer, Mage_Sales_Model_Quote $quote)
    {
        $this->_getInstance('schumacherfm_anonygento/anonymizations_quoteAddress')->anonymizeByQuote($customer, $quote);
    }

    /**
     * @return Mage_Sales_Model_Resource_Quote_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('sales/quote')
            ->getCollection()
        // @todo must be addFieldToSelect
            ->addAttributeToSelect('entity_id');

        /* @var $collection Mage_Sales_Model_Resource_Quote_Collection */

        $quoteFields = SchumacherFM_Anonygento_Model_Random_Mappings::getQuote();

        $this->_collectionAddAttributeToSelect($collection, $quoteFields);

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}