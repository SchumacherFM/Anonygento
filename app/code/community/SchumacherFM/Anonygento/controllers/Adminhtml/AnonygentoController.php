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
    public function indexAction()
    {
        $this->_title(Mage::helper('schumacherfm_anonygento')->__('System'))
            ->_title(Mage::helper('adminhtml')->__('Tools'))
            ->_title(Mage::helper('adminhtml')->__('Anonygento'));

        $this->loadLayout();
        $this->_setActiveMenu('system/tools/anonygento');
        $this->_addBreadcrumb(Mage::helper('schumacherfm_anonygento')->__('Anonygento'),
            Mage::helper('schumacherfm_anonygento')->__('Anonygento'));

        $block = $this->getLayout()->createBlock(
            'schumacherfm_anonygento/anonygento',
            'anonygento'
        );

        $this->getLayout()->getBlock('content')->append($block);

        $this->renderLayout();
    }

    /**
     * Action for saving an action
     *
     * @return void
     */
    public function saveAction()
    {
        try {
            /** @var $anonygento SchumacherFM_Anonygento_Model_Anonygento */
            $anonygento = Mage::getModel('schumacherfm_anonygento/anonygento');
            $anonygento->anonymizeAll();
            foreach ($anonygento->getResults() as $resultLabel => $resultCount) {
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('schumacherfm_anonygento')->__('Anonymized %s %s.', $resultCount, $resultLabel)
                );
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirectReferer();
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