<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
abstract class SchumacherFM_Anonygento_Model_Anonymizations_Abstract extends Varien_Object
{
    /**
     * sql column name
     */
    const COLUMN_ANONYMIZED = 'anonymized';

    /**
     * @var Zend_ProgressBar
     */
    protected $_progressBar = null;

    /**
     * @var array
     */
    protected $_instances = array();

    /**
     * @param string $type
     * @param array  $arguments
     * @param bool   $forceNew
     *
     * @return object
     */
    protected function _getInstance($type, $arguments = array(), $forceNew = FALSE)
    {
        if (!isset($this->_instances[$type]) || $forceNew === TRUE) {
            $this->_instances[$type] = Mage::getModel($type, $arguments);
        }
        return $this->_instances[$type];
    }

    /**
     * @return SchumacherFM_Anonygento_Model_Random_Customer
     */
    protected function _getRandomCustomer()
    {
        return Mage::getSingleton('schumacherfm_anonygento/random_customer');
    }

    /**
     * @param string $type
     *
     * @return SchumacherFM_Anonygento_Model_Random_Mappings
     */
    protected function _getMappings($type)
    {
        $mapping = $this->_getInstance('schumacherfm_anonygento/random_mappings');
        /* @var $mapping SchumacherFM_Anonygento_Model_Random_Mappings */
        $mapped = $mapping->{'set' . $type}();

        Mage::dispatchEvent('anonygento_anonymizations_get_mapping_after', array(
            'type'   => $type,
            'mapped' => $mapped,
        ));

        return $mapped;
    }

    /**
     * executes and runs one anonymization process
     *
     * @return mixed
     */
    abstract public function run();

    /**
     * loads the current collection
     *
     * @return Varien_Data_CollectionDb
     */
    abstract protected function _getCollection();

    /**
     * @param Zend_ProgressBar $bar
     */
    public function setProgressBar(Zend_ProgressBar $bar)
    {
        $this->_progressBar = $bar;
    }

    /**
     * @return null|Zend_ProgressBar
     */
    public function getProgressBar()
    {
        return $this->_progressBar;
    }

    /**
     * copies the data from obj to another using a mapping array
     *
     * @param object                                          $fromObj
     * @param object                                          $toObj
     * @param SchumacherFM_Anonygento_Model_Random_Mappings   $mappings
     *
     * @return bool
     */
    protected function _copyObjectData($fromObj, $toObj, SchumacherFM_Anonygento_Model_Random_Mappings $mappings)
    {

        $mapped = $mappings->getData();

        if (count($mapped) === 0) {
            return FALSE;
        }

        foreach ($mapped as $key => $newKey) {
            $data = $fromObj->getData($key);
            if ($data !== null) {
                $toObj->setData($newKey, $data);
            }
        }

        $fill = $mappings->getFill();
        if (is_array($fill)) {
            $fillModel = $this->_getInstance('schumacherfm_anonygento/random_fill');
            $fillModel->setToObj($toObj);
            $fillModel->setMappings($mappings);
            $fillModel->fill();
        }

        Mage::dispatchEvent('anonygento_anonymizations_copy_after', array(
            'copied_object' => $toObj,
            'mappings'      => $mappings,
        ));

    }

    /**
     * @param object  $collection
     * @param integer $isAnonymized
     */
    protected function _collectionAddStaticAnonymized($collection, $isAnonymized = 0)
    {
        $isAnonymized = (int)$isAnonymized;

        if ($collection instanceof Mage_Eav_Model_Entity_Collection_Abstract) {
            $collection->addStaticField(self::COLUMN_ANONYMIZED);
            $collection->addAttributeToFilter(self::COLUMN_ANONYMIZED, $isAnonymized);
        } else {
            $collection->addFieldToSelect(self::COLUMN_ANONYMIZED);
            $select = $collection->getSelect();
            $select->where(self::COLUMN_ANONYMIZED . '=' . $isAnonymized);
        }

    }

    /**
     * @param object                                               $collection
     * @param array|SchumacherFM_Anonygento_Model_Random_Mappings  $fields from the mapping table the values
     */
    protected function _collectionAddAttributeToSelect($collection, $fields)
    {
        if ($fields instanceof SchumacherFM_Anonygento_Model_Random_Mappings) {
            $fields = $fields->getData();
        }

        foreach ($fields as $key => $field) {

            if ($key === 'fill' || is_array($field)) {
                continue;
            }

            $attributeOrField = ($collection instanceof Mage_Eav_Model_Entity_Collection_Abstract)
                ? 'addAttributeToSelect'
                : 'addFieldToSelect';
            $collection->$attributeOrField($field);
        }

    }

}