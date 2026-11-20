<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

if($_SESSION['gid']!=1){
	exit;
}
$flag=$_GET['flag'];
$data=explode("-",$_GET['data']);
$gid=$data[0];
$col=$data[1];

$column=($col==1?"canViewSales":($col==2?"canApprove":($col==3?"canReceive":($col==5?"IsPA":($col==6?"isCoor":"isLocked")))));

$conn = new getConnection();
$db = $conn->PDO();

$qry="UPDATE tbl_privileges SET ".$column."=? WHERE gid=?";
$prepare=$db->prepare($qry);
$prepare->execute(array($flag,$gid));	 

 
echo 1;
?>