<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_OrderGrid extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * normally this won't run
     */
    public function run()
    {
        $gridCollection = $this->_getCollection();

        $i = 0;
        foreach ($gridCollection as $gridOrder) {
            $this->_anonymizeOrderGrid($gridOrder);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();
    }

    /**
     * @param Mage_Sales_Model_Order $gridOrder
     *
     * @throws Exception
     */
    protected function _anonymizeOrderGrid(Mage_Sales_Model_Order $gridOrder)
    {

        throw new Exception('Anonymization of order grid not implemented because implemented in Order');

    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Grid_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getResourceModel('sales/order_grid_collection')
            ->addFieldToSelect($this->_getMappings('OrderGrid')->getEntityAttributes());
        /* @var $collection Mage_Sales_Model_Resource_Order_Grid_Collection */

        $this->_collectionAddStaticAnonymized($collection, 0);

        return $collection;
    }

}