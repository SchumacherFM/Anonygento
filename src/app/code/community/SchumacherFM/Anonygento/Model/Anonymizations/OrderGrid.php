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
     * this wont run, just FYI in stat view
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
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
        return parent::_getCollection('sales/order_grid_collection');
    }

}