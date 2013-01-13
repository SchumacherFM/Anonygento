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
        $this->_filterVisibility = FALSE;
        $this->_pagerVisibility  = FALSE;
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
            'header'   => $this->__('Value'),
            'align'    => 'left',
            'index'    => 'value',
            'sortable' => FALSE,
        ));

//        $this->addColumn('description', array(
//            'header' => $this->__('Description'),
//            'align' => 'left',
//            'index' => 'description',
//            'sortable' => false,
//        ));

        $this->addColumn('label', array(
            'header'   => $this->__('Label'),
            'align'    => 'left',
            'index'    => 'label',
            'sortable' => FALSE,
        ));

        $this->addColumn('unanonymized', array(
            'header'   => $this->__('Unanonymized'),
            'align'    => 'left',
            'index'    => 'unanonymized',
            'sortable' => FALSE,
        ));

        $this->addColumn('anonymized', array(
            'header'   => $this->__('Anonymized'),
            'align'    => 'left',
            'index'    => 'anonymized',
            'sortable' => FALSE,
        ));

        $this->addColumn('status', array(
            'header'         => $this->__('Status'),
            'align'          => 'left',
            'index'          => 'status',
            'frame_callback' => array($this, 'decorateStatus')
        ));

//        $this->addColumn('action',
//            array(
//                'header' => $this->__('View data'),
//                'type' => 'action',
//                'getter' => 'getValue',
//                'actions' => array(
//                    array(
//                        'caption' => $this->__('View'),
//                        'url' => array('base' => '*/*/view'),
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
     * @param $value
     * @param $row
     * @param $column
     * @param $isExport
     *
     * @return string
     */
    public function decorateStatus($value, $row, $column, $isExport)
    {

        if ( $row->getUnanonymized() == 0) {
            $cell = '<span class="grid-severity-notice"><span>' . $this->__('Anonymized!') . '</span></span>';
        } else {
            $cell = '<span class="grid-severity-critical"><span>' . $this->__('Sensitive Data!') . '</span></span>';
        }
        return $cell;
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
        //return $this->getUrl('*/*/edit', array('type'=>$row->getId()));
    }

}
