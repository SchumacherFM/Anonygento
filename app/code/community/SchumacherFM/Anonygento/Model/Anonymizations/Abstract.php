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

    protected $_unusedCustomerData = array();
    protected $_anonymizedCustomerIds = array();
    protected $_anonymizedCustomerAddressIds = array();
    protected $_anonymizedOrderIds = array();
    protected $_anonymizedOrderAddressIds = array();
    protected $_anonymizedQuoteIds = array();
    protected $_anonymizedQuoteAddressIds = array();
    protected $_anonymizedNewsletterSubscriberIds = array();

    const MAX_FAKESTER_REQUEST_COUNT = 100;


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
        $class = explode('_', get_class($this));    // if not $this then
        return $class[count($class) - 1];           // return is 'Abstract' 8-)
    }
}