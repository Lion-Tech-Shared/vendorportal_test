<?php

require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();

if($_GET['flag']!=2){
	header("Location:../home.php");
} 

$conn = new getConnection();
$db = $conn->PDO();
$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);



$qry="  SELECT * FROM tbl_ccnMain a ";
$qry.=" LEFT JOIN  tbl_ccncoordDept b on a.isdept=b.isdept and a.iclas=b.iclas     ";
$qry.=" LEFT JOIN  tbl_pullout_hdr c on b.id=c.pullout_num AND c.vendor_num=a.vendor  ";
$qry.=" WHERE a.user_id=? and b.isCoorVwd=? AND c.status=?						   ";
 
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],0,2));

$fetch=$prepare->fetchAll();

echo json_encode($fetch);

?>