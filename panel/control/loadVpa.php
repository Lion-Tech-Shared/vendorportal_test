<?php
require "../../class-main/connection.class.php";
session_start();
 
$conn = new getConnection();
$db = $conn->PDO();
$qry= ' SELECT a.lvendr as vendor_num,b.ccmnam as vendor_name ';
$qry.=' FROM tbl_lcsucn a ';
$qry.=' LEFT JOIN tbl_cssmst b  on a.lcnvdr=b.ccmnum '; 

$prepare=$db->prepare($qry);
$prepare->execute();
$fetch=$prepare->fetchAll();

echo json_encode($fetch);
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
