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
	$trf=$_GET['txt_trf'];
	$sku=$_GET['sku'];

	$qry="  SELECT a.vendor_num,a.fromLoc,b.sku,a.toLoc,sum(b.qty) as qty ";
	$qry.=" FROM tbl_transfer_hdr a";
	$qry.=" LEFT JOIN tbl_transfer_dtl b ON a.trf_num=b.trf_num ";
	$qry.=" WHERE a.trf_num=? ";
	$qry.=" GROUP BY a.vendor_num,a.fromLoc,b.sku,a.toLoc "; 	 
	 
	
	$prepare=$db->prepare($qry);
	$errorFlag=0;
	
	$prepare->execute(array($trf));
	$fetch=$prepare->fetchAll();

	for($i=0;$i<count($fetch);$i++){			 
		$vendor=$fetch[$i][0];
		$fromLoc=$fetch[$i][1];
		$sku=$fetch[$i][2];
		$toLoc=$fetch[$i][3];
		$qty=$fetch[$i][4];

		/**check if vendor ,store,sku, to loc exist in inventory**/
		$qry="  SELECT * FROM tbl_inventory ";
		$qry.=" WHERE vendor=? AND store=? AND sku=? ";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($vendor,$toLoc,$sku));
		$fetch2=$prepare->fetchAll();
		if(count($fetch2)>0){
			/************UPDATE From location transit*************************************************************/	
		}

	}
  
?>