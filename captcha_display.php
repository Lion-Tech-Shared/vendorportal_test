<?php
require_once 'securimage/securimage.php';
$captchaId=Securimage::getCaptchaId(true);
$captchaId=Securimage::getCaptchaId(true,array('code_length' =>8 ,'database_driver' => Securimage::SI_DRIVER_MYSQL));

$captchaId=$_GET['captchaId'];

if(empty($captchaId)){
	die('no id');
}
//$options=array('captchaId'=>$captchaId);
$captcha=new Securimage();

$captcha->show();

?>

