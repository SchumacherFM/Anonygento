<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
abstract class SchumacherFM_Anonygento_Model_Random_AbstractWeird extends Varien_Object
{
    /**
     * @todo these three constants should be defined somewhere else
     *       maybe via backend settings
     */

    /**
     * path to csv data dir, no slash at the end
     */
    const DATA_PATH = 'app/code/community/SchumacherFM/Anonygento/data';

    /**
     * file extension
     */
    const DATA_FILE_EXTENSION = 'csv';

    /**
     * due to not being able to provide data files to the public
     * you can use the e.b. en_US-internal folder
     * data files are: firstnames, lastnames, streets, mail addresses ...
     */
    const DATA_INTERNAL = '-internalXXX';

    /**
     * @var string
     */
    protected $_locale = '';

    protected $_firstnameMale = array();

    protected $_firstnameFemale = array();

    protected $_lastname = array();

    protected $_email = array();
    protected $_prefixMale = array();
    protected $_prefixFemale = array();

    protected $_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected function _construct()
    {
        parent::_construct();

        $this->_locale = Mage::helper('schumacherfm_anonygento')->getLocaleForData();

        $this->setCustomerPrefix(mt_rand() % 2);

        $this->_firstnameFemale = $this->_loadFile('FirstnameFemale');
        $this->_firstnameMale   = $this->_loadFile('FirstnameMale');
        $this->_lastname        = $this->_loadFile('Lastname');
        $this->_email           = $this->_loadFile('Email');
        $this->_prefixMale      = $this->_loadFile('PrefixMale');
        $this->_prefixFemale    = $this->_loadFile('PrefixFemale');

    }

    protected function _getCustomerPrefixString()
    {
        $p = $this->getCustomerPrefix() === 0
            ? $this->_prefixFemale[mt_rand() % count($this->_prefixFemale)]
            : $this->_prefixMale[mt_rand() % count($this->_prefixMale)];

        return $p;
    }

    protected function _getCustomerFirstName()
    {
        $return = $this->getCustomerPrefix() === 0
            ? $this->_firstnameFemale[mt_rand() % count($this->_firstnameFemale)]
            : $this->_firstnameMale[mt_rand() % count($this->_firstnameMale)];
        return $return;
    }

    protected function _getCustomerLastName()
    {
        return $this->_lastname[mt_rand() % count($this->_lastname)];

    }

    protected function _getRandEmail()
    {

        $name = $this->_currentCustomer->getFirstname() . '.' .
            $this->_currentCustomer->getLastname() . '-' . uniqid();

        $email = Mage::helper('catalog/product_url')->format($name) . '@' . $this->_email[mt_rand() % count($this->_email)];
        $email = strtolower($email);

        $this->_currentCustomer->setEmail($email);
    }

    public function getEmailWeird()
    {

        $name = $this->_getRandomString() . '.' . $this->_getRandomString(8) . '-' . uniqid();

        // @todo abstract access to random mail host
        $email = $name . '@' . $this->_email[mt_rand() % count($this->_email)];
        return strtolower($email);
    }

    /**
     * @param int  $length
     * @param bool $toLower
     *
     * @return string
     */
    protected function _getRandomString($length = 7, $toLower = FALSE)
    {

        $s = '';
        $i = 0;
        while ($i < $length) {
            $s = $s . substr($this->_chars, (mt_rand() % strlen($this->_chars)), 1);
            $i++;
        }
        return $toLower ? strtolower($s) : $s;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function getMnemonicName($length = 8)
    {

        $conso    = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z');
        $consoC   = count($conso) - 1;
        $vocal    = array('a', 'e', 'i', 'o', 'u');
        $vocalC   = count($vocal) - 1;
        $password = '';
        srand((double)microtime() * 1000000);
        for ($f = 1; $f <= $length; $f++) {
            $password .= $conso[rand(0, $consoC)];
            $password .= $vocal[rand(0, $vocalC)];
        }
        return ucfirst($password);

    }

    /**
     * @param int $size
     *
     * @return array
     */
    public function getRandomArray($size = 10)
    {
        $return = array();
        for ($i = 0; $i < $size; ++$i) {
            $return[] = $this->getMnemonicName(mt_rand(2, 6));
        }
        return $return;
    }

    /**
     * loads a csv file and checks if the internal dir exists if so loads the data
     * from that directory
     *
     * @param string $name
     *
     * @return array
     */
    protected function _loadFile($name)
    {
        $baseDir = Mage::getBaseDir();

        $csvFileComponents = array(
            $baseDir,
            self::DATA_PATH,
            $this->_locale . self::DATA_INTERNAL,
            $name . '.' . self::DATA_FILE_EXTENSION
        );

        $csvFile = implode(DS, $csvFileComponents);

        if (!file_exists($csvFile)) {
            $csvFileComponents[2] = $this->_locale;
            $csvFile              = implode(DS, $csvFileComponents);
        }

        if (!is_file($csvFile)) {
            Mage::throwException('csv file for data (' . $name . ') not found!' . PHP_EOL . $csvFile . PHP_EOL);
        }

        $file = file($csvFile);
        if (count($file) > 3) {
            return array_map('trim', $file);
        } else {
            return $this->getRandomArray(mt_rand(333, 888));
        }
    }
}
