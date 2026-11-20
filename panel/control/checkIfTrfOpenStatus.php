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
	$trf=$_GET['txt_trf'];
	$qry="  SELECT * FROM tbl_transfer_hdr WHERE trf_num=? ";	 	 	
	$prepare=$db->prepare($qry); 
	try{
		$prepare->execute(array($trf));
		$fetch=$prepare->fetchAll();		
		if(count($fetch)>0){
			echo $fetch[0]['status'];
		}else{
			echo -1;
		}
		
	}catch(exception $e){
		 echo -1;
	}
	 
  
?>