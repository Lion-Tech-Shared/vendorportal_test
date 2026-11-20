<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();
 

$conn = new getConnection();
$db = $conn->PDO();
$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);


if(!isset($_SESSION["uid"]) ) {
    header("Location:../home.php");
} 
/* redirect if accessing through address */
if(!isset($_POST['flag'])){
	header("Location:../home.php");
}
$myCart=$_POST['dataArr'];
$flag=$_POST['flag'];

$remarks=$_POST['remarks'];

/***flag=2 regular pullout ; flag=1 transfer ; flag=3 total pull out */
$id=($flag==2?($_SESSION['regular']):($flag==1?($_SESSION['trf_num']):($_SESSION['total'])));
 
//echo $id;
//exit; 
/************for transfer************/
if($flag==1){
	foreach ($myCart as $key => $value) {	
		$qry= "	INSERT INTO tbl_transfer_hdr(trf_num,vendor_num,ccn_mf_num,fromLoc,toLoc,created_by,date_created,remarks)  ";
		$qry.=" VALUES(?,?,?,?,?,?,now(),?)  "; 
		$prepare=$db->prepare($qry);

		try{
			/******************************** vendor*****ccn_mf***fromLoc***toLoc*********************/
			$prepare->execute(array($id,$value[1],$value[5],$value[2],$value[3],$_SESSION['uid'],$remarks));			 
			$vendor_num=$value[1];
			//$str=$value[3];
			if($key < 1)
				$str = $value[2];
		}catch(PDOException $e){
			//echo $e->getMessage();
			//echo $e->getCode();
			//exit; 
		}

		$qry= "	INSERT INTO tbl_transfer_dtl(trf_num,sku,qty)  ";
		$qry.=" VALUES(?,?,?)  "; 
		$prepare=$db->prepare($qry);

		try{
			$prepare=$db->prepare($qry);
			$prepare->execute(array($id,$value[0],$value[4]));	  			
			
		}catch(PDOException $e){
			if($e->errorInfo[1] == 1062){
				//.echo $e->getMessage();
				$qry= "	UPDATE tbl_transfer_dtl 	    ";
				$qry.=" SET qty=qty+? WHERE trf_num=?   "; 
				$prepare=$db->prepare($qry);
				$prepare->execute(array($value[4],$id));		
			}					    		
		}
	}	
	/**ADDED for notification for vendor*/
	//$qry= "	INSERT INTO tbl_notificationFlag(id,txn,remarks,entry_date) VALUES(?,?,?,now()) ";				
	$qry= "	INSERT INTO tbl_notificationFlag(id,txn,remarks,entry_date,user_id) ";				
	$qry.=" SELECT @id:=?,@txn:=?,@remarks:=?,now(),a.lcuseid FROM tbl_pa_vendor a  ";
	$qry.=" LEFT JOIN tbl_lcseclocpa b on a.lcuseid=b.lcuseid ";
	$qry.=" WHERE a.lvendr=? and b.lcstor=? ";		
	try {
		$strs = explode(":",$str);
		$prepare=$db->prepare($qry);
		$txnRemarks=' Transfer Out Request ';
		$prepare->execute(array($id,2,$txnRemarks,$vendor_num,$str));		

		$qry= "	INSERT INTO tbl_notify(id,txn,loc) ";
		$qry.=" VALUES(?,?,?)";		
		$prepare=$db->prepare($qry);	 
	 	$prepare->execute(array($id,2,abs(trim($strs[0]))));	
	}catch(PDOException $e){
		$strs = explode(":",$str);
		echo $e->getMessage()."-".$strs[0]."-".$id;
	}	

	/**ADDED for notification for vendor commented because of tbl_notify */
	// foreach ($_POST['heirarchy'] as $key => $value) {	 
	// 	/*$qry= "	INSERT INTO tbl_ccncoordDept(id,user_id,isdept,iclas) SELECT @id:=?,a.lcuseid,@isdept:=?,@iclas:=? FROM tbl_pa_vendor a  ";
	// 	$qry.=" LEFT JOIN tbl_lcseclocpa b on a.lcuseid=b.lcuseid ";
	// 	$qry.=" WHERE a.lvendr=? and b.lcstor=? ";		*/
	// 	$qry= "	INSERT INTO tbl_ccncoordDept(id,isdept,iclas) VALUES(?,?,?)";		
	// 	try {
	// 		$prepare=$db->prepare($qry);	 
	// 		$prepare->execute(array($id,$value[0],$value[1]));			
	// 		//$prepare->execute(array($id,$value[0],$value[1],$vendor_num,$str));			
	// 	}catch(PDOException $e){
	// 		//echo $e->getMessage();
	// 	}		
		
	// }

}
/************for regular pull out*********/	
else if($flag==2 || $flag==3){
	$isTotalPullout=($flag==3?1:0);
	$txn = ($isTotalPullout==1?6:3);
	$remarksNoti=($isTotalPullout==1?"Total Pullout":"Regular Pullout");
	foreach ($myCart as $key => $value) {	
		if($key<1){		
			$qry= "	INSERT INTO tbl_pullout_hdr(pullout_num,vendor_num,fromLoc,created_by,date_created,isTotalPullout,remarks)  ";
			$qry.=" VALUES(?,?,?,?,now(),?,?)  "; 
			$prepare=$db->prepare($qry);
			$prepare->execute(array($id,$value[1],$value[2],$_SESSION['uid'],$isTotalPullout,$remarks));	
			$vendor_num=$value[1];			 
			$str = $value[2];
		}	
		try{
			/******************************** vendor*****ccn_mf***fromLoc***toLoc*********************/
			//echo $id.'-'.$value[1].'-'.$value[2].'-'.$_SESSION['uid'].'-'.$value[4];
			//exit;
			//date("Y-m-d",strtotime($value[4]))						
			$qry= "	INSERT INTO tbl_pullout_dtl(pullout_num,sku,qty)  ";
			$qry.=" VALUES(?,?,?)  "; 
			$prepare=$db->prepare($qry);
			$prepare->execute(array($id,$value[0],$value[3]));		 
		}catch(PDOException $e){
			if($e->errorInfo[1] == 1062){
				//.echo $e->getMessage();
				$qry= "	UPDATE tbl_pullout_dtl 	    ";
				$qry.=" SET qty=qty+? WHERE pullout_num=?   "; 
				$prepare=$db->prepare($qry);
				$prepare->execute(array($value[3],$id));		
			}		
		} 
	}
	/**ADDED for notification for vendor*/
	//$qry= "	INSERT INTO tbl_notificationFlag(id,txn,remarks,entry_date,user_id) VALUES(?,?,?,now(),?) ";				
	
	try {
		//$prepare=$db->prepare($qry);		
		//$prepare->execute(array($id,3,$remarksNoti,$vendor_num));		
		$strs = explode(":",$str);
		$qry= "	INSERT INTO tbl_notify(id,txn,loc,pUpd) ";
		$qry.=" VALUES(?,?,?,?)";		
		$prepare=$db->prepare($qry);	 
	 	$prepare->execute(array($id,$txn,abs(trim($strs[0])),0));	

	}catch(PDOException $e){
		echo $id."->".$e->getMessage();
	}	

	/**ADDED for notification for vendor*/
	/*foreach ($_POST['heirarchy'] as $key => $value) {	 
		$qry= "	INSERT INTO tbl_ccncoordDept(id,isdept,iclas) VALUES(?,?,?)";		 
		try {
			$prepare=$db->prepare($qry);	 
			$prepare->execute(array($id,$value[0],$value[1]));			
		}catch(PDOException $e){
			echo  $id."->".$e->getMessage();;
		}		
		
	}*/
}

 
echo 1;

?>
