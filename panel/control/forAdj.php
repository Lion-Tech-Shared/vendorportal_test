<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();
$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

$myClass = new myClass();

$vendor = $_GET['vendor'];
$store = $_GET['store'];
$userid = $_SESSION['uid'];

/**************Get Heirarchy***************************/
$qry="  SELECT distinct d.cdtval,d.cdttyp ";
$qry.=" FROM tbl_lcsucn a ";
$qry.=" LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum ";
$qry.=" LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss ";
$qry.=" LEFT JOIN tbl_cssdtl d on c.cctnum=d.cdtcct ";
$qry.=" WHERE a.lvendr=?  AND (c.cctloc = ?  OR c.cctloc = (SELECT strnum FROM tbl_store_master WHERE strwhs = ? ) )";
//$qry.=" AND date_format(curdate(),'%y%m%d')  BETWEEN date(c.cctlsd) AND date(c.cctled) "; 
/**************Get Heirarchy***************************/

$prepare=$db->prepare($qry);
$prepare->execute(array($vendor,$store,$store));
$row=$prepare->fetchAll();	 

$myReturnArr=array(); 
//echo count($row);
//exit;
$myArr=$_GET['myArr'];
$heirarchy=$_GET['heirarchy'];
/*echo json_encode($_GET['myArr']);
//exit;
/*$myArr3=explode(",", $myArr);
$myarr2=array("asdf","asfd2");
for($i=0;$i<count($myArr);$i++){

	 echo  $myArr;
	 exit;
}*/
/*$myArr2=json_encode($_GET['myArr']);
$myArr3=json_decode($myArr2,true);
 foreach($myArr3 as $key => $value){
 	echo $value[4];
 	exit;
 }*/
//$myDecArr=json_decode($myArr2);
foreach ($myArr as $key => $value) {
	// echo $myClass->getCurDateTime($db);
	 //exit;
	 $mySku=$value[0];
	 $myDesc=$value[1];
	 $mySkuQty=$value[2];
	 $myAdjType=$value[3];
	 $myReason=$value[4];
     // echo $mySku."-".$myDesc."-".$mySkuQty."-".$myAdjType."-".$myReason;
     // exit;
	//$myClass->isSkuInVendor();
	 /***validate sku if digit***/
	 $valArr['desc']=str_replace("\"","",$myDesc);
	 $valArr['myAdjType']=$myAdjType;
 	 $valArr['myReason']=$myReason;
	 /*****Check Operation**********/
     $ope=$myClass->getOperation($db,$myAdjType);
     $valArr['sku']=$mySku;
     $valArr['mySkuQty']=$mySkuQty;
     $valArr['myAdjDesc']=$myClass->getAdjDesc();
     if($ope==-1){
     	
     	$valArr['msg']='Adjustment type Invalid.  Not Submitted!';
     	array_push($myReturnArr,$valArr);
     	continue;   
     }

	 if(!$myClass->validateInput($mySku)){
	 	//$valArr['sku']=$mySku;
	 	$valArr['msg']='Not digit/Invalid Input. Not Submitted';
	 	array_push($myReturnArr,$valArr);
        continue; 
	 }
	  /***validate qty if digit***/
	 if(!$myClass->validateInput($mySkuQty)){
	 	//$valArr['sku']=$mySku;
	 	$valArr['msg']='Not digit/Invalid Input. Not Submitted';
	 	array_push($myReturnArr,$valArr);
        continue; 
	 }
	  /***Check If Sku in Vendor****/
     if(!$myClass->isSkuInVendor($db,$mySku,$row)){
        //$valArr['sku']=$mySku;
        $valArr['msg']='Not Sku of Vendor. Not Submitted';
        array_push($myReturnArr,$valArr);
        continue;        
     }
     
     /******Get On Hand********/
     $on_hand=$myClass->getOnHand($db,$vendor,$store,$mySku);

     //alert("On Hand:".$on_hand."-qty:".$mySkuQty.";");
     if($on_hand < 1 && $ope == '-'){
     	//$valArr['sku']=$mySku;
     	$valArr['msg']='No Inventory!. Not Submitted';
     	array_push($myReturnArr,$valArr);
     	continue;   
     }
    
	 $pendingQty=$myClass->getPendingAdj($db,$vendor,$mySku,$store);
     if( abs($on_hand-$pendingQty) < $mySkuQty && $ope=='-' ){
     	//$valArr['sku']=$mySku;
     	$valArr['msg']='Greater than on hand , because of pending adjustment. Not Submitted!';
     	array_push($myReturnArr,$valArr);
     	continue; 
     }     
     if($on_hand < $mySkuQty && $ope=='-'){
     	//$valArr['sku']=$mySku;
     	$valArr['msg']='Qty is greater than On Hand. Not Submitted!';
     	array_push($myReturnArr,$valArr);
     	continue;  
     }       
    $myAdjRef=$myClass->createRef($db,$vendor,$myAdjType);
    $id=$myClass->getCurDateTime($db)."".$microtime.mt_rand(1,1000);
    //$id=date('ymd-his',strtotime($myClass->getCurDateTime($db))."".$microtime.mt_rand(1,1000);
     
     if(!$myClass->createAdj($db,$id,$userid,$mySku,$mySkuQty,$myAdjType,$myReason,$ope,$store,$vendor,$myAdjRef)){
     	//$valArr['sku']=$mySku;
     	$valArr['msg']='Error in Inserting Record. Not Inserted. Not Submitted!';
     	array_push($myReturnArr,$valArr);
     	continue; 
     }
     $myClass->insertNoti($db,$id,$vendor,$store);
     /*$myClass->insertToCoor($db,$id,$heirarchy); commented because of new design the tbl_notify*/
    // $valArr['sku']=$mySku;
     $valArr['msg']='Adjustment Request For Approval';
    	 
}
 
