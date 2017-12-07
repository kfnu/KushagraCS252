<?php
/**
 * Created by PhpStorm.
 * User: Kushagra
 * Date: 12/3/2017
 * Time: 5:16 PM
 */


require_once("setting.php");

//require_once("model/".$dbtype.".php");
require_once($dbtype.".php");

//Construct a db instance
$dbconfig = array(
    'host' => $db_host,
    'user' => $db_user,
    'pass' => $db_pass,
    'name' => $db_name
);
echo "qwertyuiop";
$db = db_mysql::getInstance();

if(!isset($language)) $langauge = "en";

/*require_once("lang/".$langauge.".php");
require_once("class.user.php");
require_once("class.mail.php");
require_once("funcs.user.php");
require_once("funcs.general.php");
require_once("class.newuser.php");*/

session_start();

//Global User Object Var
//loggedInUser can be used globally if constructed
if(isset($_SESSION["CCUser"]) && is_object($_SESSION["CCUser"]))
{
    $loggedInUser = $_SESSION["CCUser"];
}

$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$bwerror="";
// you can add different browsers with the same way ..
if(preg_match('/(chromium)[ \/]([\w.]+)/', $ua))
    $browser = 'chromium';
elseif(preg_match('/(chrome)[ \/]([\w.]+)/', $ua))
    $browser = 'chrome';
elseif(preg_match('/(safari)[ \/]([\w.]+)/', $ua))
    $browser = 'safari';
elseif(preg_match('/(opera)[ \/]([\w.]+)/', $ua))
    $browser = 'opera';
elseif(preg_match('/(msie)[ \/]([\w.]+)/', $ua))
    $browser = 'msie';
elseif(preg_match('/(mozilla)[ \/]([\w.]+)/', $ua))
    $browser = 'mozilla';

preg_match('/('.$browser.')[ \/]([\w]+)/', $ua, $version);

if($browser=="msie" && $version[2]<8){
    echo "Your browser version is too old and is not supported. Use latest version of Internet Explorer (IE 8+), Mozilla Firefox, Google Chrome, Opera, Safari, etc.";
    exit;
}
?>