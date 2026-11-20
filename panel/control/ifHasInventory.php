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
	$vendor=$_GET['vendor'];
	$store=$_GET['store'];
	$sku=$_GET['sku'];

	$qry="  SELECT * FROM tbl_inventory "; 	 
	$qry.=" WHERE vendor=?	AND store=? AND sku=? ";
	
	$prepare=$db->prepare($qry);

	//echo $dr."-".$vp_dr."-".$delDate."-".$remarks;
	//exit;
	try{
		$prepare->execute(array($vendor,$store,$sku));
		$fetch=$prepare->fetchAll();		
		if(count($fetch)>0){
			echo $fetch[0]['on_hand'];
		}else{
			echo 0;
		}
		//echo json_encode($fetch);
	}catch(exception $e){
		 
	}
	/***/
  
?>
