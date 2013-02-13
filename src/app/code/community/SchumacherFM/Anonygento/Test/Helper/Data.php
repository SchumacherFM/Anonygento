<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Test
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{
    protected $dataHelper;

    public function setUp()
    {
        parent::setUp();
        $this->datahelper = Mage::helper('schumacherfm_anonygento');
    }

    /**
     * @test
     * @loadFixture
     */
    public function getLocaleForData()
    {
        $expected = 'oz_AU';
        $actual   = $this->datahelper->getLocaleForData();
        $this->assertEquals($expected, $actual, 'If the locale is: ' . $expected);
    }

    /**
     * @test
     * @loadFixture
     */
    public function getAnonymizationsConfig()
    {
        $actual = $this->datahelper->getAnonymizationsConfig();
        $this->assertEquals((int)$actual->customer->active, 4711);
        $this->assertEquals((int)$actual->customerAddress->active, 4712);
    }

    /**
     * @test
     * @loadFixture
     */
    public function getRandomConfig()
    {
        $actual = $this->datahelper->getRandomConfig();
        $this->assertEquals((int)$actual->customer->emptyCsv->stringMin, 4711);
    }

}