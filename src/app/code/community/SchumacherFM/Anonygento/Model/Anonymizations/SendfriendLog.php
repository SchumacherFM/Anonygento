<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_SendfriendLog extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    public function run()
    {
        parent::run($this->_getCollection(), '_anonymizeLog');
    }

    /**
     * @param Mage_Sendfriend_Model_Sendfriend $model
     */
    protected function _anonymizeLog(Mage_Sendfriend_Model_Sendfriend $model)
    {

        $emptyObject = new Varien_Object(array('anonymized' => 1));
        $this->_copyObjectData($emptyObject, $model, $this->_getMappings());
        $model->getResource()->save($model);
    }

    /**
     * @return Mage_Sendfriend_Model_Resource_Sendfriend_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('sendfriend/sendfriend');
    }

}