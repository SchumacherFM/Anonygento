<?php
/**
 * @author kiri
 * @date 1/2/13
 */

class SchumacherFM_Demo2_Model_Observer
{

    public function afterAnonOptionCollection(Varien_Event_Observer $observer)
    {
        $event      = $observer->getEvent();
        $collection = $event->getCollection();

        $option = array(
            'value' => 'catalogProduct',
            'model' => 'catalog/product'
        );

        $rowCountModel = Mage::getModel('schumacherfm_anonygento/counter');

        $optObj = new Varien_Object($option);
        $optObj
        /* @see SchumacherFM_Anonygento_Block_Adminhtml_Anonygento_Grid column: Status */
            ->setStatus(Mage::helper('schumacherfm_anonygento')->getAnonymizations($option['value']))
            ->setUnanonymized($rowCountModel->unAnonymized($option['model']))
            ->setAnonymized($rowCountModel->anonymized($option['model']));

        $collection->addItem($optObj);

        Zend_Debug::dump($collection);
        exit;

    }

}
