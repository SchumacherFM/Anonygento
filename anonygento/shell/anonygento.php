<?php

die('old script! do not run!');

/**
 * Anonygento is a script which anonymizes your whole Magento Shop.
 *
 * This is useful if you want to send the database to a developer
 * or to share anybody else.
 *
 * @author Cyrill @SchumacherFM
 * @date 12/9/12
 * @category    Anonygento
 * @package     Core
 * @copyright   Copyright (c) 2012 Cyrill Schumacher
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
require_once 'abstract.php';
error_reporting(E_ALL);
class Anonygento extends Mage_Shell_Abstract
{

    protected $_dryRun = false;

    protected $_topLevelDomains = array('com', 'net', 'org', 'de', 'ch', 'at', 'li', 'fr', 'uk', 'it');
    protected $_mailHoster = array('gmail', 'outlook', 'web', 'hotmail', 't-online', 'gmx', 'mail');
    protected $_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected $_namesLength = array();

    public function __construct()
    {
        parent::__construct();

        $this->_namesLength['firstNameFemale'] = count($this->_firstNameFemale);
        $this->_namesLength['firstNameMale'] = count($this->_firstNameMale);
        $this->_namesLength['lastNames'] = count($this->_lastNames);
    }


    public function run()
    {
        $this->_anonCustomer();
    }

    protected function _getProgressBar($count)
    {
        $pbAdapter = new Zend_ProgressBar_Adapter_Console(
            array('elements' =>
            array(Zend_ProgressBar_Adapter_Console::ELEMENT_PERCENT,
                Zend_ProgressBar_Adapter_Console::ELEMENT_BAR,
                Zend_ProgressBar_Adapter_Console::ELEMENT_ETA))
        );

        return new Zend_ProgressBar($pbAdapter, 0, $count);
    }

    protected function _anonCustomer()
    {
        $this->_initTimer();
        $customerCollection = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToSelect('*');
        $count = $customerCollection->count();

        $progressBar = $this->_getProgressBar($count);

        echo 'Anonymizing customers: ' . PHP_EOL;
        $i = 0;
        foreach ($customerCollection as $customer) {
            $this->_anonCustomerEntity($customer);
            $progressBar->update($i);
            $i++;
        }
        $progressBar->finish();
        echo 'Duration: ' . $this->_getTimerDurationHR() . 's' . PHP_EOL . PHP_EOL;
    }


    protected function _anonCustomerEntity(Mage_Customer_Model_Customer $customer)
    {
        $this->_setCustomerPrefix();
        $customer
            ->setPrefix($this->_getCustomerPrefixString())
            ->setFirstname($this->_getCustomerFirstName())
            ->setLastname($this->_getCustomerLastName())
            ->setDob($this->_getCustomerDob())
            ->setPasswordHash($this->_getRandomString(11));

        $customer->setEmail($this->_getRandEmail($customer));

        if ($this->_dryRun === false) {
            $customer->save();
        }

        $addressCollection = $customer->getAddressesCollection();


        $i = 0;
        foreach ($addressCollection as $address) {

            $address
                ->setPrefix($i === 0 ? $customer->getPrefix() : $this->_getCustomerPrefixString())
                ->setFirstname($i === 0 ? $customer->getFirstname() : $this->_getCustomerFirstName())
                ->setLastname($i === 0 ? $customer->getLastname() : $this->_getCustomerLastName())
                ->setTelephone($this->_getCustomerTelephone())
                ->setStreet($this->_getCustomerStreet());

            if ($address->getCompany()) {
                $address->setCompany($this->_getRandomString() . ' Ltd.');
            }

//            Zend_Debug::dump($address->getData());

            if ($this->_dryRun === false) {
                $address->save();
            }
            $i++;
        }


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

    protected $_customerPrefix = 0;

    protected function _setCustomerPrefix()
    {
        $this->_customerPrefix = (mt_rand() % 2);
    }

    protected function _getCustomerPrefix()
    {
        return $this->_customerPrefix;
    }

    protected function _getCustomerPrefixString()
    {
        $prefix = array('Ms', 'Mr');
        return $prefix[$this->_getCustomerPrefix()];
    }

    protected function _getCustomerFirstName()
    {
        $return = $this->_getCustomerPrefix() === 0
            ? $this->_firstNameFemale[mt_rand() % $this->_namesLength['firstNameFemale']]
            : $this->_firstNameMale[mt_rand() % $this->_namesLength['firstNameMale']];
        return ucfirst($return);
    }

    protected function _getCustomerLastName()
    {
        $return = $this->_lastNames[mt_rand() % $this->_namesLength['lastNames']];
        return ucfirst($return);
    }

    protected function _getRandomString($length = 7, $toLower = false)
    {

        $s = '';
        $i = 0;
        while ($i < $length) {
            $s = $s . substr($this->_chars, (mt_rand() % strlen($this->_chars)), 1);
            $i++;
        }
        return $toLower ? strtolower($s) : $s;
    }

    protected function _getRandEmail(Mage_Customer_Model_Customer $customer)
    {

        $name = $customer->getFirstname() . '.' . $customer->getLastname() . '-' . $customer->getEntityId();

        return strtolower($name) . '@' .
            $this->_mailHoster[mt_rand() % count($this->_mailHoster)] . '.' .
            $this->_topLevelDomains[mt_rand() % count($this->_topLevelDomains)];
    }

    protected $_timer = 0;

    protected function  _initTimer()
    {
        $this->_timer = microtime(true);
    }

    protected function _getTimerDuration()
    {
        return microtime(true) - $this->_timer;
    }

    protected function _getTimerDurationHR()
    {
        return sprintf('%.2f', $this->_getTimerDuration());
    }

    protected $_firstNameMale = array('aaron',
        'abdul',
        'abe',
        'zane');

    protected $_firstNameFemale = array('mary',
        'patricia',
        'linda',
        'celina');

    protected $_lastNames = array('smith',
        'johnson',
        'vang');
}

$ma = new Anonygento();
$ma->run();

