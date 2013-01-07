<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Random_Fill extends Varien_Object
{

    /**
     * @return boolean
     */
    public function fill()
    {
        $fill  = $this->getMappings()->getFill();
        $toObj = $this->getToObj();
        /* @var $toObj Varien_Object */

        if (!is_array($fill) || !is_object($toObj)) {
            return FALSE;
        }

        foreach ($fill as $attribute => $method) {
            $origData = $toObj->getData($attribute);
            $newData  = $this->_handleMappingMethod($method, $origData);

            if (!empty($origData)) {
                $toObj->setData($attribute, $newData);
            }
        }
        $this->setToObj(null);
        return TRUE;
    }

    /**
     * @param array          $methodOptions
     * @param string|integer $origData
     *
     * @return mixed
     * @throws Exception
     */
    protected function _handleMappingMethod($methodOptions, $origData)
    {
        $model = NULL;
        if (isset($methodOptions['model']) && !empty($methodOptions['model'])) {
            $model = Mage::getSingleton($methodOptions['model']);
        } elseif (isset($methodOptions['helper']) && !empty($methodOptions['helper'])) {
            $model = Mage::helper($methodOptions['helper']);
        }

        $method = isset($methodOptions['method']) ? $methodOptions['method'] : '';
        $args   = isset($methodOptions['args']) ? $methodOptions['args'] : array();

        array_push($args, $origData);

        if (is_object($model) && method_exists($model, $method)) {
            return call_user_func_array(array($model, $method), $args);
        } else {
            return call_user_func_array($method, $args);
        }

    }

}