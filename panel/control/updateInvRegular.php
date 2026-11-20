<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set   */

	$conn = new getConnection();
	$db = $conn->PDO(); 
	$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
	//$arr=array();

	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}	
	$pulloutNum=$_GET['pullout'];
	$isForfeited=$_GET['isForfeited'];
	
	$qry="  SELECT a.pullout_num,a.vendor_num,	     "; 
	$qry.="        a.fromLoc,b.sku,sum(b.qty) as qty,isTotalPullout ";		 
	$qry.=" FROM tbl_pullout_hdr a 			  		 ";
	$qry.="  LEFT JOIN tbl_pullout_dtl b 			 ";
	$qry.=" 	ON a.pullout_num=b.pullout_num 		 ";
	//$qry.="  LEFT JOIN tbl_inventory c 				 ";
	//$qry.=" 	ON c.vendor=a.vendor_num and c.store=a.fromLoc and c.sku=b.sku 		 ";
	$qry.=" WHERE a.pullout_num=? 			  		 ";
	$qry.="	GROUP BY a.vendor_num,a.fromLoc,b.sku    ";
 	
	$prepare=$db->prepare($qry);
	$prepare->execute(array($pulloutNum));
	$fetch=$prepare->fetchAll();
    $isTotalPullout=$fetch[0]['isTotalPullout'];
    $remarks=($isTotalPullout==1?"Total Pullout":"Regular Pullout");
	for($i=0;$i<count($fetch);$i++){

		$qty=$fetch[$i]['qty'];
		$vendor=$fetch[$i]['vendor_num'];
		$store=$fetch[$i]['fromLoc'];
		$sku=$fetch[$i]['sku'];

		$qry=" UPDATE tbl_inventory SET on_hand=on_hand-? WHERE vendor=? AND store=? AND sku=?";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($qty,$vendor,$store,$sku));
	}
	if($isForfeited==1){
		$qry=" UPDATE tbl_pullout_hdr SET status=? WHERE pullout_num=?";
		$prepare=$db->prepare($qry);
		$prepare->execute(array(3,$pulloutNum));
	}
	else{
		$qry=" UPDATE tbl_pullout_hdr SET status=?,actualPick_upDate=now() WHERE pullout_num=?";
		$prepare=$db->prepare($qry);
		$prepare->execute(array(2,$pulloutNum));
	}
	$qry = "UPDATE tbl_notify SET pUpd = ? WHERE id = ? ";
	$prepare = $db->prepare($qry);
	$prepare->execute(array(1,$pulloutNum));

	/*$qry=" INSERT INTO tbl_notificationFlag(id,txn,remarks,entry_date) VALUES(?,?,?,now())";
	$prepare=$db->prepare($qry);//3=pullout
	$prepare->execute(array($pulloutNum,3,$remarks));
	*/
 	echo 1;
	 
  
?>