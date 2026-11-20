<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set */

	$conn = new getConnection();
	$db = $conn->PDO();
	$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
	//$arr=array();

	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}	
	$pulloutNum=$_GET['pullout'];
	$dtls=$_GET['dtls'];
	$pick_up=date("Y-m-d",strtotime($_GET['pick_up']));

	$qry=" UPDATE tbl_pullout_hdr SET details=?,status=?,pick_up_date=? WHERE pullout_num=? ";
	$prepare=$db->prepare($qry);

	try{
		$prepare->execute(array($dtls,1,$pick_up,$pulloutNum));
		//$fetch=$prepare->fetchAll();
		$qry = "UPDATE tbl_notify SET vUpd = ? WHERE id = ? ";
		$prepare = $db->prepare($qry);
		$prepare->execute(array(1,$pulloutNum));

		echo 1;
		exit;
	}catch(PDOException $e){
		echo "Error : ".$e->getMessage();
		exit;
	}
	
    

	 
  
?>