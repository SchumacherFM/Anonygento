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

    public function run()
    {
        $collection = $this->_getCollection();

        $i = 0;
        foreach ($collection as $vote) {
            $this->_anonymizeRatingVote($vote);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();
    }

    /**
     * @param Mage_Rating_Model_Rating_Option_Vote $vote
     */
    protected function _anonymizeRatingVote(Mage_Rating_Model_Rating_Option_Vote $vote)
    {
        $emptyObject = new Varien_Object(array('anonymized' => 1));
        $this->_copyObjectData($emptyObject, $vote, $this->_getMappings('RatingOptionVote'));
        $vote->getResource()->save($vote);
    }

    /**
     * @return Mage_Rating_Model_Resource_Rating_Option_Vote_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('rating/rating_option_vote')
            ->getCollection()
            ->addFieldToSelect($this->_getMappings('RatingOptionVote')->getEntityAttributes());
        /* @var $collection Mage_Rating_Model_Resource_Rating_Option_Vote_Collection */

        $this->_collectionAddStaticAnonymized($collection, 0);

        return $collection;
    }

}