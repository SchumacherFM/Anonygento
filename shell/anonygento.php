<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */

require_once 'abstract.php';

class Mage_Shell_Anonygento extends Mage_Shell_Abstract
{

    private $_devMode = FALSE;

    /**
     * @var SchumacherFM_Anonygento_Model_Console_Console
     */
    private $_console = null;

    /**
     * @var SchumacherFM\Anonygento\Model\Zend\Console\Adapter\Posix
     */
    private $_consoleInstance = null;

    /**
     * @var Zend_Console_Getopt
     */
    private $_consoleGetOpt = null;

    protected function _construct()
    {
        /**
         * e.g. setting from .bash_profile
         */
        $this->_devMode = (isset($_SERVER['ANONYGENTO_DEV']) && $_SERVER['ANONYGENTO_DEV'] === 'true');

        // register Zend Framework 2 autoloader
        Mage::getModel('schumacherfm_anonygento/autoload_zf2')->register();
        $this->_console         = Mage::getModel('schumacherfm_anonygento/console_console');
        $this->_consoleInstance = $this->_console->getInstance();
        $this->_flushCaches();
        /**
         * stat is also a method
         */
        $this->_consoleGetOpt = new Zend_Console_Getopt(array(
            'stat'             => 'Print statistic summary',
            'randomCustomer-i' => 'Prints a random customer with optional customer entity id',
            'memoryLimit=i'    => 'Sets the PHP memory limit to a new value. Use e.g. 384 for 384M',
            'runAnonymization' => 'Runs the anonymization process. Additionally used for memoryLimit',
        ));

    }

    public function run()
    {
        try {
            $this->_consoleGetOpt->parse();
            $options = $this->_consoleGetOpt->getOptions();
        } catch (Zend_Console_Getopt_Exception $e) {
            $this->_consoleInstance->writeLine($e->getUsageMessage());
            exit;
        }

        if (count($options) === 0) {
            $options = array('runAnonymization');
        }

        foreach ($options as $method) {

            $argument = $this->_consoleGetOpt->getOption($method);

            $method = '_' . $method;

            if (method_exists($this, $method)) {
                $this->$method($argument);
            }
        }
    }

    protected function _flushCaches()
    {
        Mage::app()->getCacheInstance()->flush();
        Mage::app()->cleanCache();
    }

    public function __destruct()
    {
        $this->_flushCaches();
        Varien_Profiler::stop('Anonygento');
        $duration = Varien_Profiler::fetch('Anonygento', 'sum');

        if ($duration > 0) {
            $this->_consoleInstance->writeLine('Runs for ' . sprintf('%.2f', $duration) .
                ' secs or ' . sprintf('%.2f', $duration / 60) . ' min ');
        }
    }

    /**
     * @param integer $limit
     */
    protected function _memoryLimit($limit)
    {
        $limit = (int)$limit;

        ini_set('memory_limit', $limit . 'M');
    }

    /**
     * executable method
     * @return Varien_Data_Collection
     */
    protected function _stat()
    {
        $this->_consoleInstance->writeLine('Memory limit: ' . ini_get('memory_limit'));
        $_execCollection = $this->_console->getAnonymizationCollection();
        echo $this->_console->printInfoTable($_execCollection);
        return $_execCollection;
    }

    /**
     * executable method
     */
    protected function _runAnonymization()
    {
        $userResult = $isAdminUser = FALSE;

        if ($this->_devMode !== TRUE) {
            $prompt = $this->_console->getModelZf2('console_prompt_confirm');
            $prompt->setPromptText('Anonymize this Magento installation? [y/n]');
            $userResult  = $prompt->show();
            $isAdminUser = $userResult ? $this->_console->isAdminUser() : FALSE;
        }

        if (($userResult && $isAdminUser) || $this->_devMode === TRUE) {
            $this->_runAnonymizationReal();
        } else {
            $this->_consoleInstance->writeLine('Nothing done!', SchumacherFM_Anonygento_Model_Console_Color::GREEN);
        }
    }

    private function _runAnonymizationReal()
    {
        Varien_Profiler::enable();
        Varien_Profiler::start('Anonygento');

        $_execCollection = $this->_stat();

        foreach ($_execCollection as $anonExec) {
            $anonModel = $this->_console->getModel($anonExec->getValue());

            $reCalc = $this->_console->reCalcUnAnonymized($anonExec->getModel());

            if ($anonModel) {
                $this->_consoleInstance->writeLine(
                    'Working on ' . $anonExec->getLabel() . ': ' . $reCalc . '/' . $anonExec->getAnonymized(),
                    SchumacherFM_Anonygento_Model_Console_Color::MAGENTA
                );

                if ($reCalc > 0 || $this->_devMode === TRUE) {
                    $progessBar = $this->_console->getProgressBar($reCalc);
                    $anonModel->setProgressBar($progessBar);

                    $anonModel->run();
                    $anonModel = null;

                }
            } else {
                $this->_consoleInstance->writeLine('Model ' . $anonExec->getValue() . ' not found or not necessary!',
                    SchumacherFM_Anonygento_Model_Console_Color::LIGHT_RED);
            }

        }

    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return 'Usage:  php -f anonygento.php[ -- [options]]' . PHP_EOL . PHP_EOL;
    }

    /**
     * gets a random customer and prints all related data for checking if the anonymisation
     * process was successful. should be like viewing a single customer in the Magento backend
     * can take as argument a customer entity id
     * @todo implement it
     *
     * @param integer  $customerEntityId
     */
    protected function _randomCustomer($customerEntityId = 0)
    {
        $customerEntityId = (int)$customerEntityId;
        $this->_consoleInstance->writeLine('@todo: ' . $customerEntityId);
    }
}

$shell = new Mage_Shell_Anonygento();
$shell->run();