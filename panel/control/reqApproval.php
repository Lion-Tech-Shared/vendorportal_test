<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();  
$db = $conn->PDO();
 
$vendor_num=$_SESSION['uid'];
$id=$_GET["id"];

//select operation
$qry=" SELECT * FROM tbl_requestadj ";
//$qry.=" SET is_approved=1 ";
$qry.=" WHERE id='".$id."'"; 
$prepare=$db->prepare($qry);
$prepare->execute();
$fetch=$prepare->fetchAll();

$oper=$fetch[0]["oper"];
$qty=$fetch[0]["qty"];
$store=$fetch[0]["store"];
$sku=$fetch[0]["sku"];

//echo "oper:".$oper."-qrty:".$qty."-store:".$store."-sku:".$sku;
//exit;
 
$qry=" UPDATE tbl_adjustment ";
$qry.=" SET is_approved=1 ";
$qry.=" WHERE id='".$id."'"; 

$prepare=$db->prepare($qry);
$prepare->execute();

$qry=" UPDATE tbl_inventory ";
$qry.=" SET on_hand=on_hand".$oper."".$qty;
$qry.=" WHERE sku=".$sku." AND store=".$store;
$qry.=" AND vendor=".$vendor_num; 

$prepare=$db->prepare($qry);
$prepare->execute();

$qry=" SELECT * FROM tbl_inventory ";
$qry.=" WHERE sku=? AND store=? AND vendor=? "; 
$prepare=$db->prepare($qry);
$prepare->execute(array($sku,$store,$vendor_num));
$fetch=$prepare->fetchAll();
if(count($fetch) < 1){
	if($oper=='+'){	
		$date=date("Y-m-d");
		$qry=" INSERT INTO tbl_inventory(vendor,store,sku,on_hand,lst_sales_dwnlod_date) ";
		$qry.=" VALUES(?,?,?,?,now()) ";		
		$prepare=$db->prepare($qry);
		$prepare->execute(array($vendor_num,$store,$sku,$qty));		 
	}
}

$qry="  UPDATE tbl_notify ";
$qry.=" 	SET vUpd = ? ";
$qry.=" WHERE id = ?";
 
$prepare=$db->prepare($qry);
$prepare->execute(array(1,$id));

echo 1;
 
?>