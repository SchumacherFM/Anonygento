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

    protected $_configValue = array();

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

    protected function _getConfigValue($name)
    {
        if (isset($this->_configValue[$name])) {
            return $this->_configValue[$name];
        }

        $anonymizations = Mage::helper('schumacherfm_anonygento')->getAnonymizationsConfig();

        if (!isset($anonymizations->{$this->_gridView})) {
            throw new Mage_Adminhtml_Exception('Cannot find config value for: ' . $this->_gridView);
        }

        $this->_configValue[$name] = $anonymizations->{$this->_gridView}->{$name};
        return $this->_configValue[$name];
    }

    /**
     * Prepare grid collection
     *
     * an awesome feature would be that all these columns will be hided
     * which are empty in the current grid view.
     */
    protected function _prepareCollection()
    {
        $modelName = $this->_getConfigValue('model');

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
        return $this->_attributeColumns;

    }

    /**
     * Prepare grid columns
     */
    protected function _prepareColumns()
    {
        $attributeColumns = $this->_getAttributeColumns();

        foreach ($attributeColumns as $attribute) {

            if (strrpos($attribute, '_id') === (strlen($attribute) - 3) ||
                $attribute === 'additional_information'
            ) {
                continue;
            }

            $this->addColumn($attribute, array(
                'header'   => $this->__($attribute),
                'align'    => 'left',
                'index'    => $attribute,
                'sortable' => TRUE,
            ));
        }

        $adminRoute = $this->_getAdminUrl();
        $this->addColumn('action',
            array(
                'header'    => $this->__('View'),
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption' => $this->__('View'),
                        'url'     => array('base' => $adminRoute[0]),
                        'field'   => $adminRoute[1],
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
        $adminRoute = (string)$this->_getConfigValue('adminRoute');
        if (empty($adminRoute)) {
            return array('*/*/*', 'id');
        }
        $parts = explode('?', $adminRoute);
        return array('*/' . $parts[0], $parts[1]);
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
        $adminRoute = (string)$this->_getConfigValue('adminRoute');
        if (empty($adminRoute)) {
            return FALSE;
        }
        $parts = explode('?', $adminRoute);
        return $this->getUrl('*/' . $parts[0], array($parts[1] => $row->getId()));

    }

}
