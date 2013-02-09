<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_GiftmessageMessage extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{
    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeGiftMessage');
    }

    /**
     * @param Mage_GiftMessage_Model_Message $message
     */
    protected function _anonymizeGiftMessage(Mage_GiftMessage_Model_Message $message)
    {
        $customer = $this->_getRandomCustomer()->reInit()->getCustomer();
        $this->_copyObjectData($customer, $message);
        $message->getResource()->save($message);
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('giftmessage/message');
    }

}