<?php

class SchumacherFM_Demo2_Model_CatalogProduct extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    protected $_randomProductData;

    protected function _construct()
    {
        parent::_construct();
        $this->_randomProductData = new Varien_Object(array(
            'rand_name' => ''
        ));
    }

    /**
     * @param null $collection
     * @param null $anonymizationMethod
     */
    public function run($collection = null, $anonymizationMethod = null)
    {
        parent::run($this->_getCollection(), '_anonymizeProduct');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     *
     * @throws Exception
     */
    protected function _anonymizeProduct(Mage_Catalog_Model_Product $product)
    {
        $this->_randomProductData->setRandName('RandName ' . mt_rand());
        $this->_copyObjectData($this->_randomProductData, $product);
        /**
         * do not use $product->save() as it will fire events
         */
        $product->getResource()->save($product);
    }

    /**
     * @param string  $modelName
     * @param boolean $useMapping
     *
     * @return Mage_Newsletter_Model_Resource_Subscriber_Collection
     */
    protected function _getCollection($modelName = null, $useMapping = null)
    {
        return parent::_getCollection('catalog/product');
    }
}