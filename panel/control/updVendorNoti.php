<?php

require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();  

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();

if($_GET['flag']!=2){
	header("Location:../home.php");
} 

$conn = new getConnection();
$db = $conn->PDO();
//$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
//$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$txn=$_GET['txn'];
$status=2;



$qry=" 	SELECT a.id ";
$qry.=" FROM tbl_notificationFlag a  ";
$qry.=" LEFT JOIN tbl_transfer_hdr b on a.id=b.trf_num  ";
$qry.=" WHERE b.vendor_num=? AND a.isVendorVwd=? AND b.status=?"; 

$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],0,$status));
$fetch=$prepare->fetchAll(PDO::FETCH_COLUMN, 0); 

$in  = str_repeat('?,', count($fetch) - 1) . '?';
//$qry.="  WHERE id IN('".implode("','", $fetch)."')  ";

$qry="  UPDATE tbl_notificationFlag SET isVendorVwd=?   "; 
$qry.="  WHERE id IN('".implode("','", $fetch)."')  ";
 
 
$prepare=$db->prepare($qry);
$prepare->execute(array(1));

echo 1;
?>