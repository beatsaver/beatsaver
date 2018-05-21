require 'Medoo.php';
use Medoo\Medoo;
$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'beatsaver',
    'server' => 'localhost',
    'username' => 'xxxxxxxx',
    'password' => 'xxxxxxxxx'
]);
require("sendgrid-php/sendgrid-php.php");
$CAPTCHA_SECRET = 'xxxxx';
$SENDGRID_SECRET = 'xxxx';
session_start();
if(@$requirelogin){
if(empty($_SESSION["userdb"][0]["id"])){
header("Location: login.php");
die();
}
}
$cache = true;
require("cache.php");

