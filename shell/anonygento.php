<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';
error_reporting(E_ALL);

/**
 * Magento Anonygento Script
 *
 * @category    SchumacherFM_Anonygento_Anonygento
 * @package     shell
 * @author      Cyrill at Schumacher dot fm
 */
class Mage_Shell_Anonygento extends Mage_Shell_Abstract
{
    /**
     * Run script
     *
     */
    public function run()
    {
        try {
            /** @var $anonygento SchumacherFM_Anonygento_Model_Anonygento */
            $anonygento = Mage::getModel('integernet_anonygento/anonygento');
            $anonygento->anonymizeAll();
            foreach ($anonygento->getResults() as $resultLabel => $resultCount) {
                echo 'Anonymized ' . $resultCount . ' ' . $resultLabel . ".\n";
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f anonygento.php

USAGE;
    }
}

$shell = new Mage_Shell_Anonygento();
$shell->run();
