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
        /* @var $model Mage_Customer_Model_Resource_Customer_Collection */

        /**
         * don't use count(), otherwise it loads the whole collection
         * and counts the items
         * only getSize will perform a 'select count(*)...' query
         */
        Zend_Debug::dump(get_class($model));
        $model->addStaticField('anonymized');
        $model->addAttributeToFilter('anonymized', array('eq'=>0));
//        $model->addAttributeToFilter('anonymized',null);
//        Zend_Debug::dump($model->getSelect() );
        $return = $model->getSize();

//        Zend_Debug::dump($return);
//        exit;

        return $model->getSize();

//        return $this->_sqlWhereAndExec($model, 0);

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
        $model->addAttributeToFilter('anonymized', 1);

//        return $this->_sqlWhereAndExec($model, 1);

    }

}