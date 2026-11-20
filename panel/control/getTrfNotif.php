<?php

require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();

if($_SESSION['gid']!=2){
	header("Location:../home.php");
} 

$conn = new getConnection();
$db = $conn->PDO();
$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);




$qry=" 	SELECT a.id,a.isVendorVwd,a.vendorVwdDate,b.* ";
$qry.=" FROM tbl_notificationFlag a  ";
$qry.=" LEFT JOIN tbl_transfer_hdr b on a.id=b.trf_num  ";
$qry.=" WHERE b.vendor_num=? AND a.isVendorVwd=? AND b.status=?"; 

$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],0,2));

$fetch=$prepare->fetchAll();

echo json_encode($fetch);

?>