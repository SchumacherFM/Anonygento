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
     * @param string  $type
     * @param integer $value
     */
    public function setAnonymizations($type, $value)
    {
        $value = (int)$value;
        Mage::app()->getStore()->setConfig(self::XML_PATH_ANONYMIZED . $type, $value);

    }

    public function getAnonymizations($type)
    {
        return (int)Mage::getStoreConfig(self::XML_PATH_ANONYMIZED . $type);
    }

    public function getLocaleForData()
    {
        /*
           sometimes getStoreConfig returns NULL if called in the shell, that's weired :-(
           if the custom user settings from System > Configuration > Advanced > SchumacherFM
           are not applied then check here.
        */

        $locale = Mage::getStoreConfig(self::XML_PATH_ANONYMIZED . 'locale');
        if( $locale || empty($locale) ){
            $locale = (string)Mage::getConfig()->getNode('default')->schumacherfm->anonygento->locale;
        }
        return $locale;
    }

}