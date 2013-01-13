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

    protected $attributeColumns = array();

    protected $gridView;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->gridView = $this->getRequest()->getParam('gridView');

        parent::__construct();
        $this->setId('anonygento_grid');
        $this->_filterVisibility = TRUE;
        $this->_pagerVisibility  = TRUE;
    }

    protected function _getModelName()
    {

        $anonymizations = Mage::helper('schumacherfm_anonygento')->getAnonymizationsConfig();

        if (!isset($anonymizations->{$this->gridView})) {
            throw new Mage_Adminhtml_Exception('Cannot find config value for: ' . $this->gridView);
        }

        return $anonymizations->{$this->gridView}->model;
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
        if (count($this->attributeColumns) > 0) {
            return $this->attributeColumns;
        }
        $mapping = Mage::getModel('schumacherfm_anonygento/random_mappings');
        /* @var $mapping SchumacherFM_Anonygento_Model_Random_Mappings */
        $this->attributeColumns = $mapping->getMapping($this->gridView)->getEntityAttributes();
        return $this->attributeColumns;

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

        return parent::_prepareColumns();
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
