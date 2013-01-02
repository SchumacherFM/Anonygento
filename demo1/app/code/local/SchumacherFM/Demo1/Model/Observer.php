<?php
/**
 * @author kiri
 * @date 1/2/13
 */

class SchumacherFM_Demo1_Model_Observer
{

    public function mappingAfterAlterMyCustomAttribute(Varien_Event_Observer $observer)
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
            // leave args empty to get the current value of attribute mydemo1
            'args'   => NULL
        );

        $fill['mydemo2'] = array(
            'method' => 'mt_rand',
            'args'   => array(100, 1000)
        );

        $mapped->setFill($fill);

    }

    public function copyAfterAlterCustomerTelephone(Varien_Event_Observer $observer)
    {
        $event        = $observer->getEvent();
        $copiedObject = $event->getCopiedObject();
//        $mappings     = $event->getMappings();

        if ($copiedObject instanceof Mage_Customer_Model_Address) {

            if ($copiedObject->getTelephone()) {
                $copiedObject->setTelephone('0049-030-' . mt_rand());
            }
        }
    }

}
