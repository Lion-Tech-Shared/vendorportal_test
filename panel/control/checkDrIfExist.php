<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set */

	$conn = new getConnection();
	$db = $conn->PDO();
	//$arr=array();

	if(!isset($_GET['dr'])){
		header("Location:../home.php");
	}

	$dr=$_GET['dr'];
	$qry=" SELECT * FROM tbl_delwindow WHERE dr=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($dr));
	$fetch=$prepare->fetchAll();

	echo count($fetch);
 
?>