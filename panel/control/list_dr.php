<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php"; 
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();
	/* redirect if accessing through address */
	if(!isset($_POST['flag'])){
		header("Location:../home.php");
	}
	$conn = new getConnection();
	$db = $conn->PDO();
	$arr=array();
	$arr2=array();

	/* Get data if can Receive */
	$qryCanRec=" SELECT canApprove,canReceive,isCoor FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qryCanRec);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
	$canReceive=$fetch[0][1];
	$canApprove=$fetch[0][0];
	$isCoor=$fetch[0]['isCoor'];

	/*-------------------VENDODR & ADMIN----------------------------------------------------*/
	if($_SESSION['gid']==2 || $_SESSION['gid']==1){
		$prepare=$db->prepare("SELECT distinct dr_number FROM tbl_dr_hdr WHERE user_id=?");
		$prepare->execute(array($_SESSION['uid']));	
		$row=$prepare->fetchAll();

		for($i=0;$i<count($row);$i++){
			$qry="  SELECT a.dr_number,b.date_created,b.status,coalesce(c.isMerchApproved,0) as isMerchApproved,c.isWhse ";
			$qry.=",d.v_name,e.descr as approvalDesc,c.remarks,c.isViewedV,c.isViewedR";
			$qry.=" ,b.loc_code,f.strnam,g.remarks as delRemarks,h.remarks as rcvRemarks,";
			$qry.=" i.vVwd,i.mVwd,i.pVwd,i.ctorVwd,i.mUpd,i.vUpd,i.pUpd";
			$qry.=",g.dr as docDr";
			$qry.=" FROM tbl_store_delivery a ";
			$qry.=" LEFT JOIN tbl_dr_hdr b on a.dr_number=b.dr_number ";
			$qry.=" LEFT JOIN tbl_deliverytype c on c.dr_number=b.dr_number ";
			$qry.=" LEFT JOIN tbl_vendor_master d on b.user_id=d.v_code ";
			$qry.=" LEFT JOIN tbl_approvalmainntenance e on e.inc=c.isMerchApproved ";
			$qry.=" LEFT JOIN tbl_store_master f on b.loc_code=f.strnum ";
			$qry.=" LEFT JOIN tbl_delwindow g on g.vd_dr=b.dr_number";
			$qry.=" LEFT JOIN tbl_receive_hdr h on h.dr_number=b.dr_number";
			$qry.=" LEFT JOIN tbl_notify i on i.id = b.dr_number";
			$qry.=" WHERE a.dr_number=? "; 
			$qry.=" ORDER BY b.date_created asc";
			$qry.=" LIMIT 0,1 ";
			$prepare2=$db->prepare($qry);
			$prepare2->execute(array($row[$i]['dr_number']));	
			$row2=$prepare2->fetchAll();
				 
			for($ii=0;$ii<count($row2);$ii++){			 
				$flag['dr']=$row2[0]['dr_number'];
				$flag['date']=$row2[0]['date_created'];	
				//$flag['date']=date("Y-m-d h:m:i",strtotime($row2[0]['date_created']));	
				$flag['status']=$row2[0]['status']==1?"Open":($row2[0]['status']==0?"Received":($row2[0]['status']==2?"Ordered":"Cancelled"));	
				$flag['isApproved']=$row2[0]['isMerchApproved'];
				$flag['isWhse']=$row2[0]['isWhse'];
				$flag['vname']=$row2[0]['v_name'];
				$flag['isApprovedDesc']=$row2[0]['approvalDesc'];
				$flag['remarks']="<font color='blue'>Merch </font>: ".$row2[0]['remarks'].", <font color='blue'>Vendor</font> :".$row2[0]['delRemarks'].", <font color='blue'>PA</font> :".$row2[0]['rcvRemarks'];
				$flag['isViewedV']=$row2[0]['isViewedV'];
				$flag['isViewedR']=$row2[0]['isViewedR'];
				$flag['vVwd']=$row2[0]['vVwd'];
				$flag['mVwd']=$row2[0]['mVwd'];
				$flag['pVwd']=$row2[0]['pVwd'];
				$flag['pUpd']=$row2[0]['pUpd'];
				$flag['mUpd']=$row2[0]['mUpd'];
				$flag['vUpd']=$row2[0]['vUpd'];
				$flag['ctorVwd']=$row2[0]['ctorVwd'];
				$flag['loc']=$row2[0]['loc_code'];
				$flag['str_desc']=$row2[0]['strnam'];
				$flag['docDr']=$row2[0]['docDr'];
				array_push($arr,$flag);
			}		
		}
	/* FOR merch/receiving(store or whse) */	
	}else{
		//isset($_SESSION['isWhse'])?'WHSE':'STR'
		$isWhse=isset($_SESSION['isWhse'])?'1':'0';
		//$prepare=$db->prepare("SELECT a.dr_number FROM tbl_dr_hdr a left join tbl_user_info b on a.loc_code=b.store WHERE b.user_id=?");
		
		//$prepare=$db->prepare("SELECT a.dr_number FROM tbl_dr_hdr a left join tbl_user_info b on a.loc_code=b.store WHERE b.user_id=? AND a.status=?");
		//$prepare->execute(array($_SESSION['uid'],1));	
		//$tbl=($_SESSION['gid']==3?"tbl_lcsecloc":"tbl_lcseclocpa");
		$tbl=($canApprove==1?"tbl_lcsecloc":"tbl_lcseclocpa");
		/*--------------------for group that can receive---------------*/
		if($canReceive==1){			
									//if($isWhse==1){
								/*******************backup fragment**********************/
								/*$qry=" SELECT a.dr_number FROM tbl_dr_hdr a ";
								$qry.=" LEFT JOIN tbl_deliverytype b on a.dr_number=b.dr_number ";
								$qry.=" WHERE a.loc_code IN(";
							    $qry.="       SELECT strnum from tbl_store_master ";
							    $qry.="       WHERE strwhs IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?) ";
							    $qry.="             OR strwhs IN(SELECT STRWHS FROM tbl_store_master WHERE strnum IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?)) ";
							    $qry.="       AND STRHDO=?)";
								$qry.=" AND b.isWhse=? ";	*/

			/*******STORES LINK TO WHSE *********************/
			$qry=" SELECT a.dr_number FROM tbl_dr_hdr a ";
			$qry.=" LEFT JOIN tbl_deliverytype b on a.dr_number=b.dr_number ";
			$qry.=" WHERE a.loc_code IN(";
		    $qry.="       SELECT strwhs from tbl_store_master ";
		    $qry.="       WHERE strnum IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?) ";
		    $qry.="            														   ";
		    $qry.="       AND STRHDO=?)";
			$qry.=" AND b.isWhse=? ";								
			$qry.=" AND a.status <> 1 ";
			$qry.=" AND a.vendor_code IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?)";

			$prepare=$db->prepare($qry); 
			$prepare->execute(array($_SESSION['uid'],'S',1,$_SESSION['uid']));	
			$row=$prepare->fetchAll();
			
			for($i=0;$i<count($row);$i++){
				array_push($arr2, $row[$i][0]);
			}
			//echo $arr2;
			//exit;
								//}else{
						 		 //$qry=" SELECT dr_number FROM tbl_dr_hdr  WHERE loc_code IN(SELECT LCSTOR FROM tbl_lcsecloc WHERE lcuseid=?) ";

			/*********STORES********/
			$qry="  SELECT dr_number FROM tbl_dr_hdr  WHERE loc_code IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?) ";			
			$qry.=" AND status <> 1  ";
			$qry.=" AND vendor_code IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?)";
			
			$prepare=$db->prepare($qry);
			$prepare->execute(array($_SESSION['uid'],$_SESSION['uid']));	
			$row=$prepare->fetchAll();
			 
			for($i=0;$i<count($row);$i++){
				array_push($arr2, $row[$i][0]);
			}				//}			
		}	
		/*****For Coordinator*******/	
		else if($isCoor==1){
			$tbl="tbl_lcsecloc";	
			/*$qry="  SELECT c.dr_number FROM tbl_ccnMain a ";
			$qry.=" LEFT JOIN  tbl_ccncoordDept b on a.isdept=b.isdept and a.iclas=b.iclas     ";
			$qry.=" LEFT JOIN  tbl_dr_hdr c on b.id=c.dr_number AND c.vendor_code=a.vendor  ";
			$qry.=" WHERE a.user_id=? and (b.isCoorVwd=? OR b.isCoorVwd=?) AND c.status=?				   ";*/
			$qry=" SELECT dr_number FROM tbl_dr_hdr  WHERE loc_code IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?) ";				 
			 
			$prepare=$db->prepare($qry);
			//$prepare->execute(array($_SESSION['uid'],0,1,0));
			$prepare->execute(array($_SESSION['uid']));	


			$fetch=$prepare->fetchAll();
			for($i=0;$i<count($fetch);$i++){
				array_push($arr2, $fetch[$i][0]);
			}

		} 
		/*****For merch*************/			
		else{
			$qry=" SELECT dr_number FROM tbl_dr_hdr  WHERE loc_code IN(SELECT LCSTOR FROM ".$tbl." WHERE lcuseid=?) ";				 
			$prepare=$db->prepare($qry);
			$prepare->execute(array($_SESSION['uid']));	
			$row=$prepare->fetchAll();		
			 
			for($i=0;$i<count($row);$i++){
				array_push($arr2, $row[$i][0]);
			}		
		}
		
		 
		for($i=0;$i<count($arr2);$i++){			 
			//$prepare2=$db->prepare("SELECT dr_number,date_created FROM tbl_store_delivery WHERE dr_number=? LIMIT 0,1");
			$qry="SELECT a.dr_number,b.date_created,b.status,coalesce(c.isMerchApproved,0) as isMerchApproved,c.isWhse,";
			$qry.=" d.v_name,e.descr as approvalDesc,c.remarks,c.isViewedV,c.isViewedR,";
			$qry.=" b.loc_code,f.strnam,g.remarks as delRemarks,h.remarks as rcvRemarks,";
			$qry.=" i.vVwd,i.mVwd,i.pVwd,i.ctorVwd,i.mUpd,i.vUpd,i.pUpd";
			$qry.=",g.dr as docDr";
			$qry.=" FROM tbl_store_delivery a ";
			$qry.=" LEFT JOIN tbl_dr_hdr b on a.dr_number=b.dr_number ";
			$qry.=" LEFT JOIN tbl_deliverytype c on c.dr_number=b.dr_number ";
			$qry.=" LEFT JOIN tbl_vendor_master d on b.user_id=d.v_code ";
			$qry.=" LEFT JOIN tbl_approvalmainntenance e on e.inc=c.isMerchApproved ";
			$qry.=" LEFT JOIN tbl_store_master f on b.loc_code=f.strnum ";
			$qry.=" LEFT JOIN tbl_delwindow g on g.vd_dr=b.dr_number";
			$qry.=" LEFT JOIN tbl_receive_hdr h on h.dr_number=b.dr_number";
			$qry.=" LEFT JOIN tbl_notify i on i.id = b.dr_number";
			$qry.=" WHERE a.dr_number=?";
			$qry.=" ORDER BY b.date_created asc";
			$qry.=" LIMIT 0,1";
			$prepare2=$db->prepare($qry);
			$prepare2->execute(array($arr2[$i]));	
			$row2=$prepare2->fetchAll();
				 
			for($ii=0;$ii<count($row2);$ii++){			 
				$flag['dr']=$row2[0]['dr_number'];
				$flag['date']=$row2[0]['date_created'];	
				//$flag['date']=date("Y-m-d h:m:i",strtotime($row2[0]['date_created']));	
				$flag['status']=$row2[0]['status']==1?"Open":($row2[0]['status']==0?"Received":($row2[0]['status']==2?"Ordered":"Cancelled"));			
				$flag['isApproved']=$row2[0]['isMerchApproved'];
				$flag['isWhse']=$row2[0]['isWhse'];
				$flag['vname']=$row2[0]['v_name'];
				$flag['isApprovedDesc']=$row2[0]['approvalDesc'];
				//$flag['remarks']=$row2[0]['remarks'];
				$flag['remarks']="<font color='blue'>Merch </font>: ".$row2[0]['remarks'].", <font color='blue'>Vendor</font> :".$row2[0]['delRemarks'].", <font color='blue'>PA</font> :".$row2[0]['rcvRemarks'];
				$flag['isViewedV']=$row2[0]['isViewedV'];
				$flag['isViewedR']=$row2[0]['isViewedR'];
				$flag['loc']=$row2[0]['loc_code'];
				$flag['str_desc']=$row2[0]['strnam'];
				$flag['vVwd']=$row2[0]['vVwd'];
				$flag['mVwd']=$row2[0]['mVwd'];
				$flag['pVwd']=$row2[0]['pVwd'];
				$flag['ctorVwd']=$row2[0]['ctorVwd'];
				$flag['pUpd']=$row2[0]['pUpd'];
				$flag['mUpd']=$row2[0]['mUpd'];
				$flag['vUpd']=$row2[0]['vUpd'];
				$flag['docDr']=$row2[0]['docDr'];
				array_push($arr,$flag);
			}		
		}
	}
	echo json_encode($arr);
	exit;
?>