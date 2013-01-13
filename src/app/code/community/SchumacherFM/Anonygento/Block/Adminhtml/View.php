<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Block
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Block_Adminhtml_View extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * Class constructor
     */
    public function __construct()
    {
        $gridTable = $this->getRequest()->getParam('gridView');

        $this->_controller = 'adminhtml_view'; // fake controller
        $this->_blockGroup = 'schumacherfm_anonygento';
        $this->_headerText = Mage::helper('core')->__('Anonygento: Viewing entity: ' . $gridTable);
        parent::__construct();
        $this->_removeButton('add');

    }

}
