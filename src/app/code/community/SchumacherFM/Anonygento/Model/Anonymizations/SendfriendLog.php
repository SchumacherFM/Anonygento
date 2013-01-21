<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_SendfriendLog extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{
    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeLog');
    }

    /**
     * @param Mage_Sendfriend_Model_Sendfriend $model
     */
    protected function _anonymizeLog(Mage_Sendfriend_Model_Sendfriend $model)
    {
        $emptyObject = new Varien_Object(array('anonymized' => 1));
        $this->_copyObjectData($emptyObject, $model);
        $model->getResource()->save($model);
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('sendfriend/sendfriend');
    }

}