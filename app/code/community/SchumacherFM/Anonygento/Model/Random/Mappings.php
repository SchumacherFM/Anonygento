<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Random_Mappings
{
    /**
     * these mappings defined the fields which can be overwritten
     * base model is: SchumacherFM_Anonygento_Model_Random_Customer
     *
     * RandomCusteomer => CustomerAddress
     */

    public static function getCustomerAddress()
    {

        return array(
            'anonymized' => 'anonymized',
            'prefix'     => 'prefix',
            'firstname'  => 'firstname',
            'lastname'   => 'lastname',
            'company'    => 'company',
            'telephone'  => 'telephone',
            'fax'        => 'fax',
            'street'     => 'street',

        );

    }

}