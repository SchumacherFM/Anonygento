<?php

class SchumacherFM_Demo2_Model_Mydemo2 extends Varien_Object
{
    public function changeMydemo2($mydemo2)
    {
        return str_replace('Init', 'Anon', $mydemo2);
    }
}
