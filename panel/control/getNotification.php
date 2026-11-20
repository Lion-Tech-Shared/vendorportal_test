<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

if(!isset($_SESSION["uid"]) ) {
      exit;
} 
//if($_SESSION['gid'] != 3){
if(!isset($_GET['canApprove'])){
	header("Location:../home.php");
}
$canApprove=$_GET['canApprove'];
$op=" = ";
if($canApprove==1){
	$op=" <> ";
}

/*---------------------- Get row/data if can Receive------------------- */
$qryCanRec=" SELECT canApprove,canReceive FROM tbl_privileges WHERE gid=? ";
$prepare=$db->prepare($qryCanRec);
$prepare->execute(array($_SESSION['gid']));
$fetch=$prepare->fetchAll();
$canReceive=$fetch[0][1];
/*---------------------------------------------------------------------- */

$rows=0;


if($_SESSION['gid']==2){
	$qry=" SELECT count(*) FROM tbl_deliverytype a  ";
	$qry.="  LEFT JOIN tbl_dr_hdr b  ";
	$qry.="     ON a.dr_number=b.dr_number  ";
	$qry.=" WHERE b.vendor_code=? ";
	//$qry.="  IN(SELECT LCSTOR FROM tbl_lcsecloc WHERE lcuseid=?) ";
	//$qry.=" IN(";
	//$qry.="SELECT DISTINCT C.cctloc AS STORE FROM TBL_LCSUCN A ";
	//$qry.="    LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM ";
	//$qry.="    LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS ";
	//$qry.="    LEFT JOIN TBL_STORE_MASTER D ON c.cctloc=D.STrnum WHERE A.LVENDR=? ";
	//s$qry.="	)";
	//$qry.=" AND a.isMerchApproved<>? AND a.isViewedV=? AND b.status=?";
	$qry.=" AND a.isMerchApproved<>? AND b.status=? AND a.isViewedV=?";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['uid'],0,1,7));
	//$prepare->execute(array($_SESSION['uid'],0,7,1));
	$fetch=$prepare->fetchAll();
	$arr=array();	
	$rows=$fetch[0][0];
}else{
	if($canReceive==0 && $canApprove==0)
		exit;
	/*---------FOR STORE/WHSE------------------------- */
	if($canReceive==1){
		$count=0;
		$isWhse=isset($_SESSION['isWhse'])?'1':'0';		
		//if($isWhse==1){
		/*****************Backup code*********************************/
		/*$qry=" SELECT COUNT(a.dr_number) FROM tbl_dr_hdr a ";
		$qry.=" LEFT JOIN tbl_deliverytype b on a.dr_number=b.dr_number ";
		$qry.=" WHERE a.loc_code IN(";
	    $qry.="       SELECT strnum from tbl_store_master ";
	    $qry.="       WHERE strwhs IN(SELECT LCSTOR FROM tbl_lcseclocpa WHERE lcuseid=?) ";
	    $qry.="             OR strwhs IN(SELECT STRWHS FROM tbl_store_master WHERE strnum IN(SELECT LCSTOR FROM tbl_lcseclocpa WHERE lcuseid=?)) ";
	    $qry.="       AND STRHDO=?)";
		$qry.=" AND b.isWhse=? ";							
		$qry.=" AND a.status = 2 ";*/		

		$qry="  SELECT COUNT(a.dr_number) FROM tbl_dr_hdr a ";
		$qry.=" LEFT JOIN tbl_deliverytype b on a.dr_number=b.dr_number ";
		//$qry.=" LEFT JOIN tbl_pa_vendor c on c.lvendr=a.vendor_code "
		$qry.=" WHERE a.loc_code IN(";
	    $qry.="       SELECT strwhs from tbl_store_master ";
	    $qry.="       WHERE strnum IN(SELECT LCSTOR FROM tbl_lcseclocpa WHERE lcuseid=?) ";
	    $qry.="             															 ";
	    $qry.="       AND STRHDO=?)";
		$qry.=" AND b.isWhse=? ";		
		$qry.="	AND a.status=?";
		$qry.=" AND a.vendor_code IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?)";
		$prepare=$db->prepare($qry);

		$prepare->execute(array($_SESSION['uid'],'S',1,2,$_SESSION['uid']));	
		$fetch=$prepare->fetchAll();
		$count+=$fetch[0][0];
		//}else{
		  //$qry=" SELECT COUNT(dr_number) FROM tbl_dr_hdr  WHERE loc_code IN(SELECT LCSTOR FROM tbl_lcsecloc WHERE lcuseid=?) ";			 
		$qry=" SELECT COUNT(dr_number) FROM tbl_dr_hdr  WHERE loc_code IN(SELECT LCSTOR FROM tbl_lcseclocpa WHERE lcuseid=?)  ";			 
		$qry.=" AND status = 2 " ;		 
		$qry.=" AND vendor_code IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?)";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($_SESSION['uid'],$_SESSION['uid']));	
		 
		//}						
		$fetch2=$prepare->fetchAll();	
		$count+=$fetch2[0][0];
		$rows=$count;
	}
	/*---------FOR MERCH------------------------- */		
	else{
		$qry=" SELECT count(*) FROM tbl_deliverytype a  ";
		$qry.="  LEFT JOIN tbl_dr_hdr b  ";
		$qry.="     ON a.dr_number=b.dr_number  ";
		$qry.=" WHERE b.loc_code ";
		$qry.="  IN(SELECT LCSTOR FROM tbl_lcsecloc WHERE lcuseid=?) ";	
		$qry.=" AND a.isMerchApproved=? AND b.status=?";
		//$qry.=" AND a.isMerchApproved=? AND a.isViewedV=? AND b.status=?";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($_SESSION['uid'],0,1));
		$fetch=$prepare->fetchAll();
		$arr=array();	
		$rows=$fetch[0][0];
	}
}	
 
echo $rows;

?>
