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

	$qry="  SELECT a.user_id,a.ee_name FROM tbl_user_info a ";
	$qry.=" LEFT JOIN tbl_user_account b 		  ";
	$qry.=" 	ON a.user_id=b.user_id 			  ";
	$qry.=" LEFT JOIN tbl_privileges c    		  ";
	$qry.=" 	ON b.group_id=c.gid		  	      ";
	$qry.=" WHERE c.IsPA=?						  ";
	
	$prepare=$db->prepare($qry);

	//echo $dr."-".$vp_dr."-".$delDate."-".$remarks;
	//exit;
	try{
		$prepare->execute(array(1));
		$fetch=$prepare->fetchAll();		
		echo json_encode($fetch);
	}catch(exception $e){
		 
	}
	 
  
?>