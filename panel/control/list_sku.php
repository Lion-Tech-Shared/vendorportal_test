<?php
	require "../../class-main/connection.class.php";
	session_start();
	//$contract_num=$_GET['contract_num'];
	$vendor_num=0;
	$globalIdept=0;
	if(isset($_GET['vendor_num'])){
		$vendor_num=$_GET['vendor_num'];
		//$globalIdept=$_GET['globalIdept'];
		$globalClas = $_GET['globalClas'];
	}else{
		$vendor_num=$_SESSION['uid'];
		//$globalIdept=$_GET['globalIdept'];
	}
	$globalIdept=$_GET['globalIdept'];
	$conn = new getConnection();
	$db = $conn->PDO();
	 	
	//$prepare=$db->prepare('SELECT * FROM tbl_cssdtl WHERE cdtcct='.$contract_num);
	//$prepare->execute();
	//$row=$prepare->fetchAll();	 	
	$array=array();
	//echo $vendor_num."-".$globalIdept."-contract:".$contract_num."-";
	//exit;
	/***************************************************************************/
	/*	          added 10-04-2016 											 ***/
	//$qry="  SELECT a.lvendr,a.lcnvdr,c.cctnum,c.cctcss,d.cdtcct,d.cdtval,d.cdttyp ";
	$qry="  SELECT distinct d.cdtval,d.cdttyp ";
	$qry.=" FROM tbl_lcsucn a ";
	$qry.=" LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum ";
	$qry.=" LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss ";
	$qry.=" LEFT JOIN tbl_cssdtl d on c.cctnum=d.cdtcct ";
	//$qry.=" WHERE a.lvendr=? and curdate() BETWEEN date(c.cctlsd) AND date(c.cctled) AND a.lcnvdr=? ";
	$qry.=" WHERE a.lvendr=? AND a.lcnvdr=? ";
	//$qry.=" AND date_format(curdate(),'%Y%m%d')  BETWEEN date(c.cctlsd) AND date(c.cctled) ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($vendor_num,$globalIdept));
	$row=$prepare->fetchAll();	 	
 	/**************************************************************************/
 	$sub_dept=$globalIdept;

 	
	for($i=0;$i<count($row);$i++){
		if($row[$i]["cdttyp"]==4){
			$dept=substr($row[$i]["cdtval"],0,3); 
			$sub_dept=substr($row[$i]["cdtval"],3,3);  
			$class=substr($row[$i]["cdtval"],6,3); 

			if(isset($_GET['globalClas'])){
				//if($class != $globalClas) 
				//	continue;

				if($_GET['globalClas']==$class){
						$prepare2=$db->prepare("SELECT * FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND class=".$class);						
						$prepare2->execute();	
						$row2=$prepare2->fetchAll();
						break;
				}

			}
			else {
				if($_SESSION['gid'] == 2){
					break;
				}
				$prepare2=$db->prepare("SELECT * FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND class=".$class);						
				$prepare2->execute();	
				$row2=$prepare2->fetchAll();	
				break;
			}
			
			//echo "dept:".$dept."  class:".$class." ";
 			//exit;
		}
		else if($row[$i]["cdttyp"]==5){
			$dept=substr($row[$i]["cdtval"],0,3); 
			$sub_dept=substr($row[$i]["cdtval"],3,3); 	
			$prepare2=$db->prepare("SELECT sku,descr FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept);	
			$prepare2->execute();	
			$row2=$prepare2->fetchAll();
			break;
			//echo $sub_dept;
			//exit;			
		}
		else if($row[$i]["cdttyp"]==3){
			continue;			
			$dept=substr($row[$i]["cdtval"],0,3); 
			$sub_dept=substr($row[$i]["cdtval"],3,3); 
			$class=substr($row[$i]["cdtval"],6,3); 
			$sub_class=substr($row[$i]["cdtval"],10,3); 

			if(isset($_GET['globalClas'])){
				if($_GET['globalClas']==$class){
			   	    $prepare2=$db->prepare("SELECT * FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND class=".$class." AND sub_class=".$sub_class);			
					$prepare2->execute();	
					$row2=$prepare2->fetchAll();
				}
			}else{
				$prepare2=$db->prepare("SELECT * FROM tbl_invmst where dept=".$dept."  AND sub_dept=".$sub_dept." AND class=".$class." AND sub_class=".$sub_class);			
				$prepare2->execute();	
				$row2=$prepare2->fetchAll();
			}
			break;
			
		}
		else if($row[$i]["cdttyp"]==1){
			$sku=$row[$i]["cdtval"];
			$prepare2=$db->prepare('SELECT * FROM tbl_invmst where sku='.$sku);			
			$prepare2->execute();	
			$row2=$prepare2->fetchAll();	
			break;
		}
		

		
	}
	for($ii=0;$ii<count($row2);$ii++){
			$flag['sku']=$row2[$ii]['sku'];
			$flag['descr']=$row2[$ii]['descr'];		
			//echo $row2[$ii]["sku"]."-".$row2[$ii]['descr'];		
			$flag['class']=$row[$i]["cdttyp"];

 			//exit;	 	
			array_push($array, $flag);			 
	}
	
	echo json_encode($array);
?>