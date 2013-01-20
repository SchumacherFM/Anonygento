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

    public function run()
    {
        parent::run($this->_getCollection(), '_anonymizeReview');
    }

    /**
     * @param Mage_Review_Model_Review $review
     */
    protected function _anonymizeReview(Mage_Review_Model_Review $review)
    {
        $customer = $this->_getRandomCustomer()->getCustomer();
        $this->_copyObjectData($customer, $review, $this->_getMappings());
        $review->save();
    }

    /**
     * @return Mage_Review_Model_Resource_Review_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('review/review',false)->addFieldToSelect(array('entity_id', 'review_id'));
        ;
    }

}