echo json_encode($myReturnArr);

/*******END*********END*****END**********END*********END***********/

/*************************my Class********************************/
class myClass{

	private $myAdjDesc="";
    public function __construct(){
    	
    }
	public function createAdj($db,$id,$userid,$mySku,$mySkuQty,$myAdjType,$myReason,$ope,$store,$vendor,$myAdjRef){
		$qry=" INSERT INTO tbl_requestadj(id,userid,sku,qty,adjType,reason,oper,store,vendor,ref_num) VALUES(?,?,?,?,?,?,?,?,?,?) ";
		$prepare=$db->prepare($qry);
		$flag=$prepare->execute(array($id,$userid,$mySku,$mySkuQty,$myAdjType,str_replace("\"","",$myReason),$ope,$store,$vendor,$myAdjRef));
		//insert to tbl adjustment
		//$qry=" INSERT INTO tbl_adjustment (id,userid,viewDate,is_approved,remarks) ";
		//		$qry.=" VALUES ('".$myid."','".$vendor_num."',NOW(),0,' ') ";
		if($flag){
			$qry=" INSERT INTO tbl_adjustment(id,userid,viewDate,is_approved) ";
			$qry.=" VALUES(?,?,NOW(),0)";
			$prepare=$db->prepare($qry);
			$prepare->execute(array($id,$vendor));
			return true;
		}else{
			$qry=" DELETE FROM tbl_requestadj WHERE id=? ";
			$prepare=$db->prepare($qry);
			$prepare->execute(array($id));
			return false;
		}
	}
	public function getCurDateTime($db){
		$qry=" SELECT now() ";
		$prepare=$db->prepare($qry);
		$prepare->execute();
		$fetch=$prepare->fetchAll();

		return date("ymdhis",strtotime($fetch[0][0]));
	}
    public function getPendingAdj($db,$vendor,$sku,$store){
    	//for pending request -----------------------------------------------{
		$qry=' SELECT a.id,sum(a.qty) as total FROM tbl_requestadj a  ';		
		$qry.=" left join tbl_adjustment b on a.id=b.id";
		$qry.='   WHERE a.vendor=? AND a.sku=? AND  a.store=? AND b.is_approved=0';
		$qry.="      GROUP BY a.id ";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($vendor,$sku,$store));
		$pendingAdj=$prepare->fetchAll();	
		$pendingQty=0;
		if(count($pendingAdj)>0)
			$pendingQty=$pendingAdj[0]["total"];

