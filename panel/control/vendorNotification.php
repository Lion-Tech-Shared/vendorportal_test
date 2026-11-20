<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

if(!isset($_SESSION["uid"]) ) {
      exit;
} 
/* For Vendor Only */
if($_SESSION['gid'] != 2){
	header("Location:../home.php");
}
/* For Vendor Only */

$qry=" SELECT count(*) FROM tbl_deliverytype a  ";
$qry.="  LEFT JOIN tbl_dr_hdr b  ";
$qry.="     ON a.dr_number=b.dr_number  ";
$qry.=" WHERE b.loc_code ";
$qry.="  IN(SELECT LCSTOR FROM tbl_lcsecloc WHERE lcuseid=?) ";
$qry.=" AND a.isMerchApproved <> ? AND a.isViewedV=? AND b.status=?";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],0,0,1));
$fetch=$prepare->fetchAll();
$arr=array();

//$flag['count']=count($fetch);
//array_push($arr, $flag);
//echo json_encode($arr);
echo $fetch[0][0];

?>
