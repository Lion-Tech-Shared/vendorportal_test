<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

if(!isset($_SESSION["uid"]) ) {
      exit;
} 
if($_SESSION['gid'] != 2){
	//header("Location:../home.php");
}
if(!isset($_GET['flag'])){
	header("Location:../home.php");
} 
$isTotalPullout=$_GET['isTotalPullout'];

/*---------------------- Get row/data for notification------------------- */
$qry="  SELECT COUNT(*) FROM tbl_pa_vendor a ";
$qry.=" LEFT JOIN tbl_pullout_hdr b on a.lcuseid=b.created_by ";
$qry.=" LEFT JOIN tbl_lcseclocpa c on c.lcstor=b.fromLoc and a.lcuseid=c.lcuseid";
$qry.=" WHERE a.lcuseid=? AND b.status=? AND b.isTotalPullout=?";

$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],1,$isTotalPullout));
$fetch=$prepare->fetchAll();
$row=0;
if($fetch[0][0]>0)
 	$row=$fetch[0][0];

echo $row; 

?>
