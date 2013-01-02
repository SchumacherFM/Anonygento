<?php
/**
 * @author kiri
 * @date 1/2/13
 */

class SchumacherFM_Demo1_Model_Observer
{

    public function alterMyCustomAttribute(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $type  = $event->getType();

        if ($type !== 'Customer') {
            return null;
        }

        $mapped = $event->getMapped();

        $fill = $mapped->getFill();

        $fill['mydemo1'] = array(
            'model'  => 'schumacherfm_demo1/mydemo1',
            'method' => 'changeMydemo1',

            // leave args empty to the the current customer model
            'args'   => NULL
        );

        $mapped->setFill($fill);

    }

}
