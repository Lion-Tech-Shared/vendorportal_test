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
 
$query='SELECT * FROM  tbl_user_account WHERE user_id = ?';
$prepare=$db->prepare($query);
$prepare->execute(array($userid));
$fetch=$prepare->fetchAll();

if( count($fetch) > 0 ){
	echo "exist";	
}else{
	echo 'absent';
  
}
 
?>