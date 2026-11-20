<?php
require "../../class-main/connection.class.php";
session_start();
$conn = new getConnection();
$db = $conn->PDO();

$cells=$_POST['arr'];
foreach ($cells as $key => $value) {
	 //select operation
	$qry=" SELECT * FROM tbl_requestadj ";
	//$qry.=" SET is_approved=1 ";

	$qry.=" WHERE id='".$value."'"; 
	$prepare=$db->prepare($qry);
	$prepare->execute();
	$fetch=$prepare->fetchAll();

	$oper=$fetch[0]["oper"];
	$qty=$fetch[0]["qty"];
	$store=$fetch[0]["store"];
	$sku=$fetch[0]["sku"];
	$vendor_num=$fetch[0]["vendor"];
	 
	 
	$qry=" UPDATE tbl_adjustment ";
	$qry.=" SET is_approved=1 ";
	$qry.=" WHERE id='".$value."'"; 

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
	$fetch2=$prepare->fetchAll();
	if(count($fetch2) < 1){
		if($oper=='+'){	
			$date=date("Y-m-d");
			$qry=" INSERT INTO tbl_inventory(vendor,store,sku,on_hand,lst_sales_dwnlod_date) ";
			$qry.=" VALUES(?,?,?,?,?) ";
			$QRY.=" ";		
			$prepare=$db->prepare($qry);
			$prepare->execute(array($vendor_num,$store,$sku,$qty,$date));		 
		}
	}
}


echo 1;
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
