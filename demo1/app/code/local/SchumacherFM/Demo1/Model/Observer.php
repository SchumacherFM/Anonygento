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
        $event    = $observer->getEvent();
        $toObject = $event->getToObject();

        if ($toObject instanceof Mage_Customer_Model_Address) {

            if ($toObject->getTelephone()) {

                $telPrefix = '0049-';
                switch ($toObject->getCountryId()) {
                    case 'CH':
                        $telPrefix = '0041-';
                        break;
                    case 'AT':
                        $telPrefix = '0043-';
                        break;
                }

                $toObject->setTelephone($telPrefix . '030-' . mt_rand());
            }
        }
    }

}
