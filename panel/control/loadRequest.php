<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();
 
$vendor_num=$_SESSION['uid'];
$id=$_GET["id"];
$privDtl = explode("-",$_GET['privDtl']);
//echo $privDtl[1];
//exit;
//$qry='SELECT distinct d.lvendr as vendor_num,c.ccmnam as vendor_name FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum';
$gid=$_SESSION['gid'];
if($id=='read-all'){ 
	//if($_GET['isCoordinator'] == 1){
	if($privDtl[3] == 1){
		//$qry="  SELECT b.id FROM tbl_ccnMain a ";
		$qry=" SELECT c.*,h.ee_name,h.store,d.is_approved,@group_id:=$gid as group_id,f.descr,coalesce(e.cost,0) as cost,g.type ";
		$qry.=" FROM tbl_ccnMain a ";
		$qry.=" LEFT JOIN  tbl_ccncoordDept b on a.isdept=b.isdept and a.iclas=b.iclas     ";
		$qry.=" LEFT JOIN  tbl_requestadj c on b.id=c.id AND c.vendor=a.vendor  ";
		$qry.=" LEFT JOIN  tbl_adjustment d on c.id=d.id						";
		$qry.=" LEFT JOIN tbl_invmst f ON f.sku=c.sku "; 
		$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=c.sku "; 
		$qry.=" LEFT JOIN tbl_user_info h ON c.userid=h.user_id ";
		$qry.=" LEFT JOIN tbl_user_account i ON i.user_id=h.user_id ";
		//$qry.=" LEFT JOIN tbl_adjustment j ON a.id=j.id "; 
		$qry.=" LEFT JOIN tbl_type_adj g ON c.adjType=g.id "; 	


		$qry.=" WHERE a.user_id='".$_SESSION['uid']."' and (b.isCoorVwd=0  OR b.isCoorVwd=1) AND d.is_approved=1		    "; 		

		/*$qry="SELECT a.*,b.ee_name,b.store,c.is_approved,@group_id:=$gid as group_id,f.descr,coalesce(e.cost,0) as cost,g.type ";
		$qry.=" FROM  tbl_requestadj a ";
		$qry.=" LEFT JOIN tbl_invmst f ON f.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_user_info b ON a.userid=b.user_id ";
		$qry.=" LEFT JOIN tbl_user_account d ON d.user_id=b.user_id ";
		$qry.=" LEFT JOIN tbl_adjustment c ON a.id=c.id "; 
		$qry.=" LEFT JOIN tbl_type_adj g ON a.adjType=g.id "; 		
		$qry.=" WHERE a.userid='".$vendor_num."' ";
		$qry.=' ';		*/
		$qry.='  ORDER BY c.requestDate ASC ';


		$qry=" SELECT a.*,b.ee_name,b.store,c.is_approved,@group_id:=$gid as group_id,f.descr,coalesce(e.cost,0) as cost,g.type ";
		$qry.= ",j.*,a.id as primId";
		$qry.=" FROM  tbl_requestadj a ";
		$qry.=" LEFT JOIN tbl_invmst f ON f.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_user_info b ON a.userid=b.user_id ";
		$qry.=" LEFT JOIN tbl_user_account d ON d.user_id=b.user_id ";
		$qry.=" LEFT JOIN tbl_adjustment c ON a.id=c.id "; 
		$qry.=" LEFT JOIN tbl_type_adj g ON a.adjType=g.id "; 		
		$qry.=" LEFT JOIN (SELECT DISTINCT lcstor FROM tbl_lcseclocpa) h ON h.lcstor=a.store "; 		
		$qry.=" LEFT JOIN tbl_lcsecloc i ON i.lcstor=h.lcstor "; 		
		$qry.=" LEFT JOIN tbl_notify j ON j.id=a.id "; 
		$qry.=" WHERE i.lcuseid='".$vendor_num."' ";
		$qry.=' ';
		//$qry.='SELECT DISTINCT c.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM tbl_lcsucn A LEFT JOIN tbl_cssmst B ON a.lcnvdr=b.ccmnum LEFT JOIN tbl_cssmct C ON b.ccmnum=c.cctcss LEFT JOIN tbl_store_master D ON c.cctloc=D.STrnum WHERE a.LVENDR='.$vendor_num.' ';
		$qry.='  ORDER BY a.requestDate ASC ';


	}
	else if($gid==2){
		$qry=" SELECT a.*,b.ee_name,b.store,c.is_approved, @group_id:=$gid as group_id,f.descr,coalesce(e.cost,0) as cost,g.type ";
		$qry.=" FROM  tbl_requestadj a ";
		$qry.=" LEFT JOIN tbl_invmst f ON f.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_user_info b ON a.userid=b.user_id ";
		$qry.=" LEFT JOIN tbl_user_account d ON d.user_id=b.user_id ";
		$qry.=" LEFT JOIN tbl_adjustment c ON a.id=c.id "; 
		$qry.=" LEFT JOIN tbl_type_adj g ON a.adjType=g.id "; 
		$qry.=" WHERE b.store IN(";
		$qry.='SELECT DISTINCT c.cctloc AS STORE FROM tbl_lcsucn a LEFT JOIN tbl_cssmst b ON a.lcnvdr=b.ccmnum LEFT JOIN tbl_cssmct c ON b.ccmnum=c.cctcss LEFT JOIN tbl_store_master d ON c.cctloc=d.strnum WHERE a.lvendr='.$vendor_num.' ';
		//$qry.='SELECT DISTINCT c.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM tbl_lcsucn A LEFT JOIN tbl_cssmst B ON a.lcnvdr=b.ccmnum LEFT JOIN tbl_cssmct C ON b.ccmnum=c.cctcss LEFT JOIN tbl_store_master D ON c.cctloc=D.STrnum WHERE a.LVENDR='.$vendor_num.' ';
		$qry.=') AND a.vendor='.$vendor_num.' ORDER BY c.is_approved ASC ';
	}else if($privDtl[1] == 1){  /*Merch*/
		$qry=" SELECT a.*,b.ee_name,b.store,c.is_approved,@group_id:=$gid as group_id,f.descr,coalesce(e.cost,0) as cost,g.type ";
		$qry.= ",j.*";
		$qry.=" FROM  tbl_requestadj a ";
		$qry.=" LEFT JOIN tbl_invmst f ON f.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_user_info b ON a.userid=b.user_id ";
		$qry.=" LEFT JOIN tbl_user_account d ON d.user_id=b.user_id ";
		$qry.=" LEFT JOIN tbl_adjustment c ON a.id=c.id "; 
		$qry.=" LEFT JOIN tbl_type_adj g ON a.adjType=g.id "; 		
		$qry.=" LEFT JOIN (SELECT DISTINCT lcstor FROM tbl_lcseclocpa) h ON h.lcstor=a.store "; 		
		$qry.=" LEFT JOIN tbl_lcsecloc i ON i.lcstor=h.lcstor "; 		
		$qry.=" LEFT JOIN tbl_notify j ON j.id=a.id "; 
		$qry.=" WHERE i.lcuseid='".$vendor_num."' ";
		$qry.=' ';
		//$qry.='SELECT DISTINCT c.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM tbl_lcsucn A LEFT JOIN tbl_cssmst B ON a.lcnvdr=b.ccmnum LEFT JOIN tbl_cssmct C ON b.ccmnum=c.cctcss LEFT JOIN tbl_store_master D ON c.cctloc=D.STrnum WHERE a.LVENDR='.$vendor_num.' ';
		$qry.='  ORDER BY a.requestDate ASC ';
	}
	else{
		$qry="SELECT a.*,b.ee_name,b.store,c.is_approved,@group_id:=$gid as group_id,f.descr,coalesce(e.cost,0) as cost,g.type ";
		$qry.=" FROM  tbl_requestadj a ";
		$qry.=" LEFT JOIN tbl_invmst f ON f.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=a.sku "; 
		$qry.=" LEFT JOIN tbl_user_info b ON a.userid=b.user_id ";
		$qry.=" LEFT JOIN tbl_user_account d ON d.user_id=b.user_id ";
		$qry.=" LEFT JOIN tbl_adjustment c ON a.id=c.id "; 
		$qry.=" LEFT JOIN tbl_type_adj g ON a.adjType=g.id "; 		
		$qry.=" WHERE a.userid='".$vendor_num."' ";
		$qry.=' ';
		//$qry.='SELECT DISTINCT c.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM tbl_lcsucn A LEFT JOIN tbl_cssmst B ON a.lcnvdr=b.ccmnum LEFT JOIN tbl_cssmct C ON b.ccmnum=c.cctcss LEFT JOIN tbl_store_master D ON c.cctloc=D.STrnum WHERE a.LVENDR='.$vendor_num.' ';
		$qry.='  ORDER BY a.requestDate ASC ';
	}
}else{
	if($gid==2){
			$qry="SELECT a.*,b.ee_name,b.store,c.is_approved,@group_id:=$gid as group_id ,f.descr,coalesce(e.cost,0) as cost,g.type  ";
			$qry.=" FROM  tbl_requestadj a ";
			$qry.=" LEFT JOIN tbl_invmst f ON f.sku=a.sku "; 
			$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=a.sku "; 
			$qry.=" LEFT JOIN tbl_user_info b ON a.userid=b.user_id ";
			$qry.=" LEFT JOIN tbl_adjustment c ON a.id=c.id "; 
			$qry.=" LEFT JOIN tbl_type_adj g ON a.adjType=g.id "; 
			$qry.=" WHERE b.store IN(";
			$qry.='SELECT DISTINCT c.cctloc AS STORE FROM tbl_lcsucn a LEFT JOIN tbl_cssmst b ON a.lcnvdr=b.ccmnum LEFT JOIN tbl_cssmct c ON b.ccmnum=c.cctcss LEFT JOIN tbl_store_master d ON c.cctloc=d.strnum WHERE a.lvendr='.$vendor_num.' ';
			//$qry.='SELECT DISTINCT c.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM tbl_lcsucn A LEFT JOIN tbl_cssmst B ON a.lcnvdr=b.ccmnum LEFT JOIN tbl_cssmct C ON b.ccmnum=c.cctcss LEFT JOIN tbl_store_master D ON c.cctloc=D.STrnum WHERE a.LVENDR='.$vendor_num.' ';
			$qry.=") AND a.id='".$id."' AND a.vendor=".$vendor_num." ORDER BY a.requestDate ";
	}else{
			$qry="SELECT a.*,b.ee_name,b.store,c.is_approved,@group_id:=$gid as group_id,f.descr,coalesce(e.cost,0) as cost,g.type ";
		    $qry.=" FROM  tbl_requestadj a ";
		    $qry.=" LEFT JOIN tbl_invmst f ON f.sku=a.sku "; 
			$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=a.sku "; 
			$qry.=" LEFT JOIN tbl_user_info b ON a.userid=b.user_id ";
			$qry.=" LEFT JOIN tbl_user_account d ON d.user_id=b.user_id ";
			$qry.=" LEFT JOIN tbl_adjustment c ON a.id=c.id "; 
			$qry.=" LEFT JOIN tbl_type_adj g ON a.adjType=g.id "; 
			$qry.=" WHERE a.id='".$id."'";
			$qry.=' ';
		//$qry.='SELECT DISTINCT c.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM tbl_lcsucn A LEFT JOIN tbl_cssmst B ON a.lcnvdr=b.ccmnum LEFT JOIN tbl_cssmct C ON b.ccmnum=c.cctcss LEFT JOIN tbl_store_master D ON c.cctloc=D.STrnum WHERE a.LVENDR='.$vendor_num.' ';
			$qry.='   ORDER BY a.requestDate ASC ';
	}
}

$prepare=$db->prepare($qry);
$prepare->execute();
$fetch=$prepare->fetchAll();

 
echo json_encode($fetch);
 
?>