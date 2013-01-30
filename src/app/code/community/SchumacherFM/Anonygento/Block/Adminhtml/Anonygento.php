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
        $this->_controller = 'adminhtml_anonygento';
        $this->_blockGroup = 'schumacherfm_anonygento';
        $this->_headerText = Mage::helper('core')->__('Anonygento');
        parent::__construct();
        $this->_removeButton('add');

        Mage::getSingleton('adminhtml/session')->addNotice(
            $this->__('Please use the shell script for running the anonymization process!')
        );
    }

}
