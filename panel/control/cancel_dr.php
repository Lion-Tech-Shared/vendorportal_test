<?php
require "../../class-main/connection.class.php";
session_start();
$dr=$_GET['dr'];

$qry="  SELECT * FROM tbl_dr_hdr a LEFT JOIN tbl_deliverytype b ON a.dr_number=b.dr_number ";
$qry.=" WHERE a.dr_number=? AND a.status=? AND b.isMerchApproved=?";
//$qry="SELECT a.*,b.sku,b.stock_num,b.qty,d.ccmnam FROM tbl_dr_hdr a left join tbl_store_delivery b on a.dr_number=b.dr_number left join tbl_cssmct c on a.contract_num=c.cctnum left join tbl_cssmst d on d.ccmnum=c.cctcss left join tbl_user_info e on e.store=a.store WHERE a.dr_number=?";
$conn = new getConnection();
$db = $conn->PDO();

$prepare=$db->prepare($qry);
$prepare->execute(array($dr,1,0));	 
$row=$prepare->fetchAll();

if(count($row)>0){
	$prepare=$db->prepare("UPDATE tbl_dr_hdr SET status=? WHERE dr_number=?");
	$prepare->execute(array(-1,$dr));	 
	echo 1;
}else{
	echo 0;
}

?>