<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

if(!isset($_SESSION["uid"]) ) {
      exit;
} 
if($_SESSION['gid'] != 2){
	header("Location:../home.php");
}
if(!isset($_GET['flag'])){
	header("Location:../home.php");
} 
$isTotalPullout=$_GET['isTotalPullout'];

/*---------------------- Get row/data for notification------------------- */
$qry=" SELECT count(*) FROM tbl_pullout_hdr WHERE vendor_num=? AND status=? AND isTotalPullout=?";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],0,$isTotalPullout));
$fetch=$prepare->fetchAll();
$row=$fetch[0][0];
echo $row;

?>
