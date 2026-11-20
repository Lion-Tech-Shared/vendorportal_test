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

	$qry="  SELECT a.vendor_num,a.fromLoc,b.sku,sum(b.qty) as qty ";
	$qry.=" FROM tbl_transfer_hdr a";
	$qry.=" LEFT JOIN tbl_transfer_dtl b ON a.trf_num=b.trf_num ";
	$qry.=" WHERE a.trf_num=? ";
	$qry.=" GROUP BY a.vendor_num,a.fromLoc,b.sku "; 	 
	$counter=''; 
	
	$prepare=$db->prepare($qry);
	
	//try{
	$prepare->execute(array($trf));
	$fetch=$prepare->fetchAll();
	//echo count($fetch);
	//exit;
	$flag=0;
	for($i=0;$i<count($fetch);$i++){
		$qty=$fetch[$i][3];
		$vendor=$fetch[$i][0];
		$store=$fetch[$i][1];
		$sku=$fetch[$i][2];
		$counter.="vendor:".$vendor."  store:".$store."   sku:".$sku."   qty:".$qty;
		/************UPDATE INVENTORY*************************************************************/	
		$qry="UPDATE tbl_inventory SET in_transit=in_transit+?,on_hand=on_hand-? WHERE vendor=? AND store=? AND sku=?";	 	 	
		$prepare=$db->prepare($qry); 
		try{
			$prepare->execute(array($qty,$qty,$vendor,$store,$sku));		
			//$counter.="<br>";
			//$counter.="update INV";	
			$qry= "	UPDATE tbl_notify SET txn = ? ";
			$qry.=" WHERE id = ?";		
			$prepare=$db->prepare($qry);	 
		 	$prepare->execute(array(5,$trf));		
		}catch(PDOException $e){
			 //echo 0;
			$qry="UPDATE tbl_inventory SET in_transit=in_transit-?,on_hand=on_hand+? WHERE vendor=? AND store=? AND sku=?";	 	 	
			$prepare->execute(array($qty,$qty,$vendor,$store,$sku));	
			//$prepare=$db->prepare($qry); 
			$flag=1;
			break;
			//$counter.="outer 0";
			//$counter.="<br>";
		}
		
	}		

	/**************UPDATE STATUS*******************************/
	if($flag==0){
		$qry="  UPDATE tbl_transfer_hdr SET status=? WHERE trf_num=? ";	 	 	
		$prepare=$db->prepare($qry); 
		try{
			$prepare->execute(array(1,$trf));			
			$counter.="<br>";
			$counter.="update Status";
			$counter.="<br>";
			$counter.="1";
			$counter.="<br>";
			//echo 1;
		}catch(exception $e){
			 //echo 0;
			$counter.="inner 0";
			$counter.="<br>";
		}	 
	}else{
		$qry="UPDATE tbl_inventory SET in_transit=in_transit+?,on_hand=on_hand-? WHERE vendor=? AND store=? AND sku=?";	 	 	
		$prepare=$db->prepare($qry); 		
		$prepare->execute(array($qty,$qty,$vendor,$store,$sku));	
	}
	
	/**************UPDATE STATUS*******************************/

	//}catch(exception $e){
		 //$counter.="parent 0";
		 //$counter.="<br>";
	//}

echo $flag;
	
	 
  
?>