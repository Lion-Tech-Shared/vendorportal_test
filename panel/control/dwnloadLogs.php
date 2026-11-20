<?php
require "../../class-main/connection.class.php";
session_start();
 

$qry="SELECT * FROM tbl_control ORDER BY endDwnload desc";
//$qry="SELECT a.*,b.sku,b.stock_num,b.qty,d.ccmnam FROM tbl_dr_hdr a left join tbl_store_delivery b on a.dr_number=b.dr_number left join tbl_cssmct c on a.contract_num=c.cctnum left join tbl_cssmst d on d.ccmnum=c.cctcss left join tbl_user_info e on e.store=a.store WHERE a.dr_number=?";
$conn = new getConnection();
$db = $conn->PDO();

$prepare=$db->prepare($qry);
$prepare->execute();	 
$row=$prepare->fetchAll();

echo json_encode($row);

?>