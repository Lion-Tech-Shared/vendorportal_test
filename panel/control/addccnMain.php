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

	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}	

	$fromLoc=$_GET['fromLoc'];
	$vendor=$_GET['vendor'];
	$isdept=$_GET['isdept'];
	$iclas=$_GET['iclas'];		


	$qry="  INSERT INTO tbl_ccnMain VALUES(?,?,?,?) ";
	$prepare=$db->prepare($qry); 
	try {				
		$prepare->execute(array($_SESSION['uid'],$vendor,$isdept,$iclas));		
		echo 1;
	}catch(PDOException $e){
		if($e->errorInfo[1] == 1062){
			echo "Already exist";
		}else{
			echo $e->getMessage();
		}	
	}	
	 

?>