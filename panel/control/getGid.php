<?php

require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

if(!isset($_SESSION['uid'])){
	exit;
}
if($_SESSION['gid']==2 || $_SESSION['gid']==1){
	//exit;
}
$qry="SELECT * FROM tbl_privileges WHERE gid=?"; 
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['gid']));
$fetch=$prepare->fetchAll();
$array = array();
$arr['gid'] = $_SESSION['gid'];
$arr['cAppv'] = $fetch[0]["canApprove"];
$arr['cRcv'] = $fetch[0]["canReceive"];
$arr['isCoor'] = $fetch[0]["isCoor"];
$arr['isPa'] = $fetch[0]["IsPA"];
$arr['cVwOrdrNoti'] = $fetch[0]["canVwOrdrNoti"];
$arr['cVwRcvNoti'] = $fetch[0]["canVwRcvNoti"];
 
array_push($array, $arr);
//echo json_encode($array);
echo $_SESSION['gid']."-".$fetch[0]["canApprove"]."-".$fetch[0]["canReceive"]."-".$fetch[0]['isCoor']."-".$fetch[0]['IsPA']."-".$fetch[0]["canVwOrdrNoti"]."-".$fetch[0]["canVwRcvNoti"];

?>