<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Model
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 */
class SchumacherFM_Anonygento_Model_Console_ProgressBar extends Zend_ProgressBar
{

    public function __construct($arguments = array())
    {
        $count = isset($arguments['count'])
            ? (int)$arguments['count']
            : 0;

        $pbAdapter = new Zend_ProgressBar_Adapter_Console(
            array('elements' =>
                  array(Zend_ProgressBar_Adapter_Console::ELEMENT_PERCENT,
                      Zend_ProgressBar_Adapter_Console::ELEMENT_BAR,
                      Zend_ProgressBar_Adapter_Console::ELEMENT_ETA
                  )
            )
        );

        parent::__construct($pbAdapter, 0, $count);

    }

    /**
     * Update the progressbar
     *
     * @param  float  $value
     * @param  string $text
     *
     * @return void
     */
    public function update($value = null, $text = null)
    {
        parent::update($value, $text);
    }

}