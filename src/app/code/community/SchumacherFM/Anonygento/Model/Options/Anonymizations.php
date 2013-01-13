<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Block
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */

class SchumacherFM_Anonygento_Model_Options_Anonymizations extends Varien_Object
{
    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        // @see config.xml
        $anonymizations = Mage::helper('schumacherfm_anonygento')->getAnonymizationsConfig();

        $return = array();
        foreach ($anonymizations as $node) {
            if (!isset($node->active) || (int)$node->active !== 1) {
                continue;
            }

            $anon_model = isset($node->anonymizationModel) && (string)$node->anonymizationModel !== ''
                ? (string)$node->anonymizationModel
                : (string)$node->getName();

            $label = isset($node->label) && (string)$node->label !== ''
                ? (string)$node->label
                : Mage::helper('schumacherfm_anonygento')->__($anon_model);

            $return[] = array(
                'label' => $label,
                'value' => $anon_model,
                'model' => (string)$node->model
            );

        }
        return $return;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $_options = array();
        foreach ($this->getAllOptions() as $option => $modelName) {
            $_options[$option['value']] = $option['label'];
        }
        return $_options;
    }

    /**
     * @var Varien_Data_Collection
     */
    protected $_collection = null;

    /**
     * @return null|Varien_Data_Collection
     * @throws Exception
     */
    public function getCollection()
    {
        if ($this->_collection !== null) {
            return $this->_collection;
        }

        $this->_collection = new Varien_Data_Collection();

        foreach ($this->getAllOptions() as $option) {
            $this->_collection->addItem(new Varien_Object($option));
        }

        $rowCountModel = Mage::getSingleton('schumacherfm_anonygento/counter');
        foreach ($this->_collection as $option) {

            if (!$option->getValue() || !$option->getModel()) {
                throw new Exception('Missing value or model in the anonymization collection');
            }

            $option
            /* @see SchumacherFM_Anonygento_Block_Adminhtml_Anonygento_Grid column: Status */
                ->setStatus(0)
                ->setUnanonymized($rowCountModel->unAnonymized($option->getModel()))
                ->setAnonymized($rowCountModel->anonymized($option->getModel()));
        }
        return $this->_collection;
    }

}