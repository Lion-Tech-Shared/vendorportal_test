<?php
require "../../class-main/connection.class.php";
require "../../control/functions.php";
session_start();

if(!$_POST['securityFlag']){		
	header("Location: ../home.php");	
}
$conn = new getConnection();
$db = $conn->PDO();

$userid=strtoupper($_POST['userid']);
$flag=$_POST['flagProcess'];

$prepare=$db->prepare("SELECT a.*,b.ee_name as name FROM tbl_user_account a LEFT JOIN tbl_user_info b on a.user_id=b.user_id WHERE a.user_id=? AND a.user_id <>? AND  b.ee_name is not null");
$prepare->execute(array($userid,"ADMIN"));	
$fetch=$prepare->fetchAll();

if(count($fetch) < 1){
	$prepare=$db->prepare("SELECT a.*,b.v_name as name FROM tbl_user_account a LEFT JOIN tbl_vendor_info b on a.user_id=b.v_code WHERE a.user_id=?");
	$prepare->execute(array($userid));	
	$fetch=$prepare->fetchAll();
}

if($flag=="submit"){
	if(count($fetch)>0){
		$qry=' UPDATE tbl_user_account SET user_pass=? WHERE user_id=?';
		$prepare=$db->prepare($qry);
		$new_pwd=strToHex(mysql_aes_encrypt($userid,MD5($userid)));	
		$prepare->execute(array($new_pwd,$userid));
		echo 1;
	}else{
		echo 0;
	}
}else{
	if(count($fetch)>0){
		echo $fetch[0]["name"];
	}else{
		echo 0;
	}
	
}


?>