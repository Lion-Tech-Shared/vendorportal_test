<?php
	require "../../class-main/connection.class.php";
	session_start();

	$conn = new getConnection();
	$db = $conn->PDO();
	$arr=array();
	$dr=$_GET['dr'];

	/* Get data if can Receive */
	$qryCanRec=" SELECT canApprove,canReceive FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qryCanRec);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
	$canReceive=$fetch[0][1];

	if($_SESSION['gid']==2 || $_SESSION['gid']==1){
		$prepare=$db->prepare("SELECT distinct dr_number FROM tbl_dr_hdr WHERE user_id=?");
		$prepare->execute(array($_SESSION['uid']));	
		$row=$prepare->fetchAll();

		$qry="  SELECT a.dr_number,b.status,c.isMerchApproved as isApprov FROM tbl_store_delivery a ";
		$qry.=" LEFT JOIN tbl_dr_hdr b ON a.dr_number=b.dr_number ";
		$qry.=" LEFT JOIN tbl_deliverytype c ON c.dr_number=a.dr_number ";
		$qry.=" WHERE a.dr_number=?  LIMIT 0,1";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($dr));	
		$row2=$prepare->fetchAll();

		echo $row2[0]['dr_number']."--".$row2[0]['status']."--".$row2[0]['isApprov'];
		/*for($i=0;$i<count($row);$i++){
			$qry="  SELECT a.dr_number,a.date_created,b.status,c.isMerchApproved as isApprov FROM tbl_store_delivery a ";
			$qry.=" LEFT JOIN tbl_dr_hdr b ON a.dr_number=b.dr_number ";
			$qry.=" LEFT JOIN tbl_deliverytype c ON c.dr_number=a.dr_number ";
			$qry.=" WHERE a.dr_number=?  LIMIT 0,1";
			$prepare2=$db->prepare($qry);
			$prepare2->execute(array($row[$i]['dr_number']));	
			$row2=$prepare2->fetchAll();
				 
			for($ii=0;$ii<count($row2);$ii++){			 
				$flag['dr']=$row2[0]['dr_number'];
				$flag['date']=date("Y-m-d",strtotime($row2[0]['date_created']));	
				$flag['status']=$row2[0]['status']==1?"Open":($row2[0]['status']==0?"Received":"Cancelled");	
				array_push($arr,$flag);
			}		
		}*/
	}else{
		//isset($_SESSION['isWhse'])?'WHSE':'STR'
		$isWhse=isset($_SESSION['isWhse'])?'1':'0';
		//$prepare=$db->prepare("SELECT a.dr_number FROM tbl_dr_hdr a left join tbl_user_info b on a.loc_code=b.store WHERE b.user_id=?");		
		//$prepare=$db->prepare("SELECT a.dr_number FROM tbl_dr_hdr a left join tbl_user_info b on a.loc_code=b.store WHERE b.user_id=? AND a.status=?");
		//$prepare->execute(array($_SESSION['uid'],1));	

		$tbl=($_SESSION['gid']==3?"tbl_lcsecloc":"tbl_lcseclocpa");
		if($canReceive==1){
			$qry=" SELECT isWhse FROM tbl_deliverytype WHERE dr_number=?";		 
			$prepare=$db->prepare($qry);
			$prepare->execute(array($dr));
			$fetch=$prepare->fetchAll();
			$isWhse=$fetch[0][0];
			if($isWhse==1){
				/*$qry=" SELECT a.dr_number,a.status,coalesce(b.isMerchApproved,2) as isApprov FROM tbl_dr_hdr a ";
				$qry.=" LEFT JOIN tbl_deliverytype b on a.dr_number=b.dr_number ";
				$qry.=" WHERE a.loc_code IN(";
			    $qry.="       SELECT strnum from tbl_store_master ";
			    $qry.="       WHERE strwhs IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?) ";
			    $qry.="       AND (STRHDO=? OR STRHDO=?))";
				$qry.=" AND b.isWhse=?";
				$qry.=" AND a.dr_number=?";*/
				$qry=" SELECT a.dr_number,a.status,coalesce(b.isMerchApproved,2) as isApprov FROM tbl_dr_hdr a ";
				$qry.=" LEFT JOIN tbl_deliverytype b on a.dr_number=b.dr_number ";
				$qry.=" WHERE a.loc_code IN(";
			    $qry.="       SELECT strwhs from tbl_store_master ";
			    $qry.="       WHERE strnum IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?) ";
			    $qry.="       AND STRHDO=?)";
				$qry.=" AND b.isWhse=?";
				$qry.=" AND a.dr_number=?";
				$prepare=$db->prepare($qry);
				$prepare->execute(array($_SESSION['uid'],'S',1,$dr));	
				//$prepare->execute(array($_SESSION['uid'],'S','W',1,$dr));	
			}else{
				$qry=" SELECT a.dr_number,a.status,coalesce(b.isMerchApproved,2) as isApprov FROM tbl_dr_hdr a";
				$qry.=" LEFT JOIN tbl_deliverytype b on a.dr_number=b.dr_number ";
				$qry.=" WHERE loc_code IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?)";
				$qry.=" AND a.dr_number=?";
				$prepare=$db->prepare($qry);
				$prepare->execute(array($_SESSION['uid'],$dr));	
			}	
		/*********FOR MECRH*************/
		}else{			
			$qry=" SELECT a.dr_number,a.status,coalesce(b.isMerchApproved,2) as isApprov FROM tbl_dr_hdr a";
			$qry.=" LEFT JOIN tbl_deliverytype b on a.dr_number=b.dr_number ";
			$qry.=" WHERE loc_code IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?)";
			$qry.=" AND a.dr_number=?";
			$prepare=$db->prepare($qry);
			$prepare->execute(array($_SESSION['uid'],$dr));	
			
		}
		
		$row=$prepare->fetchAll();
		if(count($row)<1){
			echo 0;
		}else{
			echo $row[0]['dr_number']."--".$row[0]['status']."--".$row[0]['isApprov'];
		}		
	}
	
	exit;
?>