<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
abstract class SchumacherFM_Anonygento_Model_Anonymizations_Abstract extends Varien_Object
{
    protected $_unusedCustomerData = array();
    protected $_anonymizedCustomerIds = array();
    protected $_anonymizedCustomerAddressIds = array();
    protected $_anonymizedOrderIds = array();
    protected $_anonymizedOrderAddressIds = array();
    protected $_anonymizedQuoteIds = array();
    protected $_anonymizedQuoteAddressIds = array();
    protected $_anonymizedNewsletterSubscriberIds = array();

    const MAX_FAKESTER_REQUEST_COUNT = 100;

//    public function anonymizeAll()
//    {
//        /** @var $customers Mage_Customer_Model_Resource_Customer_Collection */
//        $customers = Mage::getModel('customer/customer')
//            ->getCollection()
//            ->addAttributeToSelect(array('prefix', 'firstname', 'lastname', 'suffix'));
//
//        $this->_fetchRandomCustomerData($customers->getSize() * 2);
//
//        $this->_anonymizeCustomers($customers);
//
//        $this->_anonymizeRemainingNewsletterSubscribers();
//
//        $this->_anonymizeRemainingOrders();
//        $this->_anonymizeRemainingQuotes();
//
//        $this->_anonymizeRemainingOrderAddresses();
//        $this->_anonymizeRemainingQuoteAddresses();
//    }

    /**
     * executes and runs one anonymization process
     *
     * @return mixed
     */
    abstract public function run();


    /**
     * @return array
     */
    protected function _getRandomData()
    {
        $randomData = array_pop($this->_unusedCustomerData);
        if (is_null($randomData)) {
            $this->_fetchRandomCustomerData(100);
            $randomData = array_pop($this->_unusedCustomerData);
        }
        return $randomData;
    }

    /**
     * @param int $count
     * @return array
     */
    protected function _fetchRandomCustomerData($count)
    {
        $count = min($count, self::MAX_FAKESTER_REQUEST_COUNT);
        $url = 'http://fakester.biz/json?n=' . $count;

        $json = @file_get_contents($url);
        if ($json === false) {
            $json = Mage::helper('schumacherfm_anonygento')->getOfflineFakester();
        }
        $this->_unusedCustomerData = Zend_Json::decode($json);

        /*
         * Fakester return these fields for customers:
         *
         *   [name] => Johnson, Kreiger and Jenkins
         *   [first_name] => Citlalli
         *   [last_name] => Gorczany
         *   [prefix] => Dr.
         *   [suffix] => Inc
         *   [city] => Loisshire
         *   [city_prefix] => Lake
         *   [city_suffix] => bury
         *   [country] => United Arab Emirates
         *   [secondary_address] => Suite 720
         *   [state] => Wyoming
         *   [state_abbr] => OK
         *   [street_address] => 61204 Lang Garden
         *   [street_name] => Lakin Unions
         *   [street_suffix] => Dam
         *   [zip_code] => 38126-1906
         *   [bs] => unleash world-class technologies
         *   [catch_phrase] => Vision-oriented grid-enabled throughput
         *   [domain_name] => mayer.org
         *   [domain_suffix] => info
         *   [domain_word] => hoppe
         *   [email] => jefferey@baileysimonis.name
         *   [free_email] => emmitt@hotmail.com
         *   [ip_v4_address] => 163.49.36.30
         *   [ip_v6_address] => 61b4:5b6:7d1d:db11:ab29:e003:eb4:161f
         *   [user_name] => meghan
         *
         */

        return $this->_unusedCustomerData;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return array(
            'Customers' => sizeof($this->_anonymizedCustomerIds),
            'Customer Addresses' => sizeof($this->_anonymizedCustomerAddressIds),
            'Orders' => sizeof($this->_anonymizedOrderIds),
            'Order Addresses' => sizeof($this->_anonymizedOrderAddressIds),
            'Quotes' => sizeof($this->_anonymizedQuoteIds),
            'Quote Addresses' => sizeof($this->_anonymizedQuoteAddressIds),
            'Newsletter Subscribers' => sizeof($this->_anonymizedNewsletterSubscriberIds),
        );
    }
}