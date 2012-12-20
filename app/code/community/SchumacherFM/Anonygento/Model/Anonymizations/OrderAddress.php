<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_OrderAddress extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    public function run()
    {

        $orderAddressCollection = $this->_getCollection();

        $i = 0;
        foreach ($orderAddressCollection as $orderAddress) {
            $this->_anonymizeOrderAddress($orderAddress);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();

    }

    /**
     * @param Mage_Sales_Model_Order_Address $orderAddress
     */
    protected function _anonymizeOrderAddress(Mage_Sales_Model_Order_Address $orderAddress)
    {

        $randomCustomer = $this->_getInstance('schumacherfm_anonygento/random_customer')->getCustomer();

        $this->_copyObjectData($randomCustomer, $orderAddress,
            SchumacherFM_Anonygento_Model_Random_Mappings::getOrderAddress());

        $orderAddress->save();
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param Mage_Sales_Model_Order       $order
     */
    public function anonymizeByOrder(Mage_Customer_Model_Customer $customer, Mage_Sales_Model_Order $order)
    {

        $orderAddressCollection = $order->getAddressesCollection();
        /* @var $orderAddressCollection Mage_Sales_Model_Resource_Order_Address_Collection */

        foreach ($orderAddressCollection as $orderAddress) {

            $this->_copyObjectData($customer, $orderAddress,
                SchumacherFM_Anonygento_Model_Random_Mappings::getOrderAddress());

            $orderAddress->getResource()->save($orderAddress);
        }

    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Address_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('sales/order_address')
            ->getCollection()
            ->addAttributeToSelect('entity_id');

        /* @var $collection Mage_Sales_Model_Resource_Order_Collection */

        $orderFields = SchumacherFM_Anonygento_Model_Random_Mappings::getOrderAddress();

        foreach ($orderFields as $field) {
            $collection->addAttributeToSelect($field);
        }

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}