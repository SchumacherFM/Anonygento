<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Block
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */

class SchumacherFM_Anonygento_Model_Options_Anonymizations extends Varien_Object
{
    public $useCache = 0;

    /**
     * the order of this array is important
     *
     * @var array
     */
    protected $_options = array(

        // internalKey => modelname
        'customer'             => 'customer/customer',
        'customerAddress'      => 'customer/address',
        'newsletterSubscriber' => 'newsletter/subscriber',
        'giftmessageMessage'   => 'giftmessage/message',

        'order'                => 'sales/order',
        'orderAddress'         => 'sales/order_address',
        'orderGrid'            => 'sales/order_grid_collection',
        'orderPayment'         => 'sales/order_payment',

        'quote'                => 'sales/quote',
        'quoteAddress'         => 'sales/quote_address',
        'quotePayment'         => 'sales/quote_payment',

        // all comments collections are only for FYI
        'creditmemo'           => 'sales/order_creditmemo_collection',
        'creditmemoComment'    => 'sales/order_creditmemo_comment_collection',

        'invoice'              => 'sales/order_invoice_collection',
        'invoiceComment'       => 'sales/order_invoice_comment_collection',

        'shipment'             => 'sales/order_shipment',
        'shipmentComment'      => 'sales/order_shipment_comment',

        'review'               => 'review/review',
        'ratingOptionVote'     => 'rating/rating_option_vote',
        'sendfriendLog'        => 'sendfriend/sendfriend',
    );

    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        $return = array();
        foreach ($this->_options as $opt => $modelName) {
            $return[] = array(
                'label' => Mage::helper('schumacherfm_anonygento')->__($opt),
                'value' => $opt,
                'model' => $modelName
            );
        }

        return $return;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $_options = array();
        foreach ($this->getAllOptions() as $option => $modelName) {
            $_options[$option['value']] = $option['label'];
        }
        return $_options;
    }

    /**
     * @var Varien_Data_Collection
     */
    protected $_collection = null;

    /**
     * @return Varien_Data_Collection
     */
    public function getCollection()
    {

        if ($this->_hasAdminCollection() && $this->useCache === 1) {
            return $this->_getAdminCollection();
        }

        if ($this->_collection !== null) {
            return $this->_collection;
        }

        $this->_collection = new Varien_Data_Collection();

        $rowCountModel = Mage::getSingleton('schumacherfm_anonygento/counter');

        foreach ($this->getAllOptions() as $option) {

            $optObj = new Varien_Object($option);
            $optObj
            /* @see SchumacherFM_Anonygento_Block_Adminhtml_Anonygento_Grid column: Status */
                ->setStatus(Mage::helper('schumacherfm_anonygento')->getAnonymizations($option['value']))
                ->setUnanonymized($rowCountModel->unAnonymized($option['model']))
                ->setAnonymized($rowCountModel->anonymized($option['model']));

            $this->_collection->addItem($optObj);
        }

        Mage::dispatchEvent('anonygento_options_anonymizations_collection_after', array(
            'collection' => $this->_collection
        ));

        if ($this->useCache === 1) {
            $this->_setAdminCollection();
        }
        return $this->_collection;

    }

    protected function _setAdminCollection()
    {
        Mage::getSingleton('admin/session')->setAnonymizationsCollection($this->_collection->toArray());
    }

    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function _getAdminCollection()
    {
        $return = Mage::getSingleton('admin/session')->getAnonymizationsCollection();

        if (!isset($return['items'])) {
            throw new Exception('items key is empty for getAnonymizationsCollection');
        }

        $collection = new Varien_Data_Collection();

        foreach ($return['items'] as $item) {
            $objItem = new Varien_Object($item);
            $collection->addItem(
                $objItem->setRowcountcached('yes')
            );
        }
        return $collection;
    }

    /**
     * @return bool
     */
    protected function _hasAdminCollection()
    {
        return (boolean)Mage::getSingleton('admin/session')->hasAnonymizationsCollection();
    }

}