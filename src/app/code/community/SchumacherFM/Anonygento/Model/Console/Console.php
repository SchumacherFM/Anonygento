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
        return TRUE;

        /* disabled, not really necessary */

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
        return Mage::getModel('schumacherfm_anonygento/console_progressBar', array('count' => $count));
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
     * @param string $modelName
     *
     * @return false|Mage_Core_Model_Abstract
     */
    public function getModel($modelName)
    {
        /**
         * custom anonymization models can be load here
         * event is fired in: SchumacherFM_Anonygento_Model_Options_Anonymizations::getCollection
         */
        $model = Mage::getModel('schumacherfm_anonygento/anonymizations_' . $modelName);
        if ($model) {
            return $model;
        }
        return Mage::getModel($modelName);
    }

    /**
     * @return object
     */
    public function getAnonymizationCollection()
    {
        return Mage::getModel('schumacherfm_anonygento/options_anonymizations')->getCollection();
    }

    /**
     * counts a collection with the use of the column anonymized
     *
     * @param $model
     *
     * @return integer
     */
    public function reCalcUnAnonymized($model)
    {
        return Mage::getModel('schumacherfm_anonygento/counter')->unAnonymized($model);
    }

    /**
     * @param Varien_Data_Collection $execCollection
     *
     * @return Zend_Text_Table
     */
    public function printInfoTable(Varien_Data_Collection $execCollection)
    {

        $table = new Zend_Text_Table(array(
            'columnWidths' => array(25, 13, 11, 8),
            'AutoSeparate' => Zend_Text_Table::AUTO_SEPARATE_HEADER
        ));

        $table->appendRow(array('Label', 'Unanonymized', 'Anonymized', '  Total'));
        foreach ($execCollection as $entities) {

            $row = new Zend_Text_Table_Row();

            $row->appendColumn(
                new Zend_Text_Table_Column($entities->getLabel())
            );
            $row->appendColumn(
                new Zend_Text_Table_Column($entities->getUnanonymized() . ' ', Zend_Text_Table_Column::ALIGN_RIGHT)
            );
            $row->appendColumn(
                new Zend_Text_Table_Column($entities->getAnonymized() . ' ', Zend_Text_Table_Column::ALIGN_RIGHT)
            );
            $row->appendColumn(
                new Zend_Text_Table_Column(($entities->getUnanonymized() + $entities->getAnonymized()) . ' ', Zend_Text_Table_Column::ALIGN_RIGHT)
            );

            $table->appendRow($row);

        }

        return $table;

    }

}