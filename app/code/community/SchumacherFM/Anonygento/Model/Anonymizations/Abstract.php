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
    /**
     * @var Zend_ProgressBar
     */
    protected $_progressBar = null;

    /**
     * @var SchumacherFM_Anonygento_Model_Random_Customer
     */
    protected $_randomCustomerModel = null;

//    protected $_unusedCustomerData = array();
//    protected $_anonymizedCustomerIds = array();
//    protected $_anonymizedCustomerAddressIds = array();
//    protected $_anonymizedOrderIds = array();
//    protected $_anonymizedOrderAddressIds = array();
//    protected $_anonymizedQuoteIds = array();
//    protected $_anonymizedQuoteAddressIds = array();
//    protected $_anonymizedNewsletterSubscriberIds = array();

    const MAX_FAKESTER_REQUEST_COUNT = 100;

    protected function _construct()
    {
        parent::_construct();
        $this->_randomCustomerModel = Mage::getModel('schumacherfm_anonygento/random_customer');
    }

    /**
     * executes and runs one anonymization process
     *
     * @return mixed
     */
    abstract public function run();

    /**
     * @param Zend_ProgressBar $bar
     */
    public function setProgressBar(Zend_ProgressBar $bar)
    {
        $this->_progressBar = $bar;
    }

    /**
     * @return null|Zend_ProgressBar
     */
    public function getProgressBar()
    {
        return $this->_progressBar;
    }

    /**
     * gets the last part of a class name
     * @return string
     */
    protected function _whatsMyName()
    {
        $class = explode('_', get_class($this)); // if not $this then
        return $class[count($class) - 1]; // return is 'Abstract' 8-)
    }

    /**
     * @param array $addAttributeToSelect
     * @param integer $isAnonymized
     *
     * @return Mage_Customer_Model_Resource_Customer_Collection
     */
    protected function _getCustomerCollection($addAttributeToSelect = array(), $isAnonymized = 0)
    {
        $collection = Mage::getModel('customer/customer')
            ->getCollection()
            ->addAttributeToSelect($addAttributeToSelect);
        /* @var $collection Mage_Customer_Model_Resource_Customer_Collection */

        $this->_collectionAddStaticAnonymized($collection, $isAnonymized);

        return $collection;
    }

    /**
     * copies the data from obj to another using a mapping array
     *
     * @param object  $fromObj
     * @param object  $toObj
     * @param array   $mappings key=>value
     *
     * @return bool
     */
    protected function _copyObjectData($fromObj, $toObj, $mappings = array())
    {
        if (count($mappings) === 0) {
            return FALSE;
        }

        foreach ($mappings as $key => $newKey) {
            $data = $fromObj->getData($key);
            $toObj->setData($newKey, $data);
        }

    }

    /**
     * @param object  $collection
     * @param integer $isAnonymized
     */
    protected function _collectionAddStaticAnonymized($collection, $isAnonymized = 0)
    {
        $collection->addStaticField('anonymized');
        $collection->addAttributeToFilter('anonymized', $isAnonymized);
    }

}