<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();	 /* redirect if session not set */



$conn = new getConnection();
$db = $conn->PDO();
 
$isWhse=(isset($_SESSION['isWhse'])?1:0);
$isNot='';
if($isWhse==1){
	$strhdo='W';
	$qry="  SELECT DISTINCT a.lvendr AS vendor,b.ccmnam as vendor_name FROM tbl_lcsucn a ";
	$qry.="	LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum  ";
	$qry.="	LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss  ";
	$qry.=" WHERE  c.cctloc in( ";
	$qry.="    SELECT strnum FROM tbl_store_master WHERE strwhs IN( ";
	$qry.="       SELECT b.strnum FROM `tbl_lcsecloc` a ";
	$qry.="       LEFT JOIN tbl_store_master b ON a.lcstor=b.strnum ";
	/*												     changeable to accomodate SMR		*/
	$qry.="       WHERE a.lcuseid=? AND b.strhdo=? AND b.strsbu ".$isNot." IN('DS','HG') ";
	$qry.="    ) AND strhdo=? ";
	$qry.=" )   AND curdate() BETWEEN date(cctlsd) AND date(cctled)";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['uid'],'W','S'));
}else{
	$strhdo='S';
	$isNot=' NOT ';

	$qry=" SELECT DISTINCT a.lvendr AS vendor,b.ccmnam as vendor_name FROM tbl_lcsucn a ";
	$qry.="	LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum  ";
	$qry.="	LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss  ";
	//$qry.=" WHERE  c.cctloc in( ";
	//$qry.="       SELECT b.strnum FROM `tbl_lcseclocpa` a ";
	//$qry.="       LEFT JOIN tbl_store_master b ON a.lcstor=b.strnum "; 
	//$qry.="       WHERE a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG') ";
	//$qry.=" ) AND curdate() BETWEEN c.cctlsd AND c.cctled  ";
	$qry.=" WHERE a.lvendr IN( SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?)";
	$prepare=$db->prepare($qry);
	//$prepare->execute(array($_SESSION['uid'],'S'));
	$prepare->execute(array($_SESSION['uid']));
}

$fetch=$prepare->fetchAll();

echo json_encode($fetch);
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
