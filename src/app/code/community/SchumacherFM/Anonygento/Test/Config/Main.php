<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Test
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{
    const MODULALIAS  = 'schumacherfm_anonygento';
    const MODULPREFIX = 'SchumacherFM_Anonygento';

    public function testSetupResources()
    {
        $this->assertSetupResourceDefined();
        $this->assertSetupResourceExists();
    }

    public function testClassAliases()
    {
        $this->assertHelperAlias(self::MODULALIAS, self::MODULPREFIX . '_Helper_Data');
        $this->assertResourceModelAlias(self::MODULALIAS . '/resource_setup', self::MODULPREFIX . '_Model_Resource_Setup');

        $this->assertBlockAlias(self::MODULALIAS.'/adminhtml_view','SchumacherFM_Anonygento_Block_Adminhtml_View');
        $this->assertBlockAlias(self::MODULALIAS.'/adminhtml_anonygento','SchumacherFM_Anonygento_Block_Adminhtml_Anonygento');
        $this->assertBlockAlias(self::MODULALIAS.'/adminhtml_view_grid','SchumacherFM_Anonygento_Block_Adminhtml_View_Grid');
        $this->assertBlockAlias(self::MODULALIAS.'/adminhtml_anonygento_grid','SchumacherFM_Anonygento_Block_Adminhtml_Anonygento_Grid');

        $models = array(
            '/anonymizations_creditmemo'           => 'Anonymizations_Creditmemo',
            '/anonymizations_customer'             => 'Anonymizations_Customer',
            '/anonymizations_customerAddress'      => 'Anonymizations_CustomerAddress',
            '/anonymizations_giftmessageMessage'   => 'Anonymizations_GiftmessageMessage',
            '/anonymizations_invoice'              => 'Anonymizations_Invoice',
            '/anonymizations_newsletterSubscriber' => 'Anonymizations_NewsletterSubscriber',
            '/anonymizations_order'                => 'Anonymizations_Order',
            '/anonymizations_orderAddress'         => 'Anonymizations_OrderAddress',
            '/anonymizations_orderGrid'            => 'Anonymizations_OrderGrid',
            '/anonymizations_orderPayment'         => 'Anonymizations_OrderPayment',
            '/anonymizations_quote'                => 'Anonymizations_Quote',
            '/anonymizations_quoteAddress'         => 'Anonymizations_QuoteAddress',
            '/anonymizations_quotePayment'         => 'Anonymizations_QuotePayment',
            '/anonymizations_ratingOptionVote'     => 'Anonymizations_RatingOptionVote',
            '/anonymizations_review'               => 'Anonymizations_Review',
            '/anonymizations_sendfriendLog'        => 'Anonymizations_SendfriendLog',
            '/anonymizations_shipment'             => 'Anonymizations_Shipment',

            '/counter'                             => 'Counter',

            '/random_customer'                     => 'Random_Customer',
            '/random_fill'                         => 'Random_Fill',
            '/random_ip'                           => 'Random_Ip',
            '/random_loremIpsum'                   => 'Random_LoremIpsum',
            '/random_mappings'                     => 'Random_Mappings',
            '/random_payment'                      => 'Random_Payment',

            '/options_anonymizations'              => 'Options_Anonymizations',

            '/console_color'                       => 'Console_Color',
            '/console_console'                     => 'Console_Console',
            '/console_progressBar'                 => 'Console_ProgressBar',

            '/autoload_zf2'                        => 'Autoload_Zf2',

        );

        foreach ($models as $classAlias => $expectedClassName) {
            $this->assertModelAlias(self::MODULALIAS . $classAlias, self::MODULPREFIX . '_Model_' . $expectedClassName);
        }

    }
}
