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
 
if(!isset($_GET['flag'])){
	header("Location:../home.php");
}	
$sku=$_GET['sku'];
$min=$_GET['min'];
$max=$_GET['max'];
$flag=$_GET['flag'];

//echo $sku."-".$min."-".$max."-".$flag;

if($flag=='add'){
	$qry="  INSERT INTO tbl_sku_stp(store,sku,min,max) "; 
	$qry.=" SELECT lcstor,@sku:=$sku,@min:=$min,@max:=$max FROM tbl_lcsecloc WHERE lcuseid=? ";
	$prepare=$db->prepare($qry);
	try{
		$prepare->execute(array($_SESSION['uid']));
		echo 1;
		exit;
	}catch(PDOException $e){
		echo "Error : ".$e->getMessage();
		exit;
	}
}
else if($flag=='edit'){
	$qry="  UPDATE tbl_sku_stp "; 
	$qry.=" SET min=?,max=?";
	$qry.=" WHERE sku=? AND store=(SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid=?)";

	$prepare=$db->prepare($qry);
	try{
		$prepare->execute(array($min,$max,$sku,$_SESSION['uid']));
		echo 1;
		exit;
	}catch(PDOException $e){
		echo "Error : ".$e->getMessage();
		exit;
	}
}
else if($flag=='remove'){
	$qry="  DELETE FROM tbl_sku_stp "; 	 
	$qry.=" WHERE sku=? AND store=(SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid=?)";

	$prepare=$db->prepare($qry);
	try{
		$prepare->execute(array($sku,$_SESSION['uid']));
		echo 1;
		exit;
	}catch(PDOException $e){
		echo "Error : ".$e->getMessage();
		exit;
	}
}

?>