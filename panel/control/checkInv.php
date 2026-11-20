<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set   */

	$conn = new getConnection();
	$db = $conn->PDO();
	//$arr=array();

	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}	
	$pulloutNum=$_GET['pullout'];

	$qry="  SELECT a.pullout_num,a.vendor_num,	     "; 
	$qry.="        a.fromLoc,b.sku,sum(b.qty) as qty,c.on_hand ";		 
	$qry.=" FROM tbl_pullout_hdr a 			  		 ";
	$qry.="  LEFT JOIN tbl_pullout_dtl b 			 ";
	$qry.=" 	ON a.pullout_num=b.pullout_num 		 ";
	$qry.="  LEFT JOIN tbl_inventory c 				 ";
	$qry.=" 	ON c.vendor=a.vendor_num and c.store=a.fromLoc and c.sku=b.sku 		 ";
	$qry.=" WHERE a.pullout_num=? 			  		 ";
	$qry.="	GROUP BY a.vendor_num,a.fromLoc,b.sku    ";
 
	$prepare=$db->prepare($qry);
	$prepare->execute(array($pulloutNum));
	$fetch=$prepare->fetchAll();
	echo $fetch[0]['qty'];
 
	 
  
?>