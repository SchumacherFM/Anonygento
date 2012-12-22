<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Console_Console extends SchumacherFM\Anonygento\Model\Zend\Console\Console
{

    /**
     * @return bool
     */
    public function isAdminUser()
    {
        $line = $this->getModelZf2('console_prompt_line');

        $line->setPromptText('Admin user name: ');
        $username = $line->show();

        // @todo hide the password input
        $line->setPromptText('Admin password: ');
        $password = $line->show();

        $adminUser = $this->_checkAdminUser($username, $password);

        if ($adminUser) {
            $this->getInstance()->writeLine('Welcome ' . $adminUser->getName(), SchumacherFM_Anonygento_Model_Console_Color::YELLOW);
            return TRUE;
        } else {
            $this->getInstance()->writeLine('Admin user not found!', SchumacherFM_Anonygento_Model_Console_Color::RED);
            return FALSE;
        }

    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool|Mage_Admin_Model_User
     */
    protected function _checkAdminUser($username, $password)
    {
        // @todo check ACL, create ACL for console

        $adminUser = Mage::getModel('admin/user');
        /* @var $adminUser Mage_Admin_Model_User */
        $isAdmin = $adminUser->authenticate($username, $password);
        if ($isAdmin) {
            return $adminUser->loadByUsername($username);
        } else {
            return FALSE;
        }

    }

    /**
     * @param integer $count
     *
     * @return Zend_ProgressBar
     */
    public function getProgressBar($count)
    {
        $pbAdapter = new Zend_ProgressBar_Adapter_Console(
            array('elements' =>
                  array(Zend_ProgressBar_Adapter_Console::ELEMENT_PERCENT,
                      Zend_ProgressBar_Adapter_Console::ELEMENT_BAR /* ,
                     this is to weird for showing it because of the many recalculations
                      Zend_ProgressBar_Adapter_Console::ELEMENT_ETA */
                  )
            )
        );

        return new Zend_ProgressBar($pbAdapter, 0, $count);
    }


    /**
     * gets a zend framework 2 class
     *
     * @param string $class
     * @param mixed  $arg1
     *
     * @return object
     */
    public function getModelZf2($class, $arg1 = null)
    {
        $model = 'SchumacherFM\\Anonygento\\Model\\Zend\\' .
            str_replace(' ', '\\', ucwords(str_replace('_', ' ', $class)));

        // hmmmmm
        if ($arg1 !== null) {
            return new $model($arg1);
        } else {
            return new $model();
        }
    }


    /**
     * @param $type
     *
     * @return false|Mage_Core_Model_Abstract
     */
    public function getModel($type)
    {
        return Mage::getModel('schumacherfm_anonygento/anonymizations_' . $type);
    }

    /**
     * @return object
     */
    public function getAnonymizationCollection()
    {
        return Mage::getModel('schumacherfm_anonygento/options_anonymizations')->getCollection();
    }

    /**
     * @param $model
     *
     * @return integer
     */
    public function reCalcUnAnonymized($model)
    {
        return Mage::getModel('schumacherfm_anonygento/counter')->unAnonymized($model);
    }

}