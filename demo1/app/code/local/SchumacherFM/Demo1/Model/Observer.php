<?php
/**
 * @author kiri
 * @date 1/2/13
 */

class SchumacherFM_Demo1_Model_Observer extends Varien_Object
{

    public function alterMyCustomAttribute(Varien_Event_Observer $event)
    {
        Zend_Debug::dump($event);
        exit;

    }

}
