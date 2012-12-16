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
     * @todo use group by query to avoid two queries
     */

    /**
     * @var null
     */
    protected $_readConnection = null;

    public function _construct()
    {
        parent::_construct();
        $this->_readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
    }

    /**
     * @param $modelName
     *
     * @return Object
     */
    protected function  _getModel($modelName)
    {
        if (stristr($modelName, '_collection') !== FALSE) {
            return Mage::getResourceModel($modelName);
        } else {
            return Mage::getModel($modelName)->getCollection();
        }

    }

    /**
     * @param     Mage_Core_Model ...    $model
     * @param int $anonymized
     */
    protected function  _sqlWhereAndExec($model, $anonymized = 0)
    {
        $countSql = $model->getSelectCountSql();
        /* @var $countSql Varien_Db_Select */
        $countSql->where('anonymized=' . $anonymized);
        $result = $this->_readConnection->fetchOne($countSql);
        return (int)$result;

    }

    /**
     * @param string $model
     *
     * @return integer
     */
    public function unAnonymized($model)
    {
        $model = $this->_getModel($model);
        if (!$model) {
            return -1;
        }

        /**
         * don't use count(), otherwise it loads the whole collection
         * and counts the items
         * only getSize will perform a 'select count(*)...' query
         */
//        return $model->getSize();

        return $this->_sqlWhereAndExec($model, 0);

    }

    /**
     * @param string $model
     *
     * @return integer
     */
    public function anonymized($model)
    {
        $model = $this->_getModel($model);
        if (!$model) {
            return -1;
        }

        return $this->_sqlWhereAndExec($model, 1);

    }

}