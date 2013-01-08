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

        Zend_Debug::dump($collection);
        exit;

    }

}
