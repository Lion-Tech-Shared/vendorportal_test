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
	$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
	$canReceive=$fetch[0]['canReceive'];

	$isTotalPullout=$_GET['isTotalPullout'];

	$qry="  SELECT a.pullout_num,d.ccmnam, ";
	//$qry.="        b.sku,e.descr,b.qty,";
	$qry.="        a.fromLoc,a.date_created,f.stat_desc,g.ee_name,a.pick_up_date,h.stat_desc,i.strnam,a.actualPick_upDate ";
	$qry.=" 	   ,j.*";
	$qry.=" FROM tbl_pullout_hdr a ";
	//$qry.=" LEFT JOIN tbl_transfer_dtl b 		  ";
	//$qry.=" 							ON a.trf_num=b.trf_num 			  ";
	$qry.=" LEFT JOIN tbl_lcsucn c    		  ";
	$qry.=" 							ON a.vendor_num=c.lvendr  	      ";
	$qry.=" LEFT JOIN tbl_cssmst d    		  ";
	$qry.=" 							ON c.lcnvdr=d.ccmnum	  	      ";
	//$qry.=" LEFT JOIN tbl_invmst e    		  ";
	//$qry.=" 							ON b.sku=e.sku 			 	      ";
	$qry.=" LEFT JOIN tbl_pullout_status f    		  ";
	$qry.=" 							ON a.status=f.id		 	      ";
	$qry.=" LEFT JOIN tbl_user_info g    		  ";
	$qry.=" 							ON a.created_by=g.user_id 	      ";
	$qry.=" LEFT JOIN tbl_pullout_status h    		  ";
	$qry.=" 							ON h.id=a.status  	      		  ";
	$qry.=" LEFT JOIN tbl_store_master i 						   		  ";
	$qry.=" 							ON i.strnum=a.fromLoc   		  ";
	$qry.=" LEFT JOIN tbl_notify j 						   		  ";
	$qry.=" 							ON j.id=a.pullout_num   		  ";
	$qry.="  				 						  					  ";
	if($isTotalPullout==1){
		$qry.="	WHERE a.isTotalPullout=?";
	}else{
		$qry.="	WHERE a.isTotalPullout=?";
	}
	/****For Vendor to Approve*****/
	if($_SESSION['gid']==2){
		//$qry.="	AND a.status=0";
		$qry.=" AND a.vendor_num='".$_SESSION['uid']."'";
	}
	/****For Those who can receive**/
	if($canReceive==1){
		$qry.=" AND a.created_by='".$_SESSION['uid']."'";
	}

	$prepare=$db->prepare($qry);
	//echo $dr."-".$vp_dr."-".$delDate."-".$remarks;
	//exit;	 
	$prepare->execute(array($isTotalPullout));
	$fetch=$prepare->fetchAll();		
	echo json_encode($fetch);
	 
	 
  
?>