<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Block
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Block_Adminhtml_Anonygento extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Retrieve the POST URL for the form
     *
     * @return string URL
     */
    public function getPostActionUrl()
    {
        return $this->getUrl('*/*/save');
    }

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_controller = 'anonygento';
        $this->_headerText = Mage::helper('core')->__('Anonygento');
        parent::__construct();
        $this->_removeButton('add');
        $this->_addButton('flush_magento', array(
            'label'     => Mage::helper('core')->__('Anonymize Magento'),
            'onclick'   => 'setLocation(\'' . $this->getFlushSystemUrl() .'\')',
            'class'     => 'delete',
        ));

    }

    /**
     * Get url for clean cache storage
     */
    public function getFlushStorageUrl()
    {
        return $this->getUrl('*/*/flushAll');
    }

    /**
     * Get url for clean cache storage
     */
    public function getFlushSystemUrl()
    {
        return $this->getUrl('*/*/flushSystem');
    }


}
