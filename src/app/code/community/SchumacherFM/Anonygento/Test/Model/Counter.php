<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Test
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Test_Model_Counter extends EcomDev_PHPUnit_Test_Case
{
    protected $_model = null;

    protected function setUp()
    {
        parent::setUp();
        $this->_model = Mage::getModel('schumacherfm_anonygento/counter');
    }

    /**
     * @loadFixture
     */
    public function testUnAnonymized()
    {
        $expected = 2;
        $actual   = $this->_model->unAnonymized('customer/customer');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @loadFixture
     */
    public function testAnonymized()
    {
        $expected = 3;
        $actual   = $this->_model->anonymized('customer/customer');
        $this->assertEquals($expected, $actual);
    }

}