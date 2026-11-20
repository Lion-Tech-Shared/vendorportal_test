<?php
session_start();

require_once "../../class-main/connection.class.php";

if(!isset($_SESSION['uid']) || $_POST['gate_pass'] <> 'confirm'){	 
	header("Location:../home.php");	
	exit;
}
 
$con=new getConnection();
$db=$con->PDO();

$userid=$_POST['userid'];
 
$query='DELETE FROM tbl_user_account WHERE user_id = ?';
$prepare=$db->prepare($query);
if($prepare->execute(array($userid))){ 
	$query='DELETE FROM tbl_user_info WHERE user_id = ?';
	$prepare=$db->prepare($query);
	if($prepare->execute(array($userid))){	
		$query='DELETE FROM tbl_employee_master WHERE user_id = ?';
		$prepare=$db->prepare($query);
		if($prepare->execute(array($userid))){
			echo 'success';	
		}else{
			echo 'failed3';	
		}
	}else{
		echo 'failed2';	
	}
}else{
	echo 'failed1';	
}
 
?>