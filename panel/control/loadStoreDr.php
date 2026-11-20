<?php
require "../../class-main/connection.class.php";  
session_start();

$conn = new getConnection(); 
$db = $conn->PDO(); 
if($_SESSION['gid']==2)
	$vendor_num=$_SESSION['uid'];
else
	$vendor_num=$_GET['vendor'];	

/*Either store or warehouse only*/
if($_SESSION['delType']=='store'){
	//$qry='SELECT distinct d.lvendr as vendor_num,c.ccmnam as vendor_name FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum';
	$qry='  SELECT DISTINCT c.cctloc as STORE,d.strnam as STORE_NAME FROM tbl_lcsucn a ';
	$qry.=' LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum ';
	$qry.=' LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss ';
	$qry.=' LEFT JOIN tbl_store_master d on c.cctloc=d.strnum ';
	$qry.=' WHERE a.lvendr='.$vendor_num.' ';
	$qry.=" AND d.strnam NOT LIKE('%INACTIVE%') ";//AND curdate() BETWEEN date(c.cctlsd) AND date(c.cctled) ";
	$prepare=$db->prepare($qry);
	$prepare->execute();
	$fetch=$prepare->fetchAll();
}else{
	/*$qry="  SELECT strnum  as STORE,strnam  as STORE_NAME FROM tbl_store_master WHERE strnum IN(";
	$qry.="   SELECT strwhs FROM tbl_store_master WHERE strnum IN( ";
	$qry.='    SELECT DISTINCT c.cctloc as STORE FROM tbl_lcsucn a ';
	$qry.='    LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum ';
	$qry.='    LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss ';
	$qry.='    LEFT JOIN tbl_store_master d on c.cctloc=d.strnum WHERE a.lvendr='.$vendor_num.' ';
	//$qry.="		AND curdate() BETWEEN date(c.cctlsd) AND date(c.cctled)									";
	$qry.="   ) ";	
	$qry.=" )  AND strhdo='W' AND strsbu IN('DS','HG') AND strnam NOT LIKE('%INACTIVE%') ";	*/

	$qry="  SELECT DISTINCT d.strwhs AS STORE,e.strnam AS STORE_NAME ";
	$qry.=" FROM tbl_lcsucn a ";
	$qry.=" LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum ";
	$qry.=" LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss ";
	$qry.=" LEFT JOIN tbl_store_master d on c.cctloc=d.strnum";
	$qry.=" LEFT JOIN tbl_store_master e on d.strwhs=e.strnum";
	$qry.=" WHERE a.lvendr=740003  and e.strhdo='W' and e.strsbu IN('DS','HG') AND e.strnam NOT LIKE('%INACTIVE%')";
	$prepare=$db->prepare($qry);
	$prepare->execute();
	$fetch=$prepare->fetchAll();
}
echo json_encode($fetch);
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
