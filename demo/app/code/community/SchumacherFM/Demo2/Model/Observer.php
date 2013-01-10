<?php

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

        $collection->addItem(new Varien_Object($option));

    }

}
