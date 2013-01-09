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
            'label'               => 'Catalog Product',

            // name of the anonymization model
            'value'               => 'schumacherfm_demo2/catalogProduct',

            // name of your custom model
            'model'               => 'catalog/product',
        );

        $rowCountModel = Mage::getSingleton('schumacherfm_anonygento/counter');

        $optObj = new Varien_Object($option);
        $optObj
        /* @see SchumacherFM_Anonygento_Block_Adminhtml_Anonygento_Grid column: Status */
            ->setStatus(Mage::helper('schumacherfm_anonygento')->getAnonymizations($option['value']))
            ->setUnanonymized($rowCountModel->unAnonymized($option['model']))
            ->setAnonymized($rowCountModel->anonymized($option['model']));

        $collection->addItem($optObj);

    }

}
