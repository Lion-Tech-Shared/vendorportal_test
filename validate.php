<?php
require_once "securimage/securimage.php";
$securimage=new Securimage();

if($securimage->check($_POST['captcha_code'])==false){
	echo 'Error!';
	
}else{
	echo 'Ok';	
}

?>