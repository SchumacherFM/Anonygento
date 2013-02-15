<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Test_Model_Random_Fill extends EcomDev_PHPUnit_Test_Case
{

    protected $_fillModel;

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     * @loadFixture
     * @dataProvider dataProvider
     */
    public function testFill($mapping, $attributes)
    {

        $toObject = new Varien_Object($attributes);

        /** @var $mappings SchumacherFM_Anonygento_Model_Random_Mappings */
        $mappings = Mage::getModel('schumacherfm_anonygento/random_mappings')->getMapping($mapping);

        $entityAttributes = $mappings->getEntityAttributes();
        $this->assertJsonMatch(json_encode(array_keys($attributes)), $entityAttributes);

        $this->_fillModel = Mage::getSingleton('schumacherfm_anonygento/random_fill')->setData(array());
        $this->_fillModel->setToObj($toObject);
        $this->_fillModel->setMappings($mappings);
        $this->_fillModel->fill();

        $this->assertEquals(41, strlen($toObject->getData('password_hash_test')));
        $this->assertContains('lorem ipsum', $toObject->getData('text'), '', TRUE);
        $this->assertNotEquals('87.86.85.84', $toObject->getData('ip'));

    }

}