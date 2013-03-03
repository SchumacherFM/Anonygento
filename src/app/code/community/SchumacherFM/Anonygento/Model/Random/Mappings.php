<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 *
 * do not run as Singleton class
 * the unsetData method is not implemented before each set ...
 */
class SchumacherFM_Anonygento_Model_Random_Mappings extends Varien_Object
{
    /**
     * @var array
     */
    protected $_isEntityTypesData = array();

    /**
     * @var Mage_Eav_Model_Config
     */
    protected $_eavConfig;

    protected function _construct()
    {
        parent::_construct();

        $entityTypesData          = Mage::getModel('eav/entity_type')->getCollection()->getData();
        $this->_isEntityTypesData = array();
        foreach ($entityTypesData as $type) {
            if (isset($type['attribute_model']) && !empty($type['attribute_model'])) { // only real EAV models ;-)
                $this->_isEntityTypesData[$type['entity_model']] = $type['entity_type_code'];
            }

        }
        $entityTypesData  = null;
        $this->_eavConfig = Mage::getSingleton('eav/config');
    }

    /**
     * @return array
     */
    public function getEntityAttributes()
    {
        $data = $this->getData();

        //getting the keys from these two xml config elements to
        //add the keys to the collection()->add[Attribute|Field]ToSelect method
        foreach (array('fill', 'system') as $element) {
            if (isset($data[$element])) {
                $systemKeys = array_keys($data[$element]);
                unset($data[$element]);
                $data = array_merge($data, $systemKeys);
            }
        }

        return array_unique(array_values($data));
    }

    /**
     * @param string $type
     *
     * @return SchumacherFM_Anonygento_Model_Random_Mappings
     * @throws Exception
     */
    public function getMapping($type)
    {
        $config = Mage::helper('schumacherfm_anonygento')->getAnonymizationsConfig();

        if (!isset($config->$type)) {
            Mage::throwException('Cannot find config node: ' . $type);
        }

        if (!isset($config->$type->mapping)) {
            Mage::throwException('Cannot find mapping node for ' . $type);
        }
        $model = isset($config->$type->model) ? (string)$config->$type->model : '';

        $this->setData($config->$type->mapping->asArray());

        if ($this->getUpdate()) {
            $update = $this->getUpdate();
            $this->unsUpdate();
            return $this->getMapping($update);
        }

        /**
         * check if we have an EAV model then
         * remove all columns from an EAV model which are invisible
         */
        if (isset($this->_isEntityTypesData[$model]) && !empty($this->_isEntityTypesData[$model])) {
            $thisData = $this->getData();

            foreach ($thisData as $randomKey => $attribute) {
                if (is_string($attribute) && !is_array($attribute)) {

                    if (
                        is_string($attribute) &&
                        $attribute !== 'update' &&
                        !$this->_isAttributeVisible($model, $attribute)
                    ) {
                        unset($thisData[$randomKey]);
                    }
                }
            }
            $this->setData($thisData);
        }

        return $this;
    }

    /**
     * @param string $model
     * @param string $attribute
     *
     * @return boolean
     */
    protected function _isAttributeVisible($model, $attribute)
    {
        $attribute = $this->_eavConfig->getAttribute($this->_isEntityTypesData[$model], $attribute);
        return $attribute->getIsVisible();
    }
}