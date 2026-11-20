<?php
session_start();

require_once "../../class-main/connection.class.php";

if(!isset($_SESSION['uid']) || !$_POST['userid']){	 
	header("Location:../home.php");	
	exit;
}
 
$con=new getConnection();
$db=$con->PDO();
  
$userid=$_POST['userid'];
$fn=$_POST['fn'];
$department_position=$_POST['dep'];

$query='SELECT * FROM  tbl_employee_master WHERE user_id = ?';
$prepare=$db->prepare($query);
$prepare->execute(array($userid));
$fetch=$prepare->fetchAll();

$dtl=$fn." - ".$department_position;

if( count($fetch) > 0 ){
	echo "exist";	
}else{
	$query='SELECT * FROM  tbl_vendor_master WHERE v_code = ?';
	$prepare=$db->prepare($query);
	$prepare->execute(array($userid));
	$fetch=$prepare->fetchAll();
 	
	if( count($fetch) > 0 ){
		echo "exist";	
	}else{
		$query='SELECT * FROM  tbl_user_account WHERE user_id = ?';
		$prepare=$db->prepare($query);
		$prepare->execute(array($userid));
		$fetch=$prepare->fetchAll();
	 	
		if( count($fetch) > 0 ){
			echo "exist";	
		}else{
	 		$query='INSERT INTO tbl_employee_master VALUES(?,?)';
			$prepare=$db->prepare($query);
			if($prepare->execute(array($userid,$dtl))){
				echo 'success';	
			}else{
				echo 'failed';	
			}
		}
		
	}
}
 
?>