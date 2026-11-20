<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set */

	$conn = new getConnection();
	$db = $conn->PDO();
	$arr=array();
	$sessionId=$_SESSION['uid'];

	if(!isset($_GET['flag']) && $_SESSION['gid']!=2){
		header("Location:../home.php");
	}	
	$qry=" SELECT * FROM tbl_privileges WHERE gid=?"; 
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
    $isCoor=$fetch[0]['isCoor'];
    $canApprove=$fetch[0]['canApprove'];
    $gid=$_SESSION['gid'];
    if($isCoor==1){ 
	    $qry="  SELECT  c.trf_num,e.ccmnam, ";
	    $qry.="         c.ccn_mf_num,c.fromLoc,c.toLoc,c.date_created,f.stat_desc ";
	    $qry.=" 		,g.strnam as toLocDesc,h.strnam as fromLocDesc ";
	    $qry.=" FROM tbl_ccnMain a ";
		$qry.=" LEFT JOIN  tbl_ccncoordDept b on a.isdept=b.isdept and a.iclas=b.iclas     ";
		$qry.=" LEFT JOIN  tbl_transfer_hdr c on b.id=c.trf_num AND c.vendor_num=a.vendor  ";
		$qry.=" LEFT JOIN  tbl_lcsucn d ON  c.vendor_num=d.lvendr  	     				   ";
		$qry.=" LEFT JOIN  tbl_cssmst e ON 	d.lcnvdr=e.ccmnum	  	      				   ";
		$qry.=" LEFT JOIN  tbl_transfer_status f  ON c.status=f.id		 	      		   ";
		$qry.=" LEFT JOIN  tbl_store_master g ON g.strnum=c.toLoc			 	 		   "; 
		$qry.=" LEFT JOIN  tbl_store_master h ON h.strnum=c.fromLoc			  			   ";
		$qry.=" WHERE a.user_id=? and (b.isCoorVwd=? OR b.isCoorVwd=?) AND c.status=?						   ";
		 
		//$prepare=$db->prepare($qry);
		//$prepare->execute(array($_SESSION['uid'],0,1,2));

		$qry="  SELECT  c.trf_num,e.ccmnam, ";
	    $qry.="         c.ccn_mf_num,c.fromLoc,c.toLoc,c.date_created,f.stat_desc ";
	    $qry.=" 		,g.strnam as toLocDesc,h.strnam as fromLocDesc,a.* ";
	    $qry.=" FROM tbl_notify a ";		 
		$qry.=" LEFT JOIN  tbl_transfer_hdr c ON  a.id =c.trf_num  	     				   ";
		$qry.=" LEFT JOIN  tbl_lcsucn d ON  c.vendor_num=d.lvendr  	     				   ";
		$qry.=" LEFT JOIN  tbl_cssmst e ON 	d.lcnvdr=e.ccmnum	  	      				   ";
		$qry.=" LEFT JOIN  tbl_transfer_status f  ON c.status=f.id		 	      		   ";
		$qry.=" LEFT JOIN  tbl_store_master g ON g.strnum=c.toLoc			 	 		   "; 
		$qry.=" LEFT JOIN  tbl_store_master h ON h.strnum=c.fromLoc			  			   ";		 
		$qry.=" WHERE a.txn = ? AND a.pUpd = ?   ";
		$qry.=" 	  AND c.toLoc IN (SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid = ?)";
		$prepare=$db->prepare($qry);
		$prepare->execute(array(5,1,$_SESSION['uid']));

	}else if($gid==2){
		$qry="  SELECT  c.trf_num,e.ccmnam, ";
	    $qry.="         c.ccn_mf_num,c.fromLoc,c.toLoc,c.date_created,f.stat_desc ";
	    $qry.=" 		,g.strnam as toLocDesc,h.strnam as fromLocDesc ";
	    $qry.=" FROM tbl_notificationFlag a ";
		//$qry.=" LEFT JOIN  tbl_ccncoordDept b on a.isdept=b.isdept and a.iclas=b.iclas     ";
		$qry.=" LEFT JOIN  tbl_transfer_hdr c on a.id=c.trf_num								  ";
		$qry.=" LEFT JOIN  tbl_lcsucn d ON  c.vendor_num=d.lvendr  	     				   ";
		$qry.=" LEFT JOIN  tbl_cssmst e ON 	d.lcnvdr=e.ccmnum	  	      				   ";
		$qry.=" LEFT JOIN  tbl_transfer_status f  ON c.status=f.id		 	      		   ";
		$qry.=" LEFT JOIN  tbl_store_master g ON g.strnum=c.toLoc			 	 		   "; 
		$qry.=" LEFT JOIN  tbl_store_master h ON h.strnum=c.fromLoc			  			   ";
		$qry.=" WHERE c.vendor_num=? and (a.isVendorVwd=? OR a.isVendorVwd=?) AND c.status=?						   ";
		
		$qry="  SELECT  c.trf_num,e.ccmnam, ";
	    $qry.="         c.ccn_mf_num,c.fromLoc,c.toLoc,c.date_created,f.stat_desc ";
	    $qry.=" 		,g.strnam as toLocDesc,h.strnam as fromLocDesc,a.* ";
	    $qry.=" FROM tbl_notify a ";		 
		$qry.=" LEFT JOIN  tbl_transfer_hdr c ON  a.id =c.trf_num  	     				   ";
		$qry.=" LEFT JOIN  tbl_lcsucn d ON  c.vendor_num=d.lvendr  	     				   ";
		$qry.=" LEFT JOIN  tbl_cssmst e ON 	d.lcnvdr=e.ccmnum	  	      				   ";
		$qry.=" LEFT JOIN  tbl_transfer_status f  ON c.status=f.id		 	      		   ";
		$qry.=" LEFT JOIN  tbl_store_master g ON g.strnum=c.toLoc			 	 		   "; 
		$qry.=" LEFT JOIN  tbl_store_master h ON h.strnum=c.fromLoc			  			   ";		 
		$qry.=" WHERE c.vendor_num = ?   ";
		$prepare=$db->prepare($qry);
		$prepare->execute(array($_SESSION['uid']));
	}else if( $canApprove == 1 ){
		$qry="  SELECT  c.trf_num,e.ccmnam, ";
	    $qry.="         c.ccn_mf_num,c.fromLoc,c.toLoc,c.date_created,f.stat_desc ";
	    $qry.=" 		,g.strnam as toLocDesc,h.strnam as fromLocDesc,a.* ";
	    $qry.=" FROM tbl_notify a ";		 
		$qry.=" LEFT JOIN  tbl_transfer_hdr c ON  a.id =c.trf_num  	     				   ";
		$qry.=" LEFT JOIN  tbl_lcsucn d ON  c.vendor_num=d.lvendr  	     				   ";
		$qry.=" LEFT JOIN  tbl_cssmst e ON 	d.lcnvdr=e.ccmnum	  	      				   ";
		$qry.=" LEFT JOIN  tbl_transfer_status f  ON c.status=f.id		 	      		   ";
		$qry.=" LEFT JOIN  tbl_store_master g ON g.strnum=c.toLoc			 	 		   "; 
		$qry.=" LEFT JOIN  tbl_store_master h ON h.strnum=c.fromLoc			  			   ";		 
		$qry.=" WHERE a.txn = ? AND a.pUpd = ?  ";
		$prepare=$db->prepare($qry);
		$prepare->execute(array(5,1));
	}
    else{
		$qry="  SELECT a.trf_num,d.ccmnam, ";
		//$qry.="        b.sku,e.descr,b.qty,";
		$qry.="         a.ccn_mf_num,a.fromLoc,a.toLoc,a.date_created,f.stat_desc ";
		$qry.=" ,g.strnam as toLocDesc,h.strnam as fromLocDesc ";
		$qry.=" FROM ";
		$qry.=" 		tbl_transfer_hdr a ";
		//$qry.=" LEFT JOIN tbl_transfer_dtl b 		  ";
		//$qry.=" 							ON a.trf_num=b.trf_num 			  ";
		$qry.=" LEFT JOIN ";		
		$qry.="  		tbl_notificationFlag i   	  ";
		$qry.=" ON ";
		$qry.="		    a.trf_num=i.id	  			  ";
		$qry.=" LEFT JOIN ";		
		$qry.="  		tbl_lcsucn c    		 	  ";
		$qry.=" ON ";
		$qry.="		    a.vendor_num=c.lvendr  	      ";
		$qry.=" LEFT JOIN 							  ";
		$qry.="			tbl_cssmst d    		  	  ";
		$qry.=" ON ";
		$qry.=" 		c.lcnvdr=d.ccmnum	  	      ";
		//$qry.=" LEFT JOIN tbl_invmst e    		  ";
		//$qry.=" 							ON b.sku=e.sku 			 	      ";
		$qry.=" LEFT JOIN 							  ";
		$qry.="			tbl_transfer_status f    	  ";
		$qry.=" ON 									  ";
		$qry.="			a.status=f.id		 	      ";
		$qry.=" LEFT JOIN 							  ";
		$qry.="			tbl_store_master g 		      ";
		$qry.=" ON 									  ";
		$qry.="			g.strnum=a.toLoc			  "; 

		$qry.=" LEFT JOIN 							  ";
		$qry.="			tbl_store_master h 		      ";
		$qry.=" ON 									  ";
		$qry.="			h.strnum=a.fromLoc			  ";
		 	

		
		$qry2="	WHERE a.status=? ";  
		/******Get Trf created for whse and will be visible to own creator in transfer receiving********/		
		//$qry2.=" AND a.fromLoc IN(SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid=?)";
		//$qry2.=" AND a.vendor_num IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?)";
		//$qry2.=" AND g.strhdo=? ";
		$qry2.=" AND a.vendor_num=? AND isVendorVwd=? ";
		$qry2=$qry.$qry2;
		$prepare=$db->prepare($qry2);		
		
		$prepare->execute(array(2,$sessionId,0));
	}
	$fetch=$prepare->fetchAll();
	 	
	/*for($i=0;$i<count($fetch);$i++){ 
		$myArr['trf_num']=$fetch[$i][0];
		$myArr['ccmnam']=$fetch[$i][1];
		$myArr['ccn_mf_num']=$fetch[$i][2];
		$myArr['fromLoc']=$fetch[$i][3];
		$myArr['toLoc']=$fetch[$i][4];
		$myArr['date_created']=$fetch[$i][5];
		$myArr['stat_desc']=$fetch[$i][6];
		$myArr['fromLocDesc']=$fetch[$i][8];
		$myArr['toLocDesc']=$fetch[$i][7];						
		array_push($arr,$myArr);
	}*/
	
	/*$qry2="	 WHERE a.status=1 ";  		
	$qry2.=" AND a.toLoc IN(SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid=?)";
	$qry2.=" AND a.vendor_num IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?)";		
	$qry2=$qry.$qry2;
	$prepare=$db->prepare($qry2);		
	
	$prepare->execute(array($sessionId,$sessionId));
	$fetch=$prepare->fetchAll();
	for($i=0;$i<count($fetch);$i++){ 
		$myArr['trf_num']=$fetch[$i][0];
		$myArr['ccmnam']=$fetch[$i][1];
		$myArr['ccn_mf_num']=$fetch[$i][2];
		$myArr['fromLoc']=$fetch[$i][3];
		$myArr['toLoc']=$fetch[$i][4];
		$myArr['date_created']=$fetch[$i][5];
		$myArr['stat_desc']=$fetch[$i][6];
		$myArr['fromLocDesc']=$fetch[$i][8];
		$myArr['toLocDesc']=$fetch[$i][7];					
		array_push($arr,$myArr);
	}*/	
	//$fetch=$arr;
	echo json_encode($fetch);	
?>