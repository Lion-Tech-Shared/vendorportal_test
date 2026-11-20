<?php
require "../../class-main/connection.class.php";
session_start();

if($_SESSION['gid']==2 || $_SESSION['gid']==1){
	exit;
}
$conn = new getConnection();
$db = $conn->PDO();

$dr=$_GET['dr'];
$gid=$_SESSION['gid'];
$drArr=array();
array_push($drArr, $dr);
$drs=str_replace('-', '_',$dr);
$qry="SELECT a.*,b.sku,b.stock_num,b.qty,c.descr as descr, ";
$qry.=" 	e.ccmnam,a.loc_code as store,a.status,coalesce(f.cost,0.00) as cost,@gid:=$gid as gid ";
$qry.=" FROM tbl_dr_hdr a ";
$qry.=" left join tbl_store_delivery b on a.dr_number=b.dr_number ";
$qry.=" left join tbl_invmst c on b.sku=c.sku ";
$qry.=" left join tbl_cssmct d on a.contract_num=d.cctnum ";
$qry.=" left join tbl_cssmst e on d.cctcss=e.ccmnum ";
$qry.=" left join tbl_sku_cost f on f.sku=c.sku WHERE a.dr_number=?";
//$qry="SELECT a.*,b.sku,b.stock_num,b.qty,d.ccmnam FROM tbl_dr_hdr a left join tbl_store_delivery b on a.dr_number=b.dr_number left join tbl_cssmct c on a.contract_num=c.cctnum left join tbl_cssmst d on d.ccmnum=c.cctcss left join tbl_user_info e on e.store=a.store WHERE a.dr_number=?";

/* Check if can Receive */
$qryCanRec=" SELECT canApprove,canReceive FROM tbl_privileges WHERE gid=? ";
$prepare=$db->prepare($qryCanRec);
$prepare->execute(array($gid));
$fetch=$prepare->fetchAll();
if($fetch[0][1]==1){
	$qry.=" AND b.qty > 0 ";
}



$prepare=$db->prepare($qry);
$prepare->execute(array($dr));	 
$row=$prepare->fetchAll();

 
echo json_encode($row);
?>