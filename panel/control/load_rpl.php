<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();	 /* redirect if session not set */

$conn = new getConnection();
$db = $conn->PDO();
//$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
//$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
 
if(!isset($_GET['flag'])){
	header("Location:../home.php");
}	
 
$qry="  SELECT a.*,b.descr FROM tbl_sku_stp a LEFT JOIN tbl_invmst b on a.sku=b.sku"; 
$qry.=" WHERE a.store IN(SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid=?)";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid']));
$fetch=$prepare->fetchAll();

echo json_encode($fetch);
 

?>