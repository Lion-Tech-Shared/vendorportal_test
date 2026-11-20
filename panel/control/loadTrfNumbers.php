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

	if(!isset($_GET['flag'])){
		//header("Location:../home.php");
	}	

	$qry="  SELECT a.trf_num,d.ccmnam, ";
	//$qry.="        b.sku,e.descr,b.qty,";
	$qry.="         a.ccn_mf_num,a.fromLoc,a.toLoc,a.date_created,f.stat_desc ";
	$qry.=" ,g.strnam as toLocDesc,h.strnam as fromLocDesc ";
	$qry.=" FROM ";
	$qry.=" 		tbl_transfer_hdr a ";
	//$qry.=" LEFT JOIN tbl_transfer_dtl b 		  ";
	//$qry.=" 							ON a.trf_num=b.trf_num 			  ";
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
	 	
	if($_GET['flag']==2){		
		 /*****TRF STATUS*************/
		$qry2="	WHERE a.status=1 ";  
		/******Get Trf created for whse and will be visible to own creator in transfer receiving********/		
		$qry2.=" AND a.fromLoc IN(SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid=?)";
		$qry2.=" AND a.vendor_num IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?)";
		$qry2.=" AND g.strhdo=? ";
		$qry2=$qry.$qry2;
		$prepare=$db->prepare($qry2);		
		
		$prepare->execute(array($sessionId,$sessionId,'W'));
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
		}	
		
		$qry2="	 WHERE a.status=1 ";  		
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
		}	
		//$fetch=$arr;
		echo json_encode($arr);
		exit;
	}else{
		$qry.=" WHERE a.created_by=?";
		$prepare=$db->prepare($qry);	
		$prepare->execute(array($sessionId));
		$arr=$prepare->fetchAll();				
	}

echo json_encode($arr);
?>