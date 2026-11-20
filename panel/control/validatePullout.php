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
	$pulloutNum=$_GET['pulloutNum'];

	$qry="  SELECT * FROM tbl_pullout_hdr WHERE pullout_num=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($pulloutNum));
	$fetch=$prepare->fetchAll();
	echo $fetch[0]['status'];
 
	 
  
?>