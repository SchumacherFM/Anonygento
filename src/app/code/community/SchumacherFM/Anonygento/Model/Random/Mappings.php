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

        if (isset($data['fill'])) {
            foreach ($data['fill'] as $attributeName => $options) {
                $data[] = $attributeName;
            }
            unset($data['fill']);
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
        $config = Mage::helper('schumacherfm_anonygento')->getAnonymizationsConfig();

        if (!isset($config->$type)) {
            throw new Exception('Cannot find config node: ' . $type);
        }

        if (!isset($config->$type->mapping)) {
            throw new Exception('Cannot find mapping node for ' . $type);
        }

        $this->addData($config->$type->mapping->asArray());

        return $this;
    }

}