<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 *
 * do not run as Singleton class
 * the unsetData method is not implemented before each set ...
 */
class SchumacherFM_Anonygento_Model_Random_Mappings extends Varien_Object
{
    /**
     * @return array
     */
    public function getEntityAttributes()
    {
        $data = $this->getData();

        //getting the keys from these two xml config elements to
        //add the keys to the collection()->add[Attribute|Field]ToSelect method
        foreach (array('fill', 'system') as $element) {
            if (isset($data[$element])) {
                $systemKeys = array_keys($data[$element]);
                unset($data[$element]);
                $data = array_merge($data, $systemKeys);
            }
        }

        return array_unique(array_values($data));
    }

    /**
     * @param string $type
     *
     * @return SchumacherFM_Anonygento_Model_Random_Mappings
     * @throws Exception
     */
    public function getMapping($type)
    {
        $this->reset();
        $config = Mage::helper('schumacherfm_anonygento')->getAnonymizationsConfig();

        if (!isset($config->$type)) {
            throw new Exception('Cannot find config node: ' . $type);
        }

        if (!isset($config->$type->mapping)) {
            throw new Exception('Cannot find mapping node for ' . $type);
        }

        $this->addData($config->$type->mapping->asArray());

        if ($this->getUpdate()) {
            $update = $this->getUpdate();
            $this->unsUpdate();
            return $this->getMapping($update);

        }

        return $this;
    }

    /**
     * resets the mapping object
     * @return void
     */
    public function reset()
    {
        $this->setData(array());
    }

}