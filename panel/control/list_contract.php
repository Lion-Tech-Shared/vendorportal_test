<?php
	require "../../class-main/connection.class.php";
	session_start();
	$contractNum=$_GET['contractNum'];

	if(!is_numeric($contractNum)){
		echo 0;
		exit;
	}

	$conn = new getConnection();
	$db = $conn->PDO();
	//$prepare=$db->prepare('SELECT a.cctnum,b.ccmnam,a.cctloc FROM tbl_cssmct a left join tbl_cssmst b on a.cctcss=b.ccmnum ');
	$prepare=$db->prepare('SELECT COUNT(*) as cnt FROM tbl_cssmct WHERE cctnum='.$contractNum);
	$prepare->execute();
	$row=$prepare->fetchAll();	 

	if($row[0]['cnt']>0)
		echo 1;
	else	
		echo 0;
 
?>