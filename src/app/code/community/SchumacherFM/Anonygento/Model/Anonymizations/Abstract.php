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

    protected $_options = array();

    /**
     * @var SchumacherFM_Anonygento_Model_Random_Mappings
     */
    protected $_mappings = null;

    /**
     * loads among other things the xml config: options
     * <global><anonygento><anonymizations><[element]><options>
     */
    protected function _construct()
    {
        parent::_construct();

        if (isset(Mage::helper('schumacherfm_anonygento')->getAnonymizationsConfig($this->_getConfigNodeName())->options)) {
            $this->_options = Mage::helper('schumacherfm_anonygento')
                ->getAnonymizationsConfig($this->_getConfigNodeName())
                ->options
                ->asArray();
        } else {
            $this->_options = array();
        }

        $this->_setMappings();
    }

    /**
     * gets the real config node name depending on how the class has been instantiated.
     * the class name is used as identifier to get the current config node if the class is
     * instantiated via getSingleton in another class
     *
     * @return string
     * @throws Exception
     */
    protected function _getConfigNodeName()
    {
        /** getConfigName() is initialized in SchumacherFM_Anonygento_Model_Console_Console::getModel as argument
        this property not set when the model is called via getSingleton within a class
         */
        if (!$this->getConfigName() || $this->getConfigName() === '') {
            // class instantiated via getSingleton
            $classNameParts = explode('_', get_class($this));
            $configNodeName = array_pop($classNameParts);
            // first character to lowercase
            $configNodeName = strtolower(substr($configNodeName, 0, 1)) . substr($configNodeName, 1);
        } else {
            // class instantiated via getModel in the shell class
            $configNodeName = $this->getConfigName();
        }
        return $configNodeName;
    }

    /**
     *
     * @return SchumacherFM_Anonygento_Model_Random_Mappings
     */
    protected function _getMappings()
    {
        return $this->_mappings;
    }

    /**
     * sets the current mapping object
     *
     * @return SchumacherFM_Anonygento_Model_Random_Mappings
     */
    private function _setMappings()
    {
        // do not run as getSingleton ... why?
        $this->_mappings = Mage::getModel('schumacherfm_anonygento/random_mappings')
            ->getMapping($this->_getConfigNodeName())
            ->{'set' . self::COLUMN_ANONYMIZED}(self::COLUMN_ANONYMIZED);
    }

    /**
     * to configure an option please use the xml config:
     * <global><anonygento><anonymizations><[element]><options>
     *
     * @param string $value
     * @param string $type
     *
     * @return mixed
     */
    protected function _getOption($value, $type = 'bool')
    {
        switch ($type) {
            case 'bool':
                return (isset($this->_options[$value]) && (int)$this->_options[$value] === 1);
            case 'int':
                return isset($this->_options[$value]) ? (int)$this->_options[$value] : NULL;
            case 'str':
                return isset($this->_options[$value]) ? (string)$this->_options[$value] : NULL;
            default:
                return FALSE;
        }
    }

    /**
     * @return SchumacherFM_Anonygento_Model_Random_Customer
     */
    protected function _getRandomCustomer()
    {
        return Mage::getSingleton('schumacherfm_anonygento/random_customer');
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
     * @param Varien_Object $fromObject
     * @param Varien_Object $toObject
     * @param boolean       $useStrict
     *
     * @return bool
     */
    protected function _copyObjectData($fromObject, $toObject, $useStrict = TRUE)
    {

        $mappings = $this->_getMappings();
        $fill     = $mappings->getFill();
        $mapped   = $mappings->getData();
        if (isset($mapped['fill'])) {
            unset($mapped['fill']);
        }
        if (isset($mapped['system'])) {
            unset($mapped['system']);
        }

        if (count($mapped) === 0) {
            return FALSE;
        }

        $fromObject->{'set' . self::COLUMN_ANONYMIZED}(1);
        $getDataFromObject = $fromObject->getData();

        foreach ($mapped as $key => $newKey) {

            // throw an error if there is no key in fromObject
            if ($useStrict && !array_key_exists($key, $getDataFromObject)) {

                Zend_Debug::dump($fromObject->getData());
                echo PHP_EOL;
                Zend_Debug::dump($toObject->getData());

                $msg = 'Check your config.xml!' . PHP_EOL . $key . ' not Found in fromObj: ' . get_class($fromObject) . ' copied toObj: ' .
                    get_class($toObject) . PHP_EOL;

                throw new Exception($msg);
            }

            $data = $fromObject->getData($key);
            if ($data !== null) {
                $toObject->setData($newKey, $data);
            }
        }

        if ($fill && is_array($fill)) {
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
     * merge an additional object into the toObject
     *
     * @param Varien_Object $fromObject
     * @param Varien_Object $toObject
     *
     * @return bool
     * @throws Exception
     */
    protected function _mergeMissingAttributes(Varien_Object $fromObject, Varien_Object $toObject)
    {
        $mappings = $this->_getMappings();
        $mappings->unsFill();
        $mappings->unsSystem();
        $mapped = $mappings->getData();

        if (count($mapped) === 0) {
            return FALSE;
        }
        foreach ($mapped as $key => $value) {
            $data = $fromObject->getData($key);
            if (!$toObject->hasData($key)) {
                $toObject->setData($key, $data);
            }
        }
        return TRUE;
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName, $useMapping = TRUE)
    {

        $collection = stristr($modelName, '_collection') !== FALSE
            ? Mage::getResourceModel($modelName)
            : Mage::getModel($modelName)->getCollection();

        if ($useMapping === TRUE) {
            $this->_collectionAddAttributeToSelect($collection);
        }

        /* getOptions() please see shell class */
        if ($this->getOptions() && $this->getOptions()->getCollectionLimit()) {
            $offset = $this->getOptions()->getCollectionLimit() * $this->getOptions()->getCurrentRun();
            $collection->getSelect()->limit($this->getOptions()->getCollectionLimit(), $offset);
        }

        $this->_collectionAddStaticAnonymized($collection, 0);

        return $collection;
    }

    /**
     * @param object  $collection
     * @param integer $isAnonymized
     */
    protected function _collectionAddStaticAnonymized($collection, $isAnonymized = 0)
    {
        $isAnonymized = (int)$isAnonymized;

        // @todo check here if column has already been added

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
     * @param Varien_Data_Collection_Db $collection
     */
    protected function _collectionAddAttributeToSelect(Varien_Data_Collection_Db $collection)
    {
        $fields = $this->_getMappings()->getEntityAttributes();

        $attributeOrField = ($collection instanceof Mage_Eav_Model_Entity_Collection_Abstract)
            ? 'addAttributeToSelect'
            : 'addFieldToSelect';

        $collection->$attributeOrField($fields);
    }

}