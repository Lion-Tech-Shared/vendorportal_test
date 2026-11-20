<?php
require "../../class-main/connection.class.php";
session_start();


 
$conn = new getConnection();
$db = $conn->PDO();

$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['gid']));
$fetch=$prepare->fetchAll();
$canReceive=$fetch[0]['canReceive'];

if($canReceive==1){
	//$qry='  SELECT distinct a.lvendr as vendor_num,c.ccmnam as vendor_name,d.store,e.lcstor ';
	$qry='  SELECT distinct a.lvendr as vendor_num,c.ccmnam as vendor_name';
	$qry.=' FROM tbl_pa_vendor a';
	$qry.=' LEFT JOIN tbl_lcsucn b on a.lvendr=b.lvendr ';
	$qry.=' LEFT JOIN tbl_cssmst c on b.lcnvdr=c.ccmnum ';
	$qry.=' LEFT JOIN tbl_inventory d on d.vendor=a.lvendr ';
   	$qry.=" LEFT JOIN tbl_lcseclocpa e on e.lcstor=d.store ";
	$qry.=" WHERE a.lcuseid=? AND e.lcstor is not null  ";
	//$qry.=' left join tbl_lcsucn d on d.lcnvdr=c.ccmnum ';
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['uid']));
}else{
	$qry='  SELECT distinct d.lvendr as vendor_num,c.ccmnam as vendor_name ';
	$qry.=' FROM `tbl_inventory` ';
	$qry.=' a LEFT JOIN tbl_invmst b on a.sku=b.sku ';
	$qry.=' left join tbl_cssmst c on b.sub_dept=c.ccmnum ';
	$qry.=' left join tbl_lcsucn d on d.lcnvdr=c.ccmnum ';	
	$qry.=" WHERE b.sku is not null"; /* added this line due to null sku in invmst*/
	$prepare=$db->prepare($qry);
	$prepare->execute();
}	   

$fetch=$prepare->fetchAll();
echo json_encode($fetch);
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
