<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Test_Model_Random_LoremIpsum_LoremIpsum extends EcomDev_PHPUnit_Test_Case
{

    /**
     * @var SchumacherFM_Anonygento_Model_Random_LoremIpsum
     */
    protected $_loremIpsum = null;

    protected function setUp()
    {
        parent::setUp();
        $this->_loremIpsum = Mage::getModel('schumacherfm_anonygento/random_loremIpsum');
    }

    /**
     * @test
     */
    public function testGetLoremIpsumHtml()
    {
        $text = $this->_loremIpsum->getLoremIpsum(10, 'html');
        $this->assertRegExp('~<[a-z]+>~', $text, 'Test for HTML');
        $this->assertContains('lorem ipsum', $text, 'Test for string lorem ipsum');
    }

}