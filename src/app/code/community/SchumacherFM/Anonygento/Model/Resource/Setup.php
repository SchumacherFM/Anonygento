<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Setup
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{

    /**
     * @var boolean
     */
    protected $_isEnterpriseEdition = null;

    /**
     * @return bool
     */
    public function isEnterpriseEdition()
    {
        if ($this->_isEnterpriseEdition !== null) {
            return $this->_isEnterpriseEdition;
        }

        $esa = (array)Mage::getConfig()->getNode('modules')->Enterprise_SalesArchive;
        $etr = (array)Mage::getConfig()->getNode('modules')->Enterprise_TargetRule;
        $el  = (array)Mage::getConfig()->getNode('modules')->Enterprise_License;

        $this->_isEnterpriseEdition = (
            count($esa) === 4 &&
                count($etr) === 4 &&
                count($el) === 4
        );

        return $this->_isEnterpriseEdition;

    }

}