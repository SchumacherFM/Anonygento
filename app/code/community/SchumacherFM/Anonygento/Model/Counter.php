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
     * current collection holder
     *
     * @var null
     */
    protected $_currentCollection = null;

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
     * gets the collection
     *
     * @param $modelName
     *
     * @return Object
     */
    protected function  _getCollection($modelName)
    {
        if (stristr($modelName, '_collection') !== FALSE) {
            return Mage::getResourceModel($modelName);
        } else {
            return Mage::getModel($modelName)->getCollection();
        }

    }

    /**
     * @param obj $model
     * @param int $anonymized
     *
     * @return int
     */
    protected function  _sqlWhereAndExec($anonymized = 0)
    {
        $anonymized = (int)$anonymized;
        $countSql = $this->_currentCollection->getSelectCountSql();
        /* @var $countSql Varien_Db_Select */
        $countSql->where('anonymized=' . $anonymized);
        $result = $this->_readConnection->fetchOne($countSql);
        return (int)$result;

    }

    /**
     * @param obj $model
     * @param int $anonymized
     *
     * @return int
     */
    protected function  _addStatic($anonymized = 0)
    {
        /**
         * don't use count(), otherwise it loads the whole collection
         * and counts the items
         * only getSize will perform a 'select count(*)...' query
         */
        $this->_currentCollection->addStaticField('anonymized');
        $this->_currentCollection->addAttributeToFilter('anonymized', $anonymized);
        return (int)$this->_currentCollection->getSize();

    }

    protected function _chooseMethod($anonymized = 0)
    {

        $method = method_exists($this->_currentCollection, 'addStaticField') ? '_addStatic' : '_sqlWhereAndExec';
        return $this->$method($anonymized);

    }

    /**
     * @param string $model
     *
     * @return integer
     */
    public function unAnonymized($model)
    {
        $this->_currentCollection = $this->_getCollection($model);
        if (!$this->_currentCollection) {
            return -1;
        }
        /* @var $model Mage_Customer_Model_Resource_Customer_Collection */

        return $this->_chooseMethod(0);
    }

    /**
     * @param string $model
     *
     * @return integer
     */
    public function anonymized($model)
    {
        $this->_currentCollection = $this->_getCollection($model);
        if (!$this->_currentCollection) {
            return -1;
        }

        return $this->_chooseMethod(1);

    }

}