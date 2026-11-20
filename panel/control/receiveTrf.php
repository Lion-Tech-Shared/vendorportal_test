<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set */

	$conn = new getConnection();
	$db = $conn->PDO();
	$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}	
	$trf=$_GET['trf'];


	$dataArr=$_GET['dataArr'];
	$qry="  SELECT a.vendor_num,a.fromLoc,b.sku,a.toLoc,sum(b.qty) as qty ";
	$qry.=" FROM tbl_transfer_hdr a";
	$qry.=" LEFT JOIN tbl_transfer_dtl b ON a.trf_num=b.trf_num ";
	$qry.=" WHERE a.trf_num=? ";
	$qry.=" GROUP BY a.vendor_num,a.fromLoc,b.sku,a.toLoc "; 	

	$remarks=$_GET['remarks']; 
	
	$prepare=$db->prepare($qry);
	$errorFlag=0;
	
	$prepare->execute(array($trf));
	$fetch=$prepare->fetchAll();


	foreach ($dataArr as $key => $value) {		 		
		$sku=$value[0];		
		$qty=$value[1]; 

		$vendor=$fetch[0][0];
		$fromLoc=$fetch[0][1];		
		$toLoc=$fetch[0][3];
		/*}
		for($i=0;$i<count($fetch);$i++){			 
			$vendor=$fetch[$i][0];
			$fromLoc=$fetch[$i][1];
			$sku=$fetch[$i][2];
			$toLoc=$fetch[$i][3];
			$qty=$fetch[$i][4];*/
			 
		$qry="  SELECT qty as inTransit FROM tbl_transfer_dtl WHERE trf_num = ? AND sku = ? ";		 
		$prepare=$db->prepare($qry); 			
		$prepare->execute(array($trf,$sku));		
		$getDtl = $prepare->fetchAll();
		//echo count($getDtl);
		//exit;

		/*if($getDtl[0]['inTransit']>$qty){

		}*/
		$updQty = $getDtl[0]['inTransit']-$qty;
		try {						
			/******INSERT IF NOT EXIST IN INVENTORY****************/
			$qry="  INSERT INTO tbl_inventory(vendor,store,sku,on_hand) ";
			$qry.=" VALUES(?,?,?,?) ";
			$prepare=$db->prepare($qry); 			
			$prepare->execute(array($vendor,$toLoc,$sku,$qty));	


			$qry="UPDATE tbl_inventory SET in_transit=in_transit-? WHERE vendor=? AND store=? AND sku=?";	 	 	
			$prepare=$db->prepare($qry); 					
			$prepare->execute(array($getDtl[0]['inTransit'],$vendor,$fromLoc,$sku));						

			/************UPDATE to location on hand*************************************************************/	
			/*$qry="UPDATE tbl_inventory SET on_hand=on_hand+? WHERE vendor=? AND store=? AND sku=?";	 	 	
			$prepare=$db->prepare($qry); 			 
			$prepare->execute(array($qty,$vendor,$toLoc,$sku));		*/

			$qry="UPDATE tbl_transfer_dtl SET rec_qty=? WHERE trf_num=? AND sku=?";	 	 	
			$prepare=$db->prepare($qry); 			 
			$prepare->execute(array($qty,$trf,$sku));					 				 								 

			if( $updQty > 0){
				$qry="UPDATE tbl_inventory SET on_hand=on_hand+? WHERE vendor=? AND store=? AND sku=?";	 	 	
				$prepare=$db->prepare($qry); 			 
				$prepare->execute(array($updQty,$vendor,$fromLoc,$sku));	
			}
			 

		}catch(PDOException $e){
			if($e->errorInfo[1] == 1062){
				 /************UPDATE From location transit*************************************************************/	
				$qry="UPDATE tbl_inventory SET in_transit=in_transit-? WHERE vendor=? AND store=? AND sku=?";	 	 	
				$prepare=$db->prepare($qry); 

				try{
					$prepare->execute(array($getDtl[0]['inTransit'],$vendor,$fromLoc,$sku));						
					/************UPDATE to location on hand*************************************************************/	
					$qry="UPDATE tbl_inventory SET on_hand=on_hand+? WHERE vendor=? AND store=? AND sku=?";	 	 	
					$prepare=$db->prepare($qry); 			 
					$prepare->execute(array($qty,$vendor,$toLoc,$sku));		

					$qry="UPDATE tbl_transfer_dtl SET rec_qty=? WHERE trf_num=? AND sku=?";	 	 	
					$prepare=$db->prepare($qry); 			 
					$prepare->execute(array($qty,$trf,$sku));					 				 								 

					if( $updQty > 0){
						$qry="UPDATE tbl_inventory SET on_hand=on_hand+? WHERE vendor=? AND store=? AND sku=?";	 	 	
						$prepare=$db->prepare($qry); 			 
						$prepare->execute(array($updQty,$vendor,$fromLoc,$sku));	
					}
				}catch(PDOException $e){
					 	$errorFlag=1;  
					 	/********************revert update if error occurs***************************************/
						/*$qry="UPDATE tbl_inventory SET on_hand=on_hand-? WHERE vendor=? AND store=? AND sku=?";	 	 	
						$prepare=$db->prepare($qry); 			 
						$prepare->execute(array($qty,$vendor,$toLoc,$sku));						 				 					

						
						$qry="UPDATE tbl_inventory SET in_transit=in_transit+? WHERE vendor=? AND store=? AND sku=?";	 	 	
						$prepare=$db->prepare($qry); 
						$prepare->execute(array($qty,$vendor,$fromLoc,$sku));

						$qry="UPDATE tbl_transfer_dtl SET rec_qty=? WHERE trf_num=? AND sku=?";	 	 	
						$prepare=$db->prepare($qry); 			 
						$prepare->execute(array(0,$trf,$sku));	*/

					 	echo $e->getMessage();
						exit;
				}
			}else{
				echo $e->getMessage();
			}	
		}	

		/**check if vendor ,store,sku, to loc exist in inventory**/
		/*$qry="  SELECT * FROM tbl_inventory ";
		$qry.=" WHERE vendor=? AND store=? AND sku=? ";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($vendor,$toLoc,$sku));
		$fetch2=$prepare->fetchAll();
		if(count($fetch2)>0){*/
			/************UPDATE From location transit*************************************************************/	
			/*$qry="UPDATE tbl_inventory SET in_transit=in_transit-? WHERE vendor=? AND store=? AND sku=?";	 	 	
			$prepare=$db->prepare($qry); 

			try{
				$prepare->execute(array($qty,$vendor,$fromLoc,$sku));		*/				
				/************UPDATE to location on hand*************************************************************/	
				/*$qry="UPDATE tbl_inventory SET on_hand=on_hand+? WHERE vendor=? AND store=? AND sku=?";	 	 	
				$prepare=$db->prepare($qry); 			 
				$prepare->execute(array($qty,$vendor,$toLoc,$sku));		

				$qry="UPDATE tbl_transfer_dtl SET rec_qty=? WHERE trf_num=? AND sku=?";	 	 	
				$prepare=$db->prepare($qry); 			 
				$prepare->execute(array($qty,$trf,$sku));					 				 								 

			}catch(PDOException $e){
				 	$errorFlag=1;  */
				 	/********************revert update if error occurs***************************************/
					/*$qry="UPDATE tbl_inventory SET on_hand=on_hand-? WHERE vendor=? AND store=? AND sku=?";	 	 	
					$prepare=$db->prepare($qry); 			 
					$prepare->execute(array($qty,$vendor,$toLoc,$sku));						 				 					

					
					$qry="UPDATE tbl_inventory SET in_transit=in_transit+? WHERE vendor=? AND store=? AND sku=?";	 	 	
					$prepare=$db->prepare($qry); 
					$prepare->execute(array($qty,$vendor,$fromLoc,$sku));

					$qry="UPDATE tbl_transfer_dtl SET rec_qty=? WHERE trf_num=? AND sku=?";	 	 	
					$prepare=$db->prepare($qry); 			 
					$prepare->execute(array(0,$trf,$sku));	*/

			/*	 	echo $e->getMessage();
					exit;
			}

		}else{*/
			/*$qry="UPDATE tbl_inventory SET in_transit=in_transit-? WHERE vendor=? AND store=? AND sku=?";	 	 	
			$prepare=$db->prepare($qry); */
			//try{
				/*$prepare->execute(array($qty,$vendor,$fromLoc,$sku));	*/

				/******INSERT IF NOT EXIST IN INVENTORY****************/
				/*$qry="  INSERT INTO tbl_inventory(vendor,store,sku,on_hand) ";
				$qry.=" VALUES(?,?,?,?) ";
				$prepare=$db->prepare($qry); 			
				$prepare->execute(array($vendor,$toLoc,$sku,$qty));				 
			
			}catch(PDOException $e){
					$errorFlag=1;	*/
					/*******revert update if error occurs**************/
			/*		$qry="DELETE FROM tbl_inventory WHERE vendor=? AND store=? AND sku=?";	 	 	
					$prepare=$db->prepare($qry); 
					$prepare->execute(array($vendor,$toLoc,$sku));	
					$errorFlag=1;

					$qry="UPDATE tbl_inventory SET in_transit=in_transit+? WHERE vendor=? AND store=? AND sku=?";	 	 	
					$prepare=$db->prepare($qry); 					 
					$prepare->execute(array($qty,$vendor,$fromLoc,$sku));	
					echo $e->getMessage();
					exit;
			}
			
		}*/
		

	}		
	/**************UPDATE STATUS*******************************/
	if($errorFlag==0){
		$qry="  UPDATE tbl_transfer_hdr SET status=?,received_by=?,date_received=now(),rcv_remarks=? WHERE trf_num=? ";	 	 	
		$prepare=$db->prepare($qry); 
		try{
			$prepare->execute(array(2,$_SESSION['uid'],$remarks,$trf));			
			 echo 1;
		}catch(exception $e){
			 echo 0;
		}	
		$qry="  UPDATE tbl_notify SET pUpd = ? ";
		$qry.=" WHERE id = ? ";
		$prepare=$db->prepare($qry); 			
		$prepare->execute(array(1,$trf));	
	}
		 
	 /**/

	
	 
  
?>