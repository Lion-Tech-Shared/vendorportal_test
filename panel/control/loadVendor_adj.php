<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['gid']));
$fetch=$prepare->fetchAll();
$IsPa=$fetch[0]['IsPA'];  
if($IsPa==1){
	/*$qry=" SELECT v_code,v_name FROM  tbl_vendor_master";
 
	$qry2=" SELECT distinct a.lvendr as v_code,d.v_name FROM  tbl_lcsucn a";
	$qry2.=" left join tbl_cssmst b on a.lcnvdr=b.ccmnum ";
	$qry2.=" left join tbl_cssmct c on b.ccmnum=c.cctcss ";
	$qry2.=" left join tbl_vendor_master d on d.v_code=a.lvendr ";
	$qry2.=" WHERE c.cctloc=(SELECT STORE FROM TBL_USER_INFO WHERE user_id='".$_SESSION['uid']."')";*/
	$qry2=" SELECT v_code,v_name FROM  tbl_vendor_master WHERE v_code IN( SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?) ";
	$prepare=$db->prepare($qry2);
	$prepare->execute(array($_SESSION['uid']));
	$fetch=$prepare->fetchAll();
 
	echo json_encode($fetch);
}


//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
