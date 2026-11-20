<?php
session_start();
if(!isset($_SESSION['uid']) || (!isset($_POST['pass']) && $_POST['pass'] <> 'gate_pass')){
	header("Location:../../home.php");
	exit;	
}

require_once "../../class-main/connection.class.php";
$array=array();
$con=new getConnection();
$db=$con->PDO();

if($_POST['flag']==1){	
	$userid=$_POST['userid'];
	$status=$_POST['status'];
	
	$query="UPDATE tbl_user_account SET status=? WHERE user_id=?";
	$prepare=$db->prepare($query);
	if($prepare->execute(array($status,$userid))){
		echo 1;	
	}else{
		echo 0;
	}
}else{
$bool=$_POST['bool'];
 
	//echo $status;
	$query="UPDATE tbl_user_account SET status=? WHERE group_id <> ? ";
	$prepare=$db->prepare($query);
	if($prepare->execute(array($bool,1))){
		echo 1;
	}else{
		echo 0;
	}		
}




?>