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

    /**
     * adds to an eav entity the attribute anonymized
     *
     * @param string $entityName
     *
     * @return SchumacherFM_Anonygento_Model_Resource_Setup
     */
    public function addAnonymizedAttribute($entityName)
    {
        $this->removeAttribute($entityName, 'anonymized');

        $this->addAttribute($entityName, 'anonymized', array(
            'type'         => 'static',
            'group'        => 'General',
            'label'        => 'Is anonymized',
            'is_visible'   => TRUE,
            'visible'      => TRUE,
            'default'      => 0,
            'user_defined' => FALSE,
            'input'        => 'boolean',
            'backend'      => 'customer/attribute_backend_data_boolean',
            'required'     => FALSE,
            'is_system'    => TRUE,
            'position'     => 200,
            'sort_order'   => 200,
        ));
        return $this;
    }

    /**
     * adds the column anonymized to a entity/table
     *
     * @param $entityName
     *
     * @return SchumacherFM_Anonygento_Model_Resource_Setup
     */
    public function addAnonymizedColumn($entityName)
    {
        $this
            ->getConnection()
            ->addColumn($this->getTable($entityName), 'anonymized', 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 0');

        return $this;
    }

}