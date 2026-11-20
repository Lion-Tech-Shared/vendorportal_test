<?php
require "../../class-main/connection.class.php";
session_start();

if($_SESSION['gid']==2 || $_SESSION['gid']==1){
	exit;
}
$isApprov=$_POST['isApprov'];
$ifQtyModified=$_POST['ifQtyModified'];
$dr=$_POST['dr'];
$gid=$_SESSION['gid'];
$remarks=$_POST['remarks']; 

$conn = new getConnection();
$db = $conn->PDO();
$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
 
/*flag=1 approve ; flag=-1 disapprove*/
$db->beginTransaction();
//$db->rollBack();
//$db->commit();
if($isApprov==1){
	//if($prepare){
		$qry="INSERT INTO tbl_delapproval(dr_number,DateApproved,ApprovedBy) VALUES(?,NOW(),?)";
		$prepare=$db->prepare($qry);			   
	    try{
		 	$prepare->execute(array($dr,$_SESSION['uid']));	  
		 	if($ifQtyModified==1){
				$dataArr=$_POST['dataArr'];
				foreach ($dataArr as $key => $value) {
					/*update old qty*/
					//echo $value[1];
					//exit;
					$qry="UPDATE tbl_store_delivery SET oldQty=qty,qty=? WHERE dr_number=? AND sku=? AND stock_num=?";
					$prepare=$db->prepare($qry);
					$prepare->execute(array($value[1],$dr,$value[0],$value[2]));	 

					//$qry="UPDATE tbl_store_delivery SET qty=? WHERE dr_number=?";
			 		//$prepare=$db->prepare($qry);
					//$prepare->execute(array($value[1],$dr));	
					
					/*$qry="SELECT * FROM tbl_deliverymod WHERE dr_number=?";
					$prepare=$db->prepare($qry);
					$prepare->execute(array($dr));	
					$fetch=$prepare->fetchAll();*/
					if($key < 1){
						$qry="INSERT INTO tbl_deliverymod(dr_number,modBy,dateMod) VALUES(?,?,now())";
						$prepare=$db->prepare($qry);
						$prepare->execute(array($dr,$_SESSION['uid']));			
					}
				}
			}
			$qry="UPDATE tbl_deliverytype SET isMerchApproved=?,remarks=?,isViewedV=? WHERE dr_number=?";
			$prepare=$db->prepare($qry);
			$prepare->execute(array($isApprov,$remarks,7,$dr));	

			$qry="UPDATE tbl_notify SET mUpd = ? WHERE id = ? ";
			$prepare=$db->prepare($qry);
			if($prepare->execute(array(1,$dr))){
				$db->commit();
				echo 1;
			}else{
				$db->rollBack();
				echo 0;
			}

			//$db->commit();
			//echo 1;
			exit;
		}catch(PDOException $e){
			$db->rollBack();
			echo "Error : ".$e->getMessage();
			exit;
		}
		
	//}
	 
}else{
	$qry="UPDATE tbl_deliverytype SET isMerchApproved=?,remarks=?,isViewedV=? WHERE dr_number=?";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($isApprov,$remarks,7,$dr));	
	$db->commit();
	echo -1;
}

?>