<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Block
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */

class SchumacherFM_Anonygento_Model_Autoload_Zf2
{
    const ZF2_PREFIX = 'SchumacherFM\\Anonygento\\Model\\Zend';

    const MAGE_COMMUNITY_FOLDER = 'community';

    const ZF2_PHP_SUFFIX = '.php';

    private static $_instance;

    public static function instance()
    {
        if (!self::$_instance) {
            $class           = __CLASS__;
            self::$_instance = new $class();
        }
        return self::$_instance;
    }

    public static function register()
    {
        spl_autoload_register(array(self::instance(), 'autoload'));
    }

    public function autoload($class)
    {
        if (strstr($class, self::ZF2_PREFIX)) {
            $classFile = self::_getFileName($class);
            $classFile = self::_getDir($classFile);
            require($classFile);
        }

    }

    private static function _getDir($classFile)
    {
        // Mage::getBaseDir('code')
        return dirname(dirname(__FILE__)) . DS . $classFile;
    }

    private static function _getFileName($class)
    {
        $class = str_replace(self::ZF2_PREFIX, 'Zend', $class);
        return str_replace(' ', DIRECTORY_SEPARATOR, ucwords(str_replace('\\', ' ', $class))) . self::ZF2_PHP_SUFFIX;
    }

}