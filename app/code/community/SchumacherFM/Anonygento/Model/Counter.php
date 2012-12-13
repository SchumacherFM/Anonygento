<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Counter extends Varien_Object
{

    /**
     * @param Varien_Data_Collection $model
     * @return integer
     */
    protected function _counter($model)
    {
        /**
         * don't use count(), otherwise it loads the whole collection
         * and counts the items
         * only getSize will perform a 'select count(*)...' query
         */
        return Mage::getModel($model)->getCollection()->getSize();
    }

    /**
     * @return integer
     */
    public function countCustomer()
    {
        return $this->_counter('customer/customer');
    }

    public function countCustomerAddress()
    {
        return $this->_counter('customer/address');
    }

    public function countOrder()
    {
        return $this->_counter('sales/order');
    }

    public function countOrderAddress()
    {
        return $this->_counter('sales/order_address');
    }

    public function countQuote()
    {
        return $this->_counter('sales/quote');
    }

    public function countQuoteAddress()
    {
        return $this->_counter('sales/quote_address');
    }

    public function countNewsletterSubscribers()
    {
        return $this->_counter('newsletter/subscriber');
    }

}