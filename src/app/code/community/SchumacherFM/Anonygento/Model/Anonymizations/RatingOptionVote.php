<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_RatingOptionVote extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeRatingVote');
    }

    /**
     * @param Mage_Rating_Model_Rating_Option_Vote $vote
     */
    protected function _anonymizeRatingVote(Mage_Rating_Model_Rating_Option_Vote $vote)
    {
        $emptyObject = new Varien_Object(array('anonymized' => 1));
        $this->_copyObjectData($emptyObject, $vote);
        $vote->getResource()->save($vote);
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('rating/rating_option_vote');
    }

}