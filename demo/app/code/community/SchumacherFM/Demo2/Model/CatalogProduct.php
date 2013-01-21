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

    public function run()
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
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getCollection()
    {
        return parent::_getCollection('catalog/product');
    }
}