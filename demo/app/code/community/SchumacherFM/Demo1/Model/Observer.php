<?php
/**
 * @author kiri
 * @date 1/2/13
 */

class SchumacherFM_Demo1_Model_Observer
{

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
