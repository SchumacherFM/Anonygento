<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Invoice extends SchumacherFM_Anonygento_Model_Anonymizations_AbstractOrderCreInShip
{
    protected function _construct()
    {
        parent::_construct();
        $this->setModelName('invoice');
        $this->setCollectionName('invoice');
    }
}