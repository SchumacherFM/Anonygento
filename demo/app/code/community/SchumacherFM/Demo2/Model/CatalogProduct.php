<?php

class SchumacherFM_Demo2_Model_CatalogProduct extends SchumacherFM_Anonygento_Model_Anonymizations_Abstract
{

    protected $_mapping;

    protected function _construct()
    {
        parent::_construct();

        $this->_mapping = new Varien_Object(array(
            'name' => 'name',
            'anonymized' => 'anonymized',
        ));

    }

    public function run()
    {

        $collection = $this->_getCollection();

        $i = 0;
        foreach ($collection as $product) {
            $this->_anonymizeProduct($product);
            $this->getProgressBar()->update($i);
            $i++;
        }

        $this->getProgressBar()->finish();

    }

    /**
     * @param Mage_Catalog_Model_Product $product
     *
     * @throws Exception
     */
    protected function _anonymizeProduct(Mage_Catalog_Model_Product $product)
    {

        $randomProductData = new Varien_Object(array(
            'name'       => 'RandName ' . mt_rand(),
            'anonymized' => 1,
        ));

        $this->_copyObjectData($randomProductData, $product, $this->_mapping);

        $product->getResource()->save($product);
    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getCollection()
    {
        $collection = Mage::getModel('catalog/product')
            ->getCollection();
        /* @var $collection Mage_Sales_Model_Resource_Order_Collection */

        $this->_collectionAddAttributeToSelect($collection,
            array('sku', 'name')
        );

        $this->_collectionAddStaticAnonymized($collection);

        return $collection;
    }
}