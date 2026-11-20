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

	if(!isset($_GET['dr'])){
		header("Location:../home.php");
	}

	$dr=$_GET['dr'];
	$vp_dr=$_GET['vp_dr'];
	$delDate=$_GET['delDate'];
	$remarks=$_GET['remarks'];

	$qry="  INSERT INTO tbl_delwindow  ";
	$qry.=" VALUES(?,?,?,?,NOW()) ";
	$prepare=$db->prepare($qry);
	//echo $dr."-".$vp_dr."-".$delDate."-".$remarks;
	//exit;
	try{
		$prepare->execute(array($dr,$vp_dr,date("Y-m-d",strtotime($delDate)),$remarks));
		try{
			$qry=" UPDATE tbl_dr_hdr SET status=? WHERE dr_number=?";
			$prepare=$db->prepare($qry);
			$prepare->execute(array(2,$vp_dr));	

			$qry=" UPDATE tbl_notify SET vUpd = ? WHERE id=?";
			$prepare=$db->prepare($qry);
			$prepare->execute(array(1,$vp_dr));		
		}
		catch(PDOException $e){
			$qry=" DELETE FROM tbl_delwindow WHERE dr_number=?";
			$prepare=$db->prepare($qry);
			$prepare->execute(array($vp_dr));			

			$qry=" UPDATE tbl_notify SET vUpd = ? WHERE id=?";
			$prepare=$db->prepare($qry);
			$prepare->execute(array(0,$vp_dr));	

			echo 0;
			exit;
		}		 
		echo 1;
	}
	catch(PDOException $e){
		$qry=" DELETE FROM tbl_delwindow WHERE dr_number=?";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($vp_dr));	
		echo 0;
	}
 
?>