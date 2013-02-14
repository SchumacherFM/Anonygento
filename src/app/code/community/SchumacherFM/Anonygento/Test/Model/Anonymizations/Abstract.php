<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Test
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Test_Model_Anonymizations_Abstract extends EcomDev_PHPUnit_Test_Case
{

    /**
     * @test
     * @loadFixture
     */
    public function testCopyObjectData()
    {
       //  getMockForAbstractClass($originalClassName, array $arguments = array(), $mockClassName = '', $callOriginalConstructor = TRUE,
        // $callOriginalClone = TRUE, $callAutoload = TRUE, $mockedMethods = array(), $cloneArguments = FALSE)

        $abstract = $this->getMockForAbstractClass(
            'SchumacherFM_Anonygento_Model_Anonymizations_Abstract',
            array(),
            'Mock_Anonymizations_Abstract',
            FALSE,
            true,
            true,
            array('_copyObjectData')
        );

//        $abstract->getConfigName('customer');

        Zend_Debug::dump( get_class_methods($abstract) );
        Zend_Debug::dump(get_class($abstract));
        exit;

    }
}