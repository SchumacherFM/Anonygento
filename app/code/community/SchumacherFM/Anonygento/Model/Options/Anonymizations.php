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
    /**
     * @var array
     */
    protected $_options = array(

        'customer',
        'customerAddress',
        'order',
        'orderAddress',
        'quote',
        'quoteAddress',
        'newsletterSubscribers'

    );

    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        $return = array();
        foreach ($this->_options as $opt) {
            $return[] = array(
                'label' => Mage::helper('schumacherfm_anonygento')->__($opt),
                'value' => $opt
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
        foreach ($this->getAllOptions() as $option) {
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

        if ($this->_hasAdminCollection()) {
            return $this->_getAdminCollection();
        }

        if ($this->_collection !== null) {
            return $this->_collection;
        }

        $this->_collection = new Varien_Data_Collection();

        $rowCountModel = Mage::getModel('schumacherfm_anonygento/counter');

        foreach ($this->getAllOptions() as $option) {

            $optObj = new Varien_Object();
            $optObj
                ->addData($option)
                ->setStatus(Mage::helper('schumacherfm_anonygento')->getAnonymizations($option['value']))
                ->setRowcount($rowCountModel->{'count' . $option['value']}());

            $this->_collection->addItem($optObj);
        }
        $this->_setAdminCollection();
        return $this->_collection;

    }

    protected function _setAdminCollection()
    {
        Mage::getSingleton('admin/session')->setAnonymizationsCollection($this->_collection);
    }

    protected function _getAdminCollection()
    {
        return Mage::getSingleton('admin/session')->getAnonymizationsCollection();
    }

    protected function _hasAdminCollection()
    {
        return Mage::getSingleton('admin/session')->hasAnonymizationsCollection();
    }
}