		return $pendingQty;
    }
	public function getOperation($db,$adjType){
     		$qry="  SELECT operation,type  ";
			$qry.=" FROM tbl_type_adj ";			 
			$qry.=" WHERE id=?         ";			

			$prepare=$db->prepare($qry);
			$prepare->execute(array($adjType));
			$row=$prepare->fetchAll();	  
			if(count($row) < 1){
				return -1;
			} 
			$this->myAdjDesc=$row[0][1];
			return $row[0][0]; 
	}
	public function getAdjDesc(){
		return $this->myAdjDesc;
	}
    public function validateInput($val){
    	if (!filter_var($val, FILTER_VALIDATE_INT)) {
 				 return false;
		}
		return true;		
    }
    public function createRef($db,$vendor,$myAdjType){
    	/****for reference number creation********************************/
		$qry="SELECT * FROM  tbl_requestadj WHERE vendor=? AND adjType=?";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($vendor,$myAdjType));
		$myFetch=$prepare->fetchAll();
		//echo date("Y-m-d",strtotime($myFetch[0]['requestDate']));
		$myD=$this->getCurDate($db);
		//exit;
		if(count($myFetch) > 0){
			if(date("Y-m-d",strtotime($myFetch[0]['requestDate']))==$myD){
				$ref_num=$myFetch[0]['ref_num'];
			}else{
				$ref_num=$myAdjType.$myD.$vendor;
			}
		}else{
			$ref_num=$myAdjType.$myD.$vendor;
		}
		return $ref_num;
    }
    public function getOnHand($db,$vendor,$store,$sku){
    	$qry="  SELECT on_hand   ";
		$qry.=" FROM tbl_inventory ";			 
		$qry.=" WHERE vendor=? AND store=? AND sku=? ";			

		$prepare=$db->prepare($qry);
		$prepare->execute(array($vendor,$store,$sku));
		$row=$prepare->fetchAll();	  
		if(count($row)<1){
			return 0;
		}
		return $row[0][0];
    }
    public function getCurDate($db){
    	$qry=" SELECT date_format(curdate(),'%Y-%m-%d') as curdate ";
 
		$prepare=$db->prepare($qry);
		$prepare->execute();
		$fetch=$prepare->fetchAll();		 
		$currentDate=$fetch[0][0]; 
 
		return $currentDate;
    }
	public function isSkuInVendor($db,$sku,$row){
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
		 		return true;
		 		//break;
		 	}

	    }
	    return false;
	}
	public function insertNoti($db,$id,$vendor,$store){
		$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		/**ADDED for notification for vendor*/		 
		$qry= "	INSERT INTO tbl_notificationFlag(id,txn,remarks,entry_date,user_id) ";				
		$qry.=" VALUES(?,?,?,now(),?)";
		//$qry.=" SELECT @id:=?,@txn:=?,@remarks:=?,now(),a.lcuseid FROM tbl_pa_vendor a  ";
		//$qry.=" LEFT JOIN tbl_lcseclocpa b on a.lcuseid=b.lcuseid ";
		//$qry.=" WHERE a.lvendr=? and b.lcstor=? ";		
		$txnRemarks="adjustment";
		try {
			$prepare=$db->prepare($qry);			 
			$prepare->execute(array($id,1,$txnRemarks,$vendor));		

			$qry= "	INSERT INTO tbl_notify(id,rcvr,txn,loc,pUpd) ";				
			$qry.=" VALUES(?,?,?,?,?)";
			$prepare=$db->prepare($qry);			 
			$prepare->execute(array($id,$vendor,1,$store,1));
		}catch(PDOException $e){
			//return $e->getMessage();
			return false;
		}
		return true;			
	}

	public function insertToCoor($db,$id,$heirarchy){
		$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		/**ADDED for notification for vendor*/
		foreach ($heirarchy as $key => $value) {	 
			$ar=array("\"","'");
			$value=str_replace($ar, "", $value);		 
			$split=explode("-",$value);
			/*$qry= "	INSERT INTO tbl_ccncoordDept(id,user_id,isdept,iclas) SELECT @id:=?,a.lcuseid,@isdept:=?,@iclas:=? FROM tbl_pa_vendor a  ";
			$qry.=" LEFT JOIN tbl_lcseclocpa b on a.lcuseid=b.lcuseid ";
			$qry.=" WHERE a.lvendr=? and b.lcstor=? ";		*/
			$qry= "	INSERT INTO tbl_ccncoordDept(id,isdept,iclas) VALUES(?,?,?)";		
			try {
				$prepare=$db->prepare($qry);	 
				$prepare->execute(array($id,intval($split[0]),$split[1]));			
				//$prepare->execute(array($id,$value[0],$value[1],$vendor_num,$str));			
			}catch(PDOException $e){
				//echo $e->getMessage();
			}		
			
		}
	}

    

}
?>
