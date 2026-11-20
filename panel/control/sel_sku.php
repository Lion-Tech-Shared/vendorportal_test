<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

$sku=$_POST['sku'];
$qty=$_POST['qty'];
$type=$_POST['type'];
$reason=$_POST['reason'];
$userid=$_SESSION['uid'];
$type_op=$_POST['typeop'];
$vcode=$_POST['vcode'];
$onhand=$_POST['on_hand'];

/*********************Select Store Of User************************/
$qry="SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid='".$userid."'";
$prepare=$db->prepare($qry);
$prepare->execute();
$fetch=$prepare->fetchAll();
$store=$fetch[0]["lcstor"];
$storeOrWhse=$_POST['store'];

/*********************Select Sku From master**********************/
$qry='SELECT COUNT(*) FROM tbl_invmst WHERE sku='.$sku;
$prepare=$db->prepare($qry);
$prepare->execute();
$fetch=$prepare->fetchAll();

/*********************Select Type of Adjustment*******************/
$qry='SELECT * FROM tbl_type_adj WHERE type=?';
$prepare=$db->prepare($qry);
$prepare->execute(array($type));
$getOp=$prepare->fetchAll();
if(count($getOp) < 1){
	echo -2;
	exit;
}
$type=$getOp[0]['id'];
$myD=date('Y-m-d');

/****for reference number creation********************************/
$qry="SELECT * FROM  tbl_requestadj WHERE vendor=? AND adjType=?";
$prepare=$db->prepare($qry);
$prepare->execute(array($vcode,$type));
$myFetch=$prepare->fetchAll();
//echo date("Y-m-d",strtotime($myFetch[0]['requestDate']));
//exit;
if(count($myFetch) > 0){
	if(date("Y-m-d",strtotime($myFetch[0]['requestDate']))==$myD){
		$ref_num=$myFetch[0]['ref_num'];
	}else{
		$ref_num=$type.$myD.$vcode;
	}
}else{
	$ref_num=$type.$myD.$vcode;
}

//for pending request -----------------------------------------------{
$qry='SELECT a.id,sum(a.qty) as total FROM tbl_requestadj a  ';		
$qry.=" left join tbl_adjustment b on a.id=b.id";
$qry.='   WHERE a.vendor=? AND a.sku=? AND  a.store=? AND b.is_approved=0';
$qry.="      GROUP BY a.id ";
$prepare=$db->prepare($qry);
$prepare->execute(array($vcode,$sku,$storeOrWhse));
$pendingAdj=$prepare->fetchAll();	
$pendingQty=0;
if(count($pendingAdj)>0)
	$pendingQty=$pendingAdj[0]["total"];
//------------------------------------------------------------------}


//for qty on hand---------------------------------------------------{ COMMENTED BECAUSE ON HAND PASS
/*$qry='SELECT * FROM tbl_inventory ';		
$qry.=' WHERE vendor=? AND sku=? AND  store=?';
$qry.="   ";
//$qry.=" )";	
$prepare=$db->prepare($qry);
$prepare->execute(array($vcode,$sku,$store));
$fetchInv=$prepare->fetchAll();
if(count($fetchInv)>0)
	$onhand=$fetchInv[0]["on_hand"];
*///------------------------------------------------------------------}


//echo "onhand:".$onhand."-pendg:".$pendingQty."-qty:".$qty."-type:".$type;
//exit;
//set array of adjustment types
$arr_type=array(1,2,3,5,6,8);
$arr_type2=array(4,7);

//check if sku exist with vendor--------------------------------------{
/*$qry='SELECT a.descr,b.cost FROM tbl_invmst a';
$qry.=' left join tbl_sku_cost b on a.sku=b.sku ';
$qry.='     WHERE a.dept=990 AND a.sku=? AND a.sub_dept=(SELECT LCNVDR FROM tbl_lcsucn WHERE lvendr=?)';
$qry.="          AND a.class>0 AND a.sub_class>0 ";
//$qry.=" )";	
$prepare=$db->prepare($qry);
$prepare->execute(array($sku,$vcode));
$skuVendor=$prepare->fetchAll();	*/
//----------------------------------------------------------------------}

