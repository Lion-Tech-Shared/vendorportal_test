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
		//header("Location:../home.php");
	}	
	$sub_dept=$_GET['dept'];

	$qry="  SELECT a.sku,a.descr FROM tbl_invmst a  ";	 
	$qry.=" WHERE  a.dept=?	AND sub_dept=?	 	    ";
	
	$prepare=$db->prepare($qry);
 
	$prepare->execute(array(990,$sub_dept));
	$fetch=$prepare->fetchAll();		

	echo json_encode($fetch);
	 
	 
  
?>