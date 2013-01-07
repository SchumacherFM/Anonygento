<?php
/**
 * @category    SchumacherFM_Anonygento
 * @package     Helper
 * @author      Cyrill at Schumacher dot fm
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @bugs        https://github.com/SchumacherFM/Anonygento/issues
 * @usage       php -f filename.php
 */
error_reporting(E_ALL);

if (!$argv[1]) {
    die('First arg is the filename' . PHP_EOL);
}

$filename = $argv[1];

$file = file($filename);
$data = array();

foreach ($file as $line) {

//    $line         = ucwords(strtolower(trim($line)));
    $line         = trim($line);
    $index        = strtolower($line);
    $data[$index] = $line;
}
ksort($data);

$fp = fopen($filename, 'w');
fwrite($fp, implode("\n", $data));
fclose($fp);

echo "Rebuild csv file $filename" . PHP_EOL;
