<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection(); 
$db = $conn->PDO();
$vendor_num=$_POST['vendor_num'];
//$qry='SELECT distinct d.lvendr as vendor_num,c.ccmnam as vendor_name FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum';
//$qry='SELECT DISTINCT D.STORE AS STORE,E.STRNAM AS STORE_NAME FROM TBL_LCSUCN A LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS LEFT JOIN TBL_INVENTORY D ON C.CCTLOC=D.STORE LEFT JOIN TBL_STORE_MASTER E ON E.STRNUM=D.STORE WHERE A.LVENDR='.$vendor_num.' AND D.STORE is not null';
$qry='SELECT b.dptnam as dept,b.isdept,b.iclas FROM tbl_lcsucn a ';
$qry.=' LEFT JOIN tbl_invdpt b on b.isdept=a.lcnvdr where b.idept=990 and a.lvendr='.$vendor_num.' AND b.isclas=0 AND b.iclas <> 0';
$prepare=$db->prepare($qry); 
$prepare->execute();
$fetch=$prepare->fetchAll();

echo json_encode($fetch);


//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
