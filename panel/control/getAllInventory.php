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

$qry="  SELECT   					";
$qry.="		a.sku,b.descr,a.on_hand,d.ccmnam as vendor_name,e.strnam as store_name   ";
$qry.=" FROM 						";
$qry.="		tbl_inventory a  		";
$qry.=" LEFT JOIN 					";
$qry.="		tbl_invmst b 			";
$qry.=" ON   						";
$qry.="		a.sku=b.sku 			";
$qry.=" LEFT JOIN 					";
$qry.="		tbl_lcsucn c 			";
$qry.=" ON   						";
$qry.="		c.lvendr=a.vendor		";
$qry.=" LEFT JOIN 					";
$qry.="		tbl_cssmst d 			";
$qry.=" ON   						";
$qry.="		d.ccmnum=c.lcnvdr 		";
$qry.=" LEFT JOIN 					";
$qry.="		tbl_store_master e 			";
$qry.=" ON   						";
$qry.="		e.strnum=a.store		";
$qry.=" WHERE 						";
$qry.="		a.vendor=? AND a.store=?";
$qry.=" AND a.on_hand > 0";

$prepare=$db->prepare($qry);
$prepare->execute(array($vendor,$store));
$fetch=$prepare->fetchAll();
 
echo json_encode($fetch);

?>