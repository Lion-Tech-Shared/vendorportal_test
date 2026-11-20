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
		//header("Location:../home.php");
	}	

	$qry="  SELECT distinct a.lcnvdr,b.ccmnam FROM tbl_lcsucn a    							 ";	 
	$qry.=" LEFT JOIN tbl_cssmst b ON a.lcnvdr=b.ccmnum								 ";
	$qry.=" LEFT JOIN tbl_cssmct c ON b.ccmnum=c.cctcss 					 		 ";
	$qry.=" WHERE  c.cctloc=(SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid=?)		 ";
	
	$prepare=$db->prepare($qry);
 
	$prepare->execute(array($_SESSION['uid']));
	$fetch=$prepare->fetchAll();		

	echo json_encode($fetch);
	 
	 
  
?>