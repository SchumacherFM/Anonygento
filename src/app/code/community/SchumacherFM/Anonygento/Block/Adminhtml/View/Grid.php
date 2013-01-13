<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Block
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */

class SchumacherFM_Anonygento_Block_Adminhtml_View_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_attributeColumns = array();

    protected $_gridView = null;

    protected $_modelName = null;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_gridView = $this->getRequest()->getParam('gridView');

        parent::__construct();
        $this->setId('anonygento_grid');
        $this->_filterVisibility = TRUE;
        $this->_pagerVisibility  = TRUE;
    }

    protected function _getModelName()
    {
        if ($this->_modelName !== null) {
            return $this->_modelName;
        }

        $anonymizations = Mage::helper('schumacherfm_anonygento')->getAnonymizationsConfig();

        if (!isset($anonymizations->{$this->_gridView})) {
            throw new Mage_Adminhtml_Exception('Cannot find config value for: ' . $this->_gridView);
        }

        $this->_modelName = $anonymizations->{$this->_gridView}->model;
        return $this->_modelName;
    }

    /**
     * Prepare grid collection
     */
    protected function _prepareCollection()
    {
        $modelName = $this->_getModelName();

        if (stristr($modelName, '_collection')) {
            $collection = Mage::getResourceModel($modelName);
        } else {
            $collection = Mage::getModel($modelName)->getCollection();
        }

        $attributeColumns = $this->_getAttributeColumns();

        $attributeOrField = ($collection instanceof Mage_Eav_Model_Entity_Collection_Abstract)
            ? 'addAttributeToSelect'
            : 'addFieldToSelect';
        $collection->$attributeOrField($attributeColumns);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _getAttributeColumns()
    {
        if (count($this->_attributeColumns) > 0) {
            return $this->_attributeColumns;
        }
        $mapping = Mage::getModel('schumacherfm_anonygento/random_mappings');
        /* @var $mapping SchumacherFM_Anonygento_Model_Random_Mappings */
        $this->_attributeColumns = $mapping->getMapping($this->_gridView)->getEntityAttributes();

        // @todo remove columns which ends with _id

        return $this->_attributeColumns;

    }

    /**
     * Prepare grid columns
     */
    protected function _prepareColumns()
    {

        $attributeColumns = $this->_getAttributeColumns();

        foreach ($attributeColumns as $attribute) {

            $this->addColumn($attribute, array(
                'header'   => $this->__($attribute),
                'align'    => 'left',
                'index'    => $attribute,
                'sortable' => TRUE,
            ));
        }

        $this->addColumn('action',
            array(
                'header'    => $this->__('View'),
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption' => $this->__('View'),
                        'url'     => array('base' => $this->_getAdminUrl()),
                        'field'   => 'id'
                    ),
                ),
                'filter'    => FALSE,
                'sortable'  => FALSE,
                'is_system' => TRUE,
            ));
        return parent::_prepareColumns();
    }

    protected function _getAdminUrl()
    {
        /* @todo remove this bug. because we need a method which can properly find the admin edit url for any entity */
        $modelName = explode('/',$this->_getModelName());
        return '*/' . $modelName[0] . '/edit';
    }

    /**
     * Get row edit url
     *
     * @param $row
     *
     * @return bool|string
     */
    public function getRowUrl($row)
    {
        return FALSE;
    }

}
