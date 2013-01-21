<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Shipment extends SchumacherFM_Anonygento_Model_Anonymizations_AbstractOrderCreInShip
{
    protected function _construct()
    {
        parent::_construct();
        $this->setModelName('shipment');
        $this->setCollectionName('shipments');
    }

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        $this->_run();
    }

}