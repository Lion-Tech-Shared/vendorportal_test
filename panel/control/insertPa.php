<?php 
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set */

	$conn = new getConnection();
	$db = $conn->PDO();
	//$arr=array();

	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}	

	$paId=$_GET['paId'];
	$myVendor=$_GET['myVendor'];
	$storeArr=$_GET['storeArr'];
	foreach ($storeArr as $key => $value) {
		$qry=" INSERT INTO tbl_lcseclocpa ";
		$qry.=" VALUES(?,?)  ";
	 	$prepare=$db->prepare($qry);	

		try{
			$prepare->execute(array($paId,$value));
		}catch(exception $e){
			 
		}
	}
	 
 	$qry= "  INSERT INTO tbl_pa_vendor 	";
	$qry.="  VALUES(?,?)  				";
 	$prepare=$db->prepare($qry);
 	try{
		$prepare->execute(array($paId,$myVendor));
	}catch(exception $e){
		 
	}
	echo '1';
	 
 
?>