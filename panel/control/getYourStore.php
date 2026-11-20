<?php
	require "../../class-main/connection.class.php";
	session_start();

	$con=new getConnection();
	$db=$con->PDO();	                
	$qry='SELECT a.lcstor,b.strnam FROM tbl_lcsecloc a LEFT JOIN tbl_store_master b on a.lcstor=b.strnum WHERE a.lcuseid=? ';
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['uid']));
	$fetch=$prepare->fetchAll();
	
	$str=$fetch[0][0]." : ".$fetch[0][1]; 

    echo json_encode($fetch);
?>