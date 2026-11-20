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
//$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
//$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$txn=$_GET['txn'];
$status=2;

$qry="  SELECT b.id FROM tbl_ccnMain a ";
$qry.="  LEFT JOIN  tbl_ccncoordDept b on a.isdept=b.isdept and a.iclas=b.iclas     ";

if($txn==2){	
	$qry.="  LEFT JOIN  tbl_transfer_hdr c on b.id=c.trf_num AND c.vendor_num=a.vendor  ";
	$qry.="  WHERE a.user_id=? and b.isCoorVwd=? AND c.status=?						   ";
}else if($txn==3){	
	$qry.=" LEFT JOIN  tbl_pullout_hdr c on b.id=c.pullout_num AND c.vendor_num=a.vendor  ";
	$qry.=" WHERE a.user_id=? and b.isCoorVwd=? AND c.status=?	";	
}else if($txn==4){	
	$qry.=" LEFT JOIN  tbl_dr_hdr c on b.id=c.dr_number AND c.vendor_code=a.vendor  ";
	$qry.=" WHERE a.user_id=? and b.isCoorVwd=? AND c.status=?						   "; 
	$status=0;
}else if($txn==5){	
	$qry.=" LEFT JOIN  tbl_requestadj c on b.id=c.id AND c.vendor=a.vendor  ";
	$qry.=" LEFT JOIN  tbl_adjustment d on c.id=d.id						";
	$qry.=" WHERE a.user_id=? and b.isCoorVwd=? AND d.is_approved=?		    "; 
	$status=1;
}
 
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],0,$status));
$fetch=$prepare->fetchAll(PDO::FETCH_COLUMN, 0); 

$in  = str_repeat('?,', count($fetch) - 1) . '?';
//$qry.="  WHERE id IN('".implode("','", $fetch)."')  ";

$qry="  UPDATE tbl_ccncoordDept SET isCoorVwd=?   "; 
$qry.="  WHERE id IN('".implode("','", $fetch)."')  ";
 
 
$prepare=$db->prepare($qry);
$prepare->execute(array(1));

echo 1;
?>