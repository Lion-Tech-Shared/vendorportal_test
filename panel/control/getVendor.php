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
	$qry="  SELECT lvendr FROM tbl_pa_vendor ";	
	$qry.=" WHERE  lcuseid=?			     ";	
	$prepare=$db->prepare($qry);
	 
	try{
		$prepare->execute(array($paId));
		$fetch=$prepare->fetchAll();		
		if(count($fetch)>0){
			echo $fetch[0][0];
		}else{
			echo 0;
		}
		
	}catch(exception $e){
		 
	}
	 
 
?>