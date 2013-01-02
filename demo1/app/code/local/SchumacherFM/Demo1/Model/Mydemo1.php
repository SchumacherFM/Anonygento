<?php

class SchumacherFM_Demo1_Model_Mydemo1 extends Varien_Object
{

    public function changeMydemo1($mydemo1)
    {

        return str_replace('Init', 'Anon', $mydemo1);

    }

}
