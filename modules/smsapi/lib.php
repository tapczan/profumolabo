<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
const CHARSET = 'UTF-8';
const REPLACE_FLAGS = ENT_COMPAT;
error_reporting(0);
$login = $_POST['SMSID'];
$password = $_POST['SMSPASS'];
$tel = str_replace('+', "", $_POST['PHONE']);
$tel = str_replace(' ', "", $tel);
$phone = strip_tags($tel);
$sender = $_POST['SMSSENDER'];
$eco = $_POST['SMSECO'];
$test = $_POST['SMSTEST'];
$flash = $_POST['SMSFLASH'];
$FIRST = $_POST['FIRST'];
$LAST = $_POST['LAST'];
$MAIL = $_POST['MAIL'];
$TRACK = $_POST['TRACK'];
$ORDERID = $_POST['ORDERID'];
$VALUE = number_format($_POST['VALUE'],2, ',', '');
$SHOP = configuration::get('PS_SHOP_NAME');
$message = $_POST['SMSTXT'];
$message = str_replace('{$FIRST}', $FIRST , $message);
$message = str_replace('{$LAST}', $LAST , $message);
$message = str_replace('{$MAIL}', $MAIL , $message);
$message = str_replace('{$ORDERID}', $ORDERID , $message);
$message = str_replace('{$VALUE}', $VALUE , $message);
$message = str_replace('{$SHOP}', $SHOP , $message);
$message = str_replace('{$TRACK}', $TRACK , $message);
$soap = null;

try {

    $soap = new SoapClient( 'https://ssl2.smsapi.pl/webservices/v2/?wsdl' , array(
                'features'   => SOAP_SINGLE_ELEMENT_ARRAYS,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace'      => 1,
            )
    );

    $client = array( 'username' => $login , 'password' => $password);
    $sms = array(
        'sender'    => $sender,
        'recipient' => $phone,
        'eco'       => $eco,
        'date_send' => 0,
        'details'   => 0,
        'message'   => $message,
        'partner_id' => 7413,
        'params'    => array( "single_message" => 0,"no_unicode" => 1,"test" => $test,"flash" => $flash),
        'idx'       => uniqid(),
    );

    $params = array(
        'client' => $client,
        'sms'    => $sms
    );
    $result = $soap->send_sms($params);
    $SMSADMIN = $_POST['SMSADMIN'];
    if ($SMSADMIN == '1') {
                    $thread = Db::getInstance()->getValue('SELECT max(id_customer_thread) FROM `' . _DB_PREFIX_ . 'customer_thread`');
                    $cth = ++$thread;
                    $time = new DateTime(date("Y-m-d H:m:s"));
                    $timenow = $time->format('Y-m-d H:m:s'); 
                    $token = Tools::passwdGen(12);
                    $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'customer_message (`id_customer_thread`, `message`, `date_add`, `date_upd`) VALUES (\''.$cth.'\',\''.$message.'\',\''.$timenow.'\',\''.$timenow.'\')';
                    Db::getInstance()->Execute(trim($sql));
                    $langid = Configuration::get('PS_LANG_DEFAULT');
                    $sql2 = 'INSERT INTO ' . _DB_PREFIX_ . 'customer_thread (`id_lang`, `id_contact`, `id_product`, `email`,`token`, `date_add`, `date_upd`) VALUES (\''.(int)$langid.'\',\'1\',\'0\',\''.$MAIL.'\',\''.$token.'\',\''.$timenow.'\',\''.$timenow.'\')';
                    Db::getInstance()->Execute(trim($sql2));
    }
    ob_start();
// Removed by Angelo Dan B.
//    var_dump($result);
    $res = ob_get_clean();
    unlink('smsapi_results.txt');
    $fp = fopen("smsapi_results.txt", 'a');
    fwrite($fp, $res);
    fclose($fp);

// Removed by Angelo Dan B.
//    switch ($result['response']) {
//    case 'OK':
//         break;
//    case 'ERR':
//        break;
//    case 'FATAL_ERROR':
//        break;
//}


}
catch(Exception $e) {
unlink('smsapi_exceptions.txt');
ob_start();
var_dump($e);
$exc = ob_get_clean();
$fp = fopen("smsapi_exceptions.txt", 'a');
fwrite($fp, $exc);
fclose($fp);
}
?> 