<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Helper
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ANONYMIZED = 'schumacherfm/anonygento/';

    /**
     * @return string
     */
    public function getLocaleForData()
    {
        /*
           sometimes getStoreConfig returns NULL if called in the shell, that's weired :-(
           if the custom user settings from System > Configuration > Advanced > SchumacherFM
           are not applied then check here.
        */

        $locale = (string)Mage::getStoreConfig(self::XML_PATH_ANONYMIZED . 'locale');
        if ($locale || empty($locale)) {
            $locale = (string)Mage::getConfig()->getNode('default')->schumacherfm->anonygento->locale;
        }
        return $locale;
    }

    /**
     * @param string $element
     *
     * @return Mage_Core_Model_Config_Element
     */
    public function getAnonymizationsConfig($element = '')
    {
        if (!empty($element)) {
            return Mage::getConfig()->getNode('anonygento')->anonymizations->{$element};
        } else {
            return Mage::getConfig()->getNode('anonygento')->anonymizations->children();
        }

    }

    /**
     * @return Mage_Core_Model_Config_Element
     */
    public function getRandomConfig()
    {
        return Mage::getConfig()->getNode('anonygento')->random->children();
    }

}