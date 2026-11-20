<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['gid']));
$fetch=$prepare->fetchAll();
$canReceive=$fetch[0]['canReceive'];  
$vendor_num=$_GET['vendor'];
/****************************for not vendor***********************************/
if($_SESSION['gid']!=2){
	if($canReceive==1){
		$qry= ' SELECT distinct a.strnum as STORE,a.STRNAM as STORE_NAME ';
		$qry.=' FROM tbl_store_master ';
		$qry.=' a left join tbl_inventory b on a.strnum=b.store ';
		$qry.=" WHERE store IN(SELECT lcstor FROM tbl_lcseclocpa where lcuseid='".$_SESSION['uid']."')";
		$qry.=" OR store IN(SELECT strwhs  FROM tbl_store_master where strnum IN(";
		$qry.="            SELECT lcstor FROM tbl_lcseclocpa where lcuseid='".$_SESSION['uid']."' )";
		$qry.="			   )";
	}else{
		$vendor_num=$_GET['vendor'];
		if($vendor_num==''){
			$qry= ' SELECT distinct a.store as STORE,b.STRNAM as STORE_NAME ';
			$qry.=' FROM tbl_inventory a ';
			$qry.=' LEFT JOIN tbl_store_master b on b.strnum=a.store ';
			$qry.=" LEFT JOIN (SELECT DISTINCT lcuseid,lcstor FROM tbl_lcsecloc WHERE lcuseid='".$_SESSION['uid']."' ) c on c.lcstor=a.store ";
			$qry.=" WHERE c.lcstor IN(SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid='".$_SESSION['uid']."' ) ";		
			//$qry.=" WHERE store=(SELECT store FROM tbl_user_info where user_id='".$_SESSION['uid']."')";
			$_SESSION['inv_store_only']=1;
		}else{
		//$qry='SELECT distinct d.lvendr as vendor_num,c.ccmnam as vendor_name FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum';
			/*$qry='  SELECT DISTINCT D.STORE AS STORE,E.STRNAM AS STORE_NAME FROM TBL_LCSUCN A ';
			$qry.=' LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM ';
			$qry.=' LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS ';
			$qry.=' LEFT JOIN TBL_INVENTORY D ON C.CCTLOC=D.STORE ';
			$qry.=' LEFT JOIN TBL_STORE_MASTER E ON E.STRNUM=D.STORE ';
			$qry.=' WHERE A.LVENDR='.$vendor_num.' AND D.STORE is not null';*/
			$qry= ' SELECT distinct a.strnum as STORE,a.STRNAM as STORE_NAME ';
			$qry.=' FROM tbl_store_master ';
			$qry.=' a left join tbl_inventory b on a.strnum=b.store ';
			$qry.=" WHERE store IN(SELECT store FROM tbl_inventory where vendor=".$vendor_num.")";
		}
		
	}
	$prepare=$db->prepare($qry);
	$prepare->execute();
	$fetch=$prepare->fetchAll();
		
	//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr
}else{
	$vendor_num=$_SESSION['uid'];
	//$qry='SELECT distinct d.lvendr as vendor_num,c.ccmnam as vendor_name FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum';
	/*$qry='  SELECT DISTINCT D.STORE AS STORE,E.STRNAM AS STORE_NAME FROM TBL_LCSUCN A ';
	$qry.=' LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM ';
	$qry.=' LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS ';
	$qry.=' LEFT JOIN TBL_INVENTORY D ON C.CCTLOC=D.STORE ';
	$qry.=' LEFT JOIN TBL_STORE_MASTER E ON E.STRNUM=D.STORE ';
	$qry.=' WHERE A.LVENDR='.$vendor_num.' AND D.STORE is not null';
	*/
	$qry="  SELECT distinct a.store as STORE,b.strnam as STORE_NAME FROM tbl_inventory a  ";
	$qry.=" LEFT JOIN tbl_store_master b ON a.store=b.strnum ";	
	$qry.=" WHERE a.vendor=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($vendor_num));
	$fetch=$prepare->fetchAll();
}
echo json_encode($fetch);
?>
