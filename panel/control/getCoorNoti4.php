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
//$qry.=" LEFT JOIN  tbl_dr_hdr c on b.id=c.dr_number AND c.vendor_code=a.vendor  ";
//$qry.=" WHERE a.user_id=? and b.isCoorVwd=? AND c.status=?						   ";
$qry.=" LEFT JOIN  tbl_requestadj c on b.id=c.id AND c.vendor=a.vendor  ";
$qry.=" LEFT JOIN  tbl_adjustment d on c.id=d.id						";
$qry.=" WHERE a.user_id=? and b.isCoorVwd=? AND d.is_approved=?		    "; 
 
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],0,1));

$fetch=$prepare->fetchAll();

echo json_encode($fetch);

?>