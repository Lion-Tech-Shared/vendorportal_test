<?php
date_default_timezone_set('Asia/Taipei');
session_start();
//require_once "";

if(!isset($_SESSION['reset_uid'])){	 
	echo 4; 
	exit;	
}

require_once "../../class-main/connection.class.php";
require_once "../../control/functions.php";

$new_pwd=strtoupper($_POST['new_pwd']);
$confirm=strtoupper($_POST['confirm_pwd']);

//$_SESSION['reset_uid'];
//$_SESSION['reset_link'];
//echo  strToHex(mysql_aes_encrypt($pwd, MD5($uid)));
if($new_pwd=="" || $confirm==""){
	echo 0;
//	header("Location:../../reset_password.php");
	exit;
}else if(strlen($new_pwd) <6 ){
	echo 2;
	exit;
}else if($new_pwd <> $confirm){
	echo 3;
	exit;
}else{
	$con=new getConnection();
	$db=$con->PDO(); 
	
	$query="UPDATE tbl_user_account SET user_pass=? WHERE user_id=?";
	$prepare=$db->prepare($query);	 
	$reset_uid=strtoupper($_SESSION['reset_uid']);	
	//$pwd=strToHex(mysql_aes_encrypt($confirm, MD5($_SESSION['reset_uid'])));	
	$prepare->execute(array(strToHex(mysql_aes_encrypt($confirm, MD5($reset_uid))),$reset_uid));
 
	$query="DELETE FROM tbl_send_link WHERE userid=?";
	$prepare=$db->prepare($query);
	$prepare->execute(array($_SESSION['reset_uid']));
	
	echo 1;
	exit;
}
?>