<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';
error_reporting(E_ALL);

/**
 * Magento Anonygento Script
 *
 * @category    SchumacherFM_Anonygento_Anonygento
 * @package     shell
 * @author      Cyrill at Schumacher dot fm
 */
class Mage_Shell_Anonygento extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {

        $_execCollection = $this->_getAnonymizationCollection();

Zend_Debug::dump($_execCollection);
exit;

        foreach ($_execCollection as $anonExec) {
            $anonModel = $this->_getModel($anonExec->getValue());

            if ($anonModel) {
                $this->_shellOut('Running ' . $anonExec->getLabel());
                $progessBar = $this->_getProgressBar($anonExec->getUnanonymized());
                $anonModel->setProgressBar($progessBar);
                $anonModel->run();
            } else {
                $this->_shellOut('Model ' . $anonExec->getValue() . ' not found!');
            }

        }
    }

    /**
     * @param string $string
     */
    protected function _shellOut($string = '')
    {
        echo $string . PHP_EOL;
    }

    /**
     * @param $type
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _getModel($type)
    {
        return Mage::getModel('schumacherfm_anonygento/anonymizations_' . $type);
    }

    /**
     * @return object
     */
    protected function _getAnonymizationCollection()
    {
        return Mage::getModel('schumacherfm_anonygento/options_anonymizations')->getCollection();
    }

    /**
     * @param integer $count
     * @return Zend_ProgressBar
     */
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


    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return 'Usage:  php -f anonygento.php' . PHP_EOL . PHP_EOL;
    }
}

$shell = new Mage_Shell_Anonygento($argv);
$shell->run();
