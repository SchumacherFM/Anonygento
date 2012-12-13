<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Block
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */

class SchumacherFM_Anonygento_Block_Adminhtml_Anonygento_Grid extends Mage_Adminhtml_Block_Widget_Grid
{


    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('anonygento_grid');
        $this->_filterVisibility = false;
        $this->_pagerVisibility = false;
        $this->setTitle($this->__('Anonygento'));

    }

    /**
     * Prepare grid collection
     */
    protected function _prepareCollection()
    {

        $collection = Mage::getModel('schumacherfm_anonygento/options_anonymizations')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Add name and description to collection elements
     */
//    protected function _afterLoadCollection()
//    {
//        foreach ($this->_collection as $item) {
//        }
//        return $this;
//    }

    /**
     * Prepare grid columns
     */
    protected function _prepareColumns()
    {
        $baseUrl = $this->getUrl();
        $this->addColumn('value', array(
            'header' => $this->__('Value'),
            'width' => '180',
            'align' => 'left',
            'index' => 'value',
            'sortable' => false,
        ));

//        $this->addColumn('description', array(
//            'header' => $this->__('Description'),
//            'align' => 'left',
//            'index' => 'description',
//            'sortable' => false,
//        ));

        $this->addColumn('label', array(
            'header' => $this->__('Label'),
            'align' => 'left',
            'index' => 'label',
            'width' => '180',
            'sortable' => false,
        ));

        $this->addColumn('rowcount', array(
            'header' => $this->__('Row count'),
            'align' => 'left',
            'index' => 'rowcount',
            'width' => '180',
            'sortable' => false,
        ));

        $this->addColumn('status', array(
            'header' => $this->__('Status'),
            'width' => '120',
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => array(0 => $this->__('Sensitive Data!'), 1 => $this->__('Anonymized!')),
            'frame_callback' => array($this, 'decorateStatus')
        ));

//        $this->addColumn('action',
//            array(
//                'header' => $this->__('Action'),
//                'width' => '100',
//                'type' => 'action',
//                'getter' => 'getValue',
//                'actions' => array(
//                    array(
//                        'caption' => $this->__('Anonymize'),
//                        'url' => array('base' => '*/*/save'),
//                        'field' => 'exec'
//                    ),
//                ),
//                'filter' => false,
//                'sortable' => false,
//                'is_system' => true,
//            ));

        return parent::_prepareColumns();
    }

    /**
     * Decorate status column values
     *
     * @return string
     */
    public function decorateStatus($value, $row, $column, $isExport)
    {
        if ($row->getStatus()) {
            $cell = '<span class="grid-severity-notice"><span>' . $value . '</span></span>';
        } else {
            $cell = '<span class="grid-severity-critical"><span>' . $value . '</span></span>';
        }
        return $cell;
    }

    /**
     * Get row edit url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return false;
        //return $this->getUrl('*/*/edit', array('type'=>$row->getId()));
    }

    /**
     * Add mass-actions to grid
     */
    protected function _XXXprepareMassaction()
    {
        $this->setMassactionIdField('value');
        $this->getMassactionBlock()->setFormFieldName('types');

        $modeOptions = Mage::getModel('index/process')->getModesOptions();

        $this->getMassactionBlock()->addItem('enable', array(
            'label' => Mage::helper('index')->__('Enable'),
            'url' => $this->getUrl('*/*/massEnable'),
        ));
        $this->getMassactionBlock()->addItem('disable', array(
            'label' => Mage::helper('index')->__('Disable'),
            'url' => $this->getUrl('*/*/massDisable'),
        ));
        $this->getMassactionBlock()->addItem('refresh', array(
            'label' => Mage::helper('index')->__('Refresh'),
            'url' => $this->getUrl('*/*/massRefresh'),
            'selected' => true,
        ));

        return $this;
    }
}
