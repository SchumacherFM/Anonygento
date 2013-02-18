<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Test_Model_Random_Customer_Customer extends EcomDev_PHPUnit_Test_Case
{

    /**
     * @var SchumacherFM_Anonygento_Model_Random_Customer
     */
    protected $_customer = null;

    protected function setUp()
    {
        parent::setUp();
        $this->_customer = Mage::getModel('schumacherfm_anonygento/random_customer', array('useDataInternal' => FALSE));
    }

    public function testGetCustomer()
    {

        $randomCustomer = $this->_customer->getCustomer();

        $this->assertRegExp('~^[a-z0-9]{4,}$~i', $randomCustomer->getFirstname(), 'Firstname');
        $this->assertRegExp('~^[a-z0-9]{4,}$~i', $randomCustomer->getMiddlename(), 'Middlename');
        $this->assertRegExp('~^[a-z0-9]{4,}$~i', $randomCustomer->getLastname(), 'Lastname');
        $this->assertRegExp('~^[0-9]{4}-[0-9]{2}-[0-9]{2}$~', $randomCustomer->getDob(), 'Dob');
        $this->assertRegExp('~^[a-z0-9]{4,}\n[0-9]+$~i', $randomCustomer->getStreet(), 'Street');
        $this->assertRegExp('~^[0-9]{5}-[0-9]{6}$~', $randomCustomer->getTelephone(), 'Telephone');
        $this->assertRegExp('~^[0-9]{5}-[0-9]{6}$~', $randomCustomer->getFax(), 'Fax');

        $this->assertFalse(!ip2long($randomCustomer->getRemoteIp()), 'RemoteIp');
        $this->assertEquals($randomCustomer->getFirstname() . ' ' . $randomCustomer->getLastname(), $randomCustomer->getName(), 'Name');
        $this->assertEquals($randomCustomer->getFirstname() . ' ' . $randomCustomer->getMiddlename() . ' ' . $randomCustomer->getLastname(),
            $randomCustomer->getName2(), 'Name2');

        $this->assertRegExp('~^' . preg_quote($randomCustomer->getFirstname()) . '\.' . preg_quote($randomCustomer->getLastname()) .
                '-[a-z0-9]+@[a-z0-9\-]+\.[a-z0-9]+$~i',
            $randomCustomer->getEmail(), 'Email');

    }

}