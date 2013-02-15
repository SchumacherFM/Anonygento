<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Test_Model_Random_Ip_Ip extends EcomDev_PHPUnit_Test_Case
{

    /**
     * @var SchumacherFM_Anonygento_Model_Random_Ip
     */
    protected $_ip = null;

    protected function setUp()
    {
        $this->_ip = Mage::getModel('schumacherfm_anonygento/random_ip');
    }

    /**
     * @dataProvider dataProvider
     */
    public function testShuffleIp($ipAddresses)
    {
        foreach ($ipAddresses as $ip) {
            $shuffled = $this->_ip->shuffleIp($ip);
            $this->assertNotEquals($ip, $shuffled);
        }
    }

    public function testGetRandomValidPublicIp()
    {
        $publicIp = $this->_ip->getRandomValidPublicIp();
        $this->assertTrue($this->_ip->isValidPublicIp($publicIp));
    }

}