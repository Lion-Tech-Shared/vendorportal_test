<?php 
	require "../../class-main/connection.class.php";
	session_start();
	//con_num:contractNum,store:store,vndr_num:vendor_num,qty:total_qty,amt:total_amt
	$conn = new getConnection();
	$db = $conn->PDO();
	$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
	$arr=$_POST['arr'];
	//$dr_num=$_POST['dr_num'];$_SESSION['dr_num']
	$dr_num=$_SESSION['dr_num'];
	$store=$_POST['store'];
	$contractNum=$_POST['con_num'];
	//$vendor=$_POST['vndr_num'];
	$vendor=$_SESSION['uid'];
	$qty=$_POST['qty'];
	$amt=$_POST['amt'];
	 
	$qry="SELECT * FROM tbl_dr_hdr WHERE dr_number=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($dr_num));		
	$fetch=$prepare->fetchAll();

	if(count($fetch)>0){
		echo "DR# Already Exist, We will clear the form";
		exit;
	}

	$prepare=$db->prepare("INSERT INTO tbl_dr_hdr(user_id,dr_number,contract_num,vendor_code,loc_code,total_qty,total_amt,status,date_created) VALUES(?,?,?,?,?,?,?,?,now()) ");
	$ifSuccessFull=$prepare->execute(array($_SESSION['uid'],$dr_num,$contractNum,$vendor,$store,$qty,$amt,1));	 

	if($ifSuccessFull){
		$isWhse = ($_SESSION['delType']=='warehouse'?'1':'0');
		$prepare = $db->prepare("INSERT INTO tbl_deliverytype(dr_number,isWhse) VALUES(?,?) ");
		$prepare->execute(array($dr_num,$isWhse));	 
		foreach ($arr as $key => $value) {
	 		//echo $value[0].'-'.$value[1]; 		 	 
	 		/*$prepare=$db->prepare("SELECT * FROM tbl_store_delivery WHERE dr_number=? AND sku=? AND stock_num=? ");
			$prepare->execute(array($dr_num,$value[0],$value[1]));		
			$fetch=$prepare->fetchAll();
			*/
			try{
				$prepare2 = $db->prepare("INSERT INTO tbl_store_delivery(dr_number,sku,stock_num,qty,mms_dr) VALUES(?,?,?,?,?) ");
				$prepare2->execute(array($dr_num,$value[0],$value[1],$value[2],$contractNum));							
			}catch(PDOException $e){
				if($e->errorInfo[1] == 1062){
					$prepare2 = $db->prepare("UPDATE tbl_store_delivery set qty=qty+? WHERE dr_number=? AND sku=? AND stock_num=? ");
					$prepare2->execute(array($value[2],$dr_num,$value[0],$value[1]));
				}
			}
			/*
			if(count($fetch) > 0){
				$prepare2=$db->prepare("UPDATE tbl_store_delivery set qty=qty+? WHERE dr_number=? AND sku=? AND stock_num=? ");
				$prepare2->execute(array($value[2],$dr_num,$value[0],$value[1]));
			}else{
				$prepare2=$db->prepare("INSERT INTO tbl_store_delivery(dr_number,sku,stock_num,qty,mms_dr) VALUES(?,?,?,?,?) ");
				$prepare2->execute(array($dr_num,$value[0],$value[1],$value[2],$contractNum));	
			}
	 		*/ 		
		}
		/**ADDED for notification for vendor*/		 
		$qry = " INSERT INTO tbl_notificationFlag(id,txn,remarks,entry_date,user_id) ";				
		$qry.= " SELECT @id:=?,@txn:=?,@remarks:=?,now(),a.lcuseid FROM tbl_pa_vendor a  ";
		$qry.= " LEFT JOIN tbl_lcseclocpa b on a.lcuseid=b.lcuseid ";
		$qry.= " WHERE a.lvendr=? and b.lcstor=? ";		
		$txnRemarks = "Store Delivery";
		try {
			$prepare=$db->prepare($qry);			 
			$prepare->execute(array($dr_num,4,$txnRemarks,$vendor,$store));		
		}catch(PDOException $e){
			echo $e->getMessage();
		}	

		/**ADDED for notification for vendor*/
		// foreach ($_POST['heirarchy'] as $key => $value) {	 
		// 	$ar=array("\"","'");
		// 	$value=str_replace($ar, "", $value);		 
		// 	$split=explode("-",$value);
		// 	$qry= "	INSERT INTO tbl_ccncoordDept(id,user_id,isdept,iclas) SELECT @id:=?,a.lcuseid,@isdept:=?,@iclas:=? FROM tbl_pa_vendor a  ";
		// 	$qry.=" LEFT JOIN tbl_lcseclocpa b on a.lcuseid=b.lcuseid ";
		// 	$qry.=" WHERE a.lvendr=? and b.lcstor=? ";		
		// 	$qry= "	INSERT INTO tbl_ccncoordDept(id,isdept,iclas) VALUES(?,?,?)";		
		// 	try {
		// 		$prepare=$db->prepare($qry);	 
		// 		$prepare->execute(array($dr_num,intval($split[0]),$split[1]));			
		// 		//$prepare->execute(array($id,$value[0],$value[1],$vendor_num,$str));			
		// 	}catch(PDOException $e){
		// 		//echo $e->getMessage();
		// 	}		
			
		// }
		//echo "1-".$isWhse."=".$split."-".$split[0];
		$myClass = new myClass();
		$myClass->notify($db,$dr_num,4,$store);

		echo "1-".$isWhse;
	}else{
		echo "Error In Data Storing!";
	}




	class myClass{

		 public function __construct(){


		 }

		 public function notify($db,$dr,$txn,$store){

		 	$qry = "	INSERT INTO tbl_notify(id,txn,loc) VALUES(?,?,?)";		
			try {
				$prepare=$db->prepare($qry);	 
				$prepare->execute(array($dr,$txn,$store));	
				return true;						 
			}catch(PDOException $e){
				 
			}	
			return false;
		 }

	}
	
?>