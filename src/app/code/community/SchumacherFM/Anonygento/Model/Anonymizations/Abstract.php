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
        // do not run as getSingleton
        $mapping = Mage::getModel('schumacherfm_anonygento/random_mappings');
        /* @var $mapping SchumacherFM_Anonygento_Model_Random_Mappings */
        $mapped = $mapping->getMapping($type);

        $mapped->{'set' . self::COLUMN_ANONYMIZED}(self::COLUMN_ANONYMIZED);

        return $mapped;
    }

    /**
     * runs the anonymization process
     *
     * @param Varien_Data_Collection_Db $collection
     * @param string                    $anonymizationMethod
     */
    public function run($collection, $anonymizationMethod)
    {
        $i = 0;
        foreach ($collection as $model) {
            $this->$anonymizationMethod($model);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $collection = null;
        $this->getProgressBar()->finish();

    }

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
     * @param object                  $fromObject
     * @param object                  $toObject
     * @param Varien_Object           $mappings
     *
     * @return bool
     */
    protected function _copyObjectData($fromObject, $toObject, Varien_Object $mappings)
    {

        $mapped = $mappings->getData();

        if (count($mapped) === 0) {
            return FALSE;
        }

        $fromObject->{'set' . self::COLUMN_ANONYMIZED}(1);

        foreach ($mapped as $key => $newKey) {
            $data = $fromObject->getData($key);
            if ($data !== null) {
                $toObject->setData($newKey, $data);
            }
        }

        $fill = $mappings->getFill();
        if (is_array($fill)) {
            $fillModel = Mage::getSingleton('schumacherfm_anonygento/random_fill');
            $fillModel->setToObj($toObject);
            $fillModel->setMappings($mappings);
            $fillModel->fill();
        }

        Mage::dispatchEvent('anonygento_anonymizations_copy_after', array(
            'to_object' => $toObject,
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

    /**
     * @param string $modelName
     * @param string $mappingName
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName, $mappingName = NULL)
    {

        $collection = stristr($modelName, '_collection') !== FALSE
            ? Mage::getResourceModel($modelName)
            : Mage::getModel($modelName)->getCollection();

        if ($mappingName !== NULL) {
            $this->_collectionAddAttributeToSelect($collection,
                $this->_getMappings($mappingName)->getEntityAttributes()
            );
        }

        /* getOptions() please see shell class */
        if ($this->getOptions() && $this->getOptions()->getCollectionLimit()) {
            $offset = $this->getOptions()->getCollectionLimit() * $this->getOptions()->getCurrentRun();
            $collection->getSelect()->limit($this->getOptions()->getCollectionLimit(), $offset);
        }

        $this->_collectionAddStaticAnonymized($collection, 0);

        return $collection;
    }

}