<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Anonymizations_Creditmemo extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    /**
     * normally this won't run
     */
    public function run()
    {
        $cmCollection = $this->_getCollection();

        $i = 0;
        foreach ($cmCollection as $creditmemo) {
            $this->_anonymizeCreditmemo($creditmemo);
            $this->getProgressBar()->update($i);
            $i++;
        }
        $this->getProgressBar()->finish();
    }

    /**
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     *
     * @throws Exception
     */
    protected function _anonymizeCreditmemo(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $emptyCopy = new Varien_Object();
        $emptyCopy->setAnonymized(1);

        $creditmemo->setAnonymized(1);

        $commentsCollection = $creditmemo->getCommentsCollection();
        foreach ($commentsCollection as $comment) {

            $this->_copyObjectData($emptyCopy, $comment, $this->_getMappings('CreditmemoComment'));

            $comment->getResource()->save($comment);

        }
        $creditmemo->save();

        $creditmemo->getResource()->updateGridRecords($creditmemo->getOrderId());

    }

    /**
     * @param Mage_Sales_Model_Order       $order
     */
    public function anonymizeByOrder(Mage_Sales_Model_Order $order)
    {
        $cmCollection = $order->getCreditmemosCollection();

        foreach ($cmCollection as $creditmemo) {
            /* @var $creditmemo Mage_Sales_Model_Order_Creditmemo */
            $this->_anonymizeCreditmemo($creditmemo);
        }

    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Creditmemo_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('sales/order_creditmemo')->getCollection();
        /* @var $collection Mage_Sales_Model_Resource_Order_Creditmemo_Collection */

        $collection->addFieldToSelect(
            $this->_getMappings('Creditmemo')->getEntityAttributes()
        );

        $this->_collectionAddStaticAnonymized($collection, 0);

        return $collection;
    }

}