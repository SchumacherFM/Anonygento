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

        'customers',
        'customersAddresses',
        'orders',
        'orderAddresses',
        'quotes',
        'quoteAddresses',
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
     * @return Varien_Data_Collection
     */
    public function getCollection()
    {
        $collection = new Varien_Data_Collection();
        foreach ($this->getAllOptions() as $option) {

            $optObj = new Varien_Object();

            $optObj->addData($option);

            $collection->addItem($optObj);
        }
        return $collection;

    }

}
