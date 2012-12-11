<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonygento extends Varien_Object
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

    public function anonymizeAll()
    {
        /** @var $customers Mage_Customer_Model_Resource_Customer_Collection */
        $customers = Mage::getModel('customer/customer')
            ->getCollection()
            ->addAttributeToSelect(array('prefix', 'firstname', 'lastname', 'suffix'));

        $this->_fetchRandomCustomerData($customers->getSize() * 2);

        $this->_anonymizeCustomers($customers);

        $this->_anonymizeRemainingNewsletterSubscribers();

        $this->_anonymizeRemainingOrders();
        $this->_anonymizeRemainingQuotes();

        $this->_anonymizeRemainingOrderAddresses();
        $this->_anonymizeRemainingQuoteAddresses();
    }

    /**
     * @param Mage_Customer_Model_Resource_Customer_Collection $customers
     */
    protected function _anonymizeCustomers($customers)
    {
        foreach ($customers as $customer) {

            $this->_anonymizeCustomer($customer);
        }
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     */
    protected function _anonymizeCustomer($customer)
    {
        $randomData = $this->_getRandomData();

        foreach ($this->_getCustomerMapping() as $customerKey => $randomDataKey) {
            if (!$customer->getData($customerKey)) {
                continue;
            }

            if (strlen($randomDataKey)) {
                $customer->setData($customerKey, $randomData[$randomDataKey]);
            } else {
                $customer->setData($customerKey, '');
            }
        }

        $customer->getResource()->save($customer);
        $this->_anonymizedCustomerIds[] = $customer->getId();

        /* @var $subscriber Mage_Newsletter_Model_Subscriber */
        $subscriber = Mage::getModel('newsletter/subscriber');
        $subscriber->loadByEmail($customer->getOrigData('email'));
        if ($subscriber->getId()) {
            $this->_anonymizeNewsletterSubscriber($subscriber, $randomData);
        }

        $this->_anonymizeQuotes($customer, $randomData);
        $this->_anonymizeOrders($customer, $randomData);
        $this->_anonymizeCustomerAddresses($customer, $randomData);
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param array $randomData
     */
    protected function _anonymizeOrders($customer, $randomData)
    {
        $orders = Mage::getModel('sales/order')
            ->getCollection()
            ->addFieldToFilter('customer_email', $customer->getOrigData('email'));

        foreach ($orders as $order) {
            $this->_anonymizeOrder($order, $randomData);
        }
    }

    /**
     *
     */
    protected function _anonymizeRemainingOrders()
    {
        $orders = Mage::getModel('sales/order')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('nin' => $this->_anonymizedOrderIds));

        foreach ($orders as $order) {

            /** @var $order Mage_Sales_Model_Order */
            $randomData = $this->_getRandomData();
            $this->_anonymizeOrder($order, $randomData);

            foreach ($order->getAddressesCollection() as $orderAddress) {

                /** @var $orderAddress Mage_Sales_Model_Order_Address */
                if ($orderAddress->getCustomerFirstname() == $order->getOrigData('customer_firstname')
                    && $orderAddress->getCustomerLastname() == $order->getOrigData('customer_lastname')
                ) {

                    $newRandomData = $randomData;
                } else {
                    $newRandomData = $this->_getRandomData();
                }

                $this->_anonymizeOrderAddress($orderAddress, $newRandomData);
            }
        }
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param array $randomData
     */
    protected function _anonymizeOrder($order, $randomData)
    {
        /** @var $order Mage_Sales_Model_Order */
        foreach ($this->_getOrderMapping() as $orderKey => $randomDataKey) {
            if (!$order->getData($orderKey)) {
                continue;
            }

            if (strlen($randomDataKey)) {
                $order->setData($orderKey, $randomData[$randomDataKey]);
            } else {
                $order->setData($orderKey, '');
            }
        }

        $order->getResource()->save($order);
        $this->_anonymizedOrderIds[] = $order->getId();

        /* @var $quote Mage_Sales_Model_Quote */
        $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
        if ($quote->getId()) {
            $this->_anonymizeQuote($quote, $randomData);
        }
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param array $randomData
     */
    protected function _anonymizeQuotes($customer, $randomData)
    {
        $quotes = Mage::getModel('sales/quote')
            ->getCollection()
            ->addFieldToFilter('customer_id', $customer->getId());

        foreach ($quotes as $quote) {
            $this->_anonymizeQuote($quote, $randomData);
        }
    }

    /**
     *
     */
    protected function _anonymizeRemainingQuotes()
    {
        $quotes = Mage::getModel('sales/quote')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('nin' => $this->_anonymizedQuoteIds));

        foreach ($quotes as $quote) {

            /** @var $quote Mage_Sales_Model_Quote */
            $randomData = $this->_getRandomData();
            $this->_anonymizeQuote($quote, $randomData);

            foreach ($quote->getAddressesCollection() as $quoteAddress) {

                /** @var $quoteAddress Mage_Sales_Model_Quote_Address */
                if ($quoteAddress->getCustomerFirstname() == $quote->getOrigData('customer_firstname')
                    && $quoteAddress->getCustomerLastname() == $quote->getOrigData('customer_lastname')
                ) {

                    $newRandomData = $randomData;
                } else {
                    $newRandomData = $this->_getRandomData();
                }

                $this->_anonymizeQuoteAddress($quoteAddress, $newRandomData);
            }
        }
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @param array $randomData
     */
    protected function _anonymizeQuote($quote, $randomData)
    {
        /** @var $quote Mage_Sales_Model_Quote */
        foreach ($this->_getQuoteMapping() as $quoteKey => $randomDataKey) {
            if (!$quote->getData($quoteKey)) {
                continue;
            }

            if (strlen($randomDataKey)) {
                $quote->setData($quoteKey, $randomData[$randomDataKey]);
            } else {
                $quote->setData($quoteKey, '');
            }
        }

        $quote->getResource()->save($quote);
        $this->_anonymizedQuoteIds[] = $quote->getId();
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param array $randomData
     */
    protected function _anonymizeCustomerAddresses($customer, $randomData)
    {
        $customerAddresses = $customer->getAddressesCollection()
            ->addAttributeToSelect(array('prefix', 'firstname', 'lastname', 'suffix'));

        foreach ($customerAddresses as $customerAddress) {

            /** @var $customerAddress Mage_Customer_Model_Address */
            if ($customerAddress->getFirstname() == $customer->getOrigData('firstname')
                && $customerAddress->getLastname() == $customer->getOrigData('lastname')
            ) {

                $newRandomData = $randomData;
            } else {
                $newRandomData = $this->_getRandomData();
            }

            $this->_anonymizeCustomerAddress($customerAddress, $newRandomData);
        }
    }

    /**
     * @param Mage_Customer_Model_Address $customerAddress
     * @param array $randomData
     */
    protected function _anonymizeCustomerAddress($customerAddress, $randomData)
    {
        foreach ($this->_getAddressMapping() as $addressKey => $randomDataKey) {
            if (!$customerAddress->getData($addressKey)) {
                continue;
            }

            if (strlen($randomDataKey)) {
                $customerAddress->setData($addressKey, $randomData[$randomDataKey]);
            } else {
                $customerAddress->setData($addressKey, '');
            }
        }

        $customerAddress->getResource()->save($customerAddress);
        $this->_anonymizedCustomerAddressIds[] = $customerAddress->getId();

        $this->_anonymizeQuoteAddresses($customerAddress, $randomData);
        $this->_anonymizeOrderAddresses($customerAddress, $randomData);
    }

    /**
     * @param Mage_Customer_Model_Address $customerAddress
     * @param array $randomData
     */
    protected function _anonymizeQuoteAddresses($customerAddress, $randomData)
    {
        $quoteAddresses = Mage::getModel('sales/quote_address')
            ->getCollection()
            ->addFieldToFilter('customer_address_id', $customerAddress->getId());

        foreach ($quoteAddresses as $quoteAddress) {
            $this->_anonymizeQuoteAddress($quoteAddress, $randomData);
        }
    }

    /**
     *
     */
    protected function _anonymizeRemainingQuoteAddresses()
    {
        $quoteAddresses = Mage::getModel('sales/quote_address')
            ->getCollection()
            ->addFieldToFilter('address_id', array('nin' => $this->_anonymizedQuoteAddressIds));

        foreach ($quoteAddresses as $quoteAddress) {

            /** @var $quoteAddress Mage_Sales_Model_Quote_Address */
            $randomData = $this->_getRandomData();
            $this->_anonymizeQuoteAddress($quoteAddress, $randomData);
        }
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $quoteAddress
     * @param array $randomData
     */
    protected function _anonymizeQuoteAddress($quoteAddress, $randomData)
    {
        foreach ($this->_getAddressMapping() as $addressKey => $randomDataKey) {
            if (!$quoteAddress->getData($addressKey)) {
                continue;
            }

            if (strlen($randomDataKey)) {
                $quoteAddress->setData($addressKey, $randomData[$randomDataKey]);
            } else {
                $quoteAddress->setData($addressKey, '');
            }
        }

        $quoteAddress->getResource()->save($quoteAddress);
        $this->_anonymizedQuoteAddressIds[] = $quoteAddress->getId();
    }


    /**
     * @param Mage_Customer_Model_Address $customerAddress
     * @param array $randomData
     */
    protected function _anonymizeOrderAddresses($customerAddress, $randomData)
    {
        $orderAddresses = Mage::getModel('sales/order_address')
            ->getCollection()
            ->addFieldToFilter('customer_address_id', $customerAddress->getId());

        foreach ($orderAddresses as $orderAddress) {
            $this->_anonymizeOrderAddress($orderAddress, $randomData);
        }
    }

    /**
     *
     */
    protected function _anonymizeRemainingOrderAddresses()
    {
        $orderAddresses = Mage::getModel('sales/order_address')
            ->getCollection()
            ->addFieldToFilter('entity_id', array('nin' => $this->_anonymizedOrderAddressIds));

        foreach ($orderAddresses as $orderAddress) {

            /** @var $orderAddress Mage_Sales_Model_Order_Address */
            $randomData = $this->_getRandomData();
            $this->_anonymizeOrderAddress($orderAddress, $randomData);
        }
    }

    /**
     * @param Mage_Sales_Model_Order_Address $orderAddress
     * @param array $randomData
     */
    protected function _anonymizeOrderAddress($orderAddress, $randomData)
    {
        foreach ($this->_getAddressMapping() as $addressKey => $randomDataKey) {
            if (!$orderAddress->getData($addressKey)) {
                continue;
            }

            if (strlen($randomDataKey)) {
                $orderAddress->setData($addressKey, $randomData[$randomDataKey]);
            } else {
                $orderAddress->setData($addressKey, '');
            }
        }

        $orderAddress->getResource()->save($orderAddress);
        $this->_anonymizedOrderAddressIds[] = $orderAddress->getId();

        /* @var $quoteAddress Mage_Sales_Model_Quote_Address */
        $quoteAddress = Mage::getModel('sales/quote_address')->load($orderAddress->getQuoteAddressId());
        if ($quoteAddress->getId()) {
            $this->_anonymizeQuoteAddress($quoteAddress, $randomData);
        }
    }

    /**
     *
     */
    protected function _anonymizeRemainingNewsletterSubscribers()
    {
        $newsletterSubscribers = Mage::getModel('newsletter/subscriber')
            ->getCollection()
            ->addFieldToFilter('subscriber_id', array('nin' => $this->_anonymizedNewsletterSubscriberIds));

        foreach ($newsletterSubscribers as $newsletterSubscriber) {

            /** @var $newsletterSubscriber Mage_Newsletter_Model_Subscriber */
            $randomData = $this->_getRandomData();
            $this->_anonymizeNewsletterSubscriber($newsletterSubscriber, $randomData);
        }
    }

    /**
     * @param Mage_Newsletter_Model_Subscriber $subscriber
     * @param array $randomData
     */
    protected function _anonymizeNewsletterSubscriber($subscriber, $randomData)
    {
        $subscriber->setData('subscriber_email', $randomData['email']);
        $subscriber->getResource()->save($subscriber);

        $this->_anonymizedNewsletterSubscriberIds[] = $subscriber->getId();
    }

    /**
     * @return array
     */
    protected function _getCustomerMapping()
    {
        return array(
            'prefix' => 'prefix',
            'firstname' => 'first_name',
            'middlename' => '',
            'lastname' => 'last_name',
            'suffix' => 'suffix',
            'email' => 'email',
        );
    }

    /**
     * @return array
     */
    protected function _getQuoteMapping()
    {
        return array(
            'customer_prefix' => 'prefix',
            'customer_firstname' => 'first_name',
            'customer_middlename' => '',
            'customer_lastname' => 'last_name',
            'customer_suffix' => 'suffix',
            'customer_email' => 'email',
            'customer_taxvat' => '',
            'remote_ip' => 'ip_v4_address',
        );
    }

    /**
     * @return array
     */
    protected function _getOrderMapping()
    {
        return array(
            'customer_prefix' => 'prefix',
            'customer_firstname' => 'first_name',
            'customer_middlename' => '',
            'customer_lastname' => 'last_name',
            'customer_suffix' => 'suffix',
            'customer_email' => 'email',
            'customer_taxvat' => '',
            'remote_ip' => 'ip_v4_address',
        );
    }

    /**
     * @return array
     */
    protected function _getAddressMapping()
    {
        return array(
            'prefix' => 'prefix',
            'firstname' => 'first_name',
            'middlename' => '',
            'lastname' => 'last_name',
            'suffix' => 'suffix',
            'company' => 'bs',
            'street' => 'street_address',
            'telephone' => 'zip_code',
            'fax' => '',
            'vat_id' => '',
            'email' => 'email',
        );
    }

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