<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Review extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeReview');
    }

    /**
     * @param Mage_Review_Model_Review $review
     */
    protected function _anonymizeReview(Mage_Review_Model_Review $review)
    {
        $customer = $this->_getRandomCustomer()->getCustomer();
        $this->_copyObjectData($customer, $review);
        $review->getResource()->save($review);
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('review/review',false)->addFieldToSelect(array('entity_id', 'review_id'));
    }

}