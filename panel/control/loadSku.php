<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

$sdept=$_GET['sdept'];
$clas=$_GET['clas'];

$qry="SELECT sku,descr FROM tbl_invmst WHERE dept=? AND sub_dept=? AND class=? AND sub_class!=?  ";
$prepare=$db->prepare($qry);
//array(990,$sdept,$cls,0)
$prepare->execute(array(990,$sdept,$clas,0));
//array(990,$sdept,$cls,0)
$fetch=$prepare->fetchAll();

echo json_encode($fetch);
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