$skuVendor=0;
/***********Check if sku is under contract******************************/
$qry="  SELECT distinct d.cdtval,d.cdttyp ";
$qry.=" FROM tbl_lcsucn a ";
$qry.=" LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum ";
$qry.=" LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss ";
$qry.=" LEFT JOIN tbl_cssdtl d on c.cctnum=d.cdtcct ";
$qry.=" WHERE a.lvendr=?  AND c.cctloc=? ";
$qry.=" AND date_format(curdate(),'%Y%m%d')  BETWEEN date(c.cctlsd) AND date(c.cctled) "; 
//$qry.=" and curdate() BETWEEN c.cctlsd AND c.cctled";
$prepare=$db->prepare($qry);
$prepare->execute(array($vcode,$store));
$row=$prepare->fetchAll();	 	for($i=0;$i<count($row);$i++){
	if($row[$i]["cdttyp"]==4){
		$dept=substr($row[$i]["cdtval"],0,3); 
		$sub_dept=substr($row[$i]["cdtval"],3,3); 
		$class=substr($row[$i]["cdtval"],6,3); 
		$prepare2=$db->prepare("SELECT * FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND class=".$class." AND sku=".$sku);					
	}
	else if($row[$i]["cdttyp"]==5){
		$dept=substr($row[$i]["cdtval"],0,3); 
		$sub_dept=substr($row[$i]["cdtval"],3,3); 	
		$prepare2=$db->prepare("SELECT sku,descr FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND sku=".$sku);	
		//echo $sub_dept;
		//exit;			
	}
	else if($row[$i]["cdttyp"]==3){
		$dept=substr($row[$i]["cdtval"],0,3); 
		$sub_dept=substr($row[$i]["cdtval"],3,3); 
		$class=substr($row[$i]["cdtval"],6,3); 
		$sub_class=substr($row[$i]["cdtval"],10,3); 
		$prepare2=$db->prepare("SELECT * FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND class=".$class." AND sub_class=".$sub_class." AND sku=".$sku);			
	}
	else if($row[$i]["cdttyp"]==1){
		$sku=$row[$i]["cdtval"];
		$prepare2=$db->prepare('SELECT * FROM tbl_invmst where sku='.$sku);				
	}
	$prepare2->execute();	
	$row2=$prepare2->fetchAll();
 	if(count($row2) > 0){
 		$skuVendor=1;
 		break;
 	}

}
/**************************************************************************/
for($i=0;$i<count($row);$i++){
	if($row[$i]["cdttyp"]==4){
		$dept=substr($row[$i]["cdtval"],0,3); 
		$sub_dept=substr($row[$i]["cdtval"],3,3); 
		$class=substr($row[$i]["cdtval"],6,3); 
		$prepare2=$db->prepare("SELECT * FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND class=".$class." AND sku=".$sku);					
	}
	else if($row[$i]["cdttyp"]==5){
		$dept=substr($row[$i]["cdtval"],0,3); 
		$sub_dept=substr($row[$i]["cdtval"],3,3); 	
		$prepare2=$db->prepare("SELECT sku,descr FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND sku=".$sku);	
		//echo $sub_dept;
		//exit;			
	}
	else if($row[$i]["cdttyp"]==3){
		$dept=substr($row[$i]["cdtval"],0,3); 
		$sub_dept=substr($row[$i]["cdtval"],3,3); 
		$class=substr($row[$i]["cdtval"],6,3); 
		$sub_class=substr($row[$i]["cdtval"],10,3); 
		$prepare2=$db->prepare("SELECT * FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND class=".$class." AND sub_class=".$sub_class." AND sku=".$sku);			
	}
	else if($row[$i]["cdttyp"]==1){
		$sku=$row[$i]["cdtval"];
		$prepare2=$db->prepare('SELECT * FROM tbl_invmst where sku='.$sku);				
	}
	$prepare2->execute();	
	$row2=$prepare2->fetchAll();
 	if(count($row2) > 0){
 		$skuVendor=1;
 		break;
 	}

}

//echo $dept."-".$sub_dept."-".$class."-".$sku;
//exit;
/****Sku not found in Vendodr****/ 
if($skuVendor < 1){
	echo -1;
}
else if($onhand < $qty && in_array($type, $arr_type) ){
	echo -3;
}else if( ($onhand-$pendingQty) < $qty && in_array($type, $arr_type)){
	echo -4;
}
//else if(count($fetchInv) < 1){
  //echo -5;
//}
else if($fetch[0][0]>0 && $skuVendor > 0){
	$id=date('Ymdhis').$storeOrWhse;
	//insert to request adjustment
	$qry="INSERT INTO tbl_requestadj(id,userid,sku,qty,adjType,reason,oper,store,vendor,ref_num) VALUES(?,?,?,?,?,?,?,?,?,?)";
	$prepare=$db->prepare($qry);
	$flag=$prepare->execute(array($id,$userid,$sku,$qty,$type,$reason,$type_op,$storeOrWhse,$vcode,$ref_num));
	//insert to tbl adjustment
	//$qry=" INSERT INTO tbl_adjustment (id,userid,viewDate,is_approved,remarks) ";
	//		$qry.=" VALUES ('".$myid."','".$vendor_num."',NOW(),0,' ') ";
	if($flag){
		$qry=" INSERT INTO tbl_adjustment(id,userid,viewDate,is_approved) ";
		$qry.=" VALUES(?,?,NOW(),0)";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($id,$vcode));
		echo 1;
	}else{
		echo 0;
	}
	//$prepare->execute(array($id,$vcode,0,''));
	//$fetch=$prepare->fetchAll();
	
}//else if(isset($_SESSION['sku_found'])!=$sku){
	//echo -1;
//}
else{
	echo 0;
}

unset($_SESSION['sku_found']);
unset($_SESSION['vendor']);
unset($_SESSION['on_hand']);
?>