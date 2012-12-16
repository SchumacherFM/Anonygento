<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Random_Customer extends Varien_Object
{
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
    const DATA_INTERNAL = '-internal';

    /**
     * @var string
     */
    protected $_locale = '';

    protected $_firstnameMale = array();

    protected $_firstnameFemale = array();

    protected $_lastname = array();

    protected $_email = array();

    protected $_street = array();

    protected $_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected $_namesLength = array();

    /**
     * @var Varien_Object
     */
    protected $_currentCustomer = NULL;

    public function _construct()
    {
        parent::_construct();

        $this->_locale = Mage::helper('schumacherfm_anonygento')->getLocaleForData();

        $this->_firstnameFemale = $this->_loadFile('FirstnameFemale');
        $this->_firstnameMale   = $this->_loadFile('FirstnameMale');
        $this->_lastname        = $this->_loadFile('Lastname');
        $this->_email           = $this->_loadFile('Email');
        $this->_street          = $this->_loadFile('Street');

//        $this->_namesLength['firstnameFemale'] = count($this->_firstnameFemale);
//        $this->_namesLength['firstnameMale']   = count($this->_firstnameMale);
//        $this->_namesLength['lastnames']       = count($this->_lastname);
//        $this->_namesLength['street']          = count($this->_street);
//        $this->_namesLength['email']           = count($this->_email);

    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    public function getCustomer(Mage_Customer_Model_Customer $customer = null)
    {

        $this->setCustomerPrefix(mt_rand() % 2);

        if ($customer === null) {
            $this->_currentCustomer = new Varien_Object();
            $this->_currentCustomer->setEntityId(mt_rand());
        } else {
            $this->_currentCustomer = $customer;
        }

        $this->_currentCustomer->setPrefix($this->_getCustomerPrefixString());
        $this->_currentCustomer->setFirstname($this->_getCustomerFirstName());
        $this->_currentCustomer->setMiddlename($this->_getCustomerFirstName());
        $this->_currentCustomer->setLastname($this->_getCustomerLastName());
        $this->_currentCustomer->setSuffix('');
        $this->_currentCustomer->setDob($this->_getCustomerDob());

//            (
//            'taxvat',
//            'remote_ip',
//            'company'   => 'bs',
//            'street'    => 'street_address',
//            'telephone' => 'zip_code',
//            'fax'       => '',

        $this->_getRandEmail();

        return $this->_currentCustomer;
    }

    protected function _getCustomerStreet()
    {
        return $this->_getRandomString(11) . ' ' . mt_rand(1, 100);
    }

    protected function _getCustomerTelephone()
    {
        return mt_rand(100000, 999999);
    }

    protected function _getCustomerDob()
    {
        return date('Y-m-d H:i:s', mt_rand(0, time() - (3600 * 24 * 360 * 18)));
    }

    protected function _getCustomerPrefixString()
    {
        $prefix = array('Frau', 'Herr');
        return $prefix[$this->getCustomerPrefix()];
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
            $this->_currentCustomer->getLastname() . '-' . $this->_currentCustomer->getEntityId();

        $email = strtolower($name) . '@' . $this->_email[mt_rand() % count($this->_email)];

        // @todo remove german umlauts with magentos catalog url helper func

        $this->_currentCustomer->setEmail($email);
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
            // @todo if not found then use random data string
            die('csv file for data (' . $name . ') not found!' . PHP_EOL . $csvFile . PHP_EOL);
        }

        return array_map('trim', file($csvFile));
    }

}
