<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Random_Ip extends Varien_Object
{
    /**
     * this class tries to "anonymize" an ip address but keeps the relation
     */

    protected $_currentFactor = 0;
    protected $_maxLongIp = 0;

    protected function _construct()
    {
        parent::_construct();
        $this->_initFactor();
        $this->_maxLongIp = ip2long('255.255.255.255');
    }

    protected function _initFactor()
    {
        $this->_currentFactor = mt_rand(6, 32) - 2;
    }

    /**
     * @param string $origDataIp
     *
     * @return string
     */
    public function shuffleIp($origDataIp = null)
    {
        if ($origDataIp === null) {
            return null;
        }
        $long = $this->_calculate(ip2long($origDataIp));
        return long2ip($long);

    }

    /**
     * @param integer $origDataLongIp
     *
     * @return int
     */
    public function shuffleIpLong($origDataLongIp)
    {
        return $this->_calculate($origDataLongIp);
    }

    protected function _calculate($longIp)
    {
        $pow = pow(2, $this->_currentFactor);

        // @todo optimize random algo 8-)
        if ((mt_rand() % 2) == 0 && $longIp > $pow) {
            $long = $longIp - $pow;
        } else {
            $long = $longIp + $pow;
        }

        if ($long > $this->_maxLongIp || !$this->_isValidPublic(long2ip($long))) {
            $this->_initFactor();
            return $this->_calculate($longIp);
        }

        return $long;
    }

    public function getRandomValidPublicIp()
    {

        $ip = array(mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        $ip = implode('.', $ip);

        return $this->_isValidPublic($ip) ? $ip : $this->getRandomValidPublicIp();

    }

    protected function _isValidPublic($ip)
    {
        return !$this->_ipInRange($ip, '10.0.0.0', '10.255.255.255') &&
            !$this->_ipInRange($ip, '172.16.0.0', '172.31.255.255') &&
            !$this->_ipInRange($ip, '0.0.0.0', '0.255.255.255') &&
            !$this->_ipInRange($ip, '127.0.0.0', '127.255.255.255') &&
            !$this->_ipInRange($ip, '240.0.0.0', '255.255.255.254') &&
            !$this->_ipInRange($ip, '192.168.0.0', '192.168.255.255');
    }

    /**
     * @param string $ip
     * @param string $start
     * @param string $end
     *
     * @return boolean
     */
    protected function _ipInRange($ip, $start, $end)
    {
        $ip    = ip2long($ip);
        $start = ip2long($start);
        $end   = ip2long($end);

        return ($ip >= $start && $ip <= $end);
    }
}
