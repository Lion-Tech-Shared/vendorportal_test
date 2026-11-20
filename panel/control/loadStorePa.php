<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();
/* redirect if accessing through address */
if(!isset($_GET['flag'])){
	header("Location:../home.php");
}
 
$conn = new getConnection();
$db = $conn->PDO();

$paId=$_GET['paId'];
$myVendor=$_GET['myVendor'];

$strhdo='S';
/***STORE*******************STORE**********************STORE*******************/
$qry="		SELECT DISTINCT c.cctloc,d.strnam FROM tbl_lcsucn a 		        ";
$qry.="		LEFT JOIN tbl_cssmst b ON a.lcnvdr=b.ccmnum  					 	";
$qry.="		LEFT JOIN tbl_cssmct c ON b.ccmnum=c.cctcss  					 	";
$qry.="		LEFT JOIN tbl_store_master d on d.strnum=c.cctloc		  			"; 
		
$qry.="		WHERE c.cctloc IN( 													";
$qry.="																			";
/**********Inserted from qry above*******************************/
$qry.="  	  SELECT strnum FROM tbl_store_master ";
$qry.="		  WHERE ";
$qry.=" 		strhdo=? AND strsbu IN('HG','DS') AND strnam NOT LIKE('%INACTIVE%')  ";
$qry.="    		AND strnum NOT IN(SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid=?) ";
/****************************************************************/
$qry.="																			 ";
$qry.="		)				   													 ";
$qry.="		AND a.lvendr=?  	   					 							 ";
//$qry.="     AND date_format(curdate(),'%y%m%d') BETWEEN c.cctlsd AND c.cctled							 	 ";


// $qry="  SELECT strnum,strnam FROM tbl_store_master ";
// $qry.=" WHERE ";
// $qry.=" 	strhdo=? AND strsbu IN('HG','DS') AND strnam NOT LIKE('%INACTIVE%')";
// $qry.="     AND strnum NOT IN(SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid=?)";
// $qry.=""					 ;

$prepare=$db->prepare($qry);
$prepare->execute(array('S',$paId,$myVendor));	 
$fetch=$prepare->fetchAll();

echo json_encode($fetch);

?>