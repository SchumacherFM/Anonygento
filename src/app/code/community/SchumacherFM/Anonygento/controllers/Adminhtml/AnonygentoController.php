<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Controller
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Adminhtml_AnonygentoController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return void
     */
    public function indexAction()
    {
        $this->_title(Mage::helper('schumacherfm_anonygento')->__('System'))
            ->_title(Mage::helper('adminhtml')->__('Tools'))
            ->_title(Mage::helper('adminhtml')->__('Anonygento'));

        $this->loadLayout();
        $this->_setActiveMenu('system/tools/anonygento');

        $abc = Mage::helper('schumacherfm_anonygento')->__('Anonygento');
        $this->_addBreadcrumb($abc, $abc);
        $this->renderLayout();
    }

    /**
     * @return void
     */
    public function viewAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Check if user is allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('schumacherfm_anonygento/anonygento');
    }

}