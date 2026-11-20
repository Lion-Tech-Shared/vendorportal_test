<?php   
/*-------------------------------------------------------* 		   			
 					     myClass
*-------------------------------------------------------*/
 
class myClass
{

	private $arr = array();	
	public $gid;
	private $splt = array();
	private $db;

	public function __construct($ar,$db)
	{		 
		$this->arr = $ar;
		$this->splt = explode("-",$this->arr);
		$this->db = $db;
	}

	public function setGid(){
		$this->gid = $this->splt[0];
	}

	public function gId(){
		//$this->setGid();
		//return $this->gid;
	}

	public function isVendor(){
		if($_SESSION['gid'] == 2)
			return true;

		return false;
		 
	}

	public function canApprove(){
		
		if( $this->splt[1] == 1)
			return true;

		return false;
	}

	public function canReceive(){
		if( $this->splt[2] == 1)
			return true;

		return false;
	}

	public function isCoordinator(){
		if( $this->splt[3] == 1)
			return true;

		return false;
	}

	public function isPA(){
		if( $this->splt[4] == 1)
			return true;

		return false;
	}

	public function canViewOrderNoti(){
		if( $this->splt[5] == 1)
			return true;

		return false;
	}

	public function canViewReceiveNoti(){
		if( $this->splt[6] == 1)
			return true;

		return false;
	}

	public function getNoti($db,$col){
		/*Exclude pullout txn because there is a separate for processing getnoti6*/
		$qry = " SELECT * FROM tbl_notify a ";
		$qry.= " LEFT JOIN tbl_dr_hdr b ";						 
		$qry.= " ON a.id = b.dr_number ";						 
		$qry.= " WHERE (".$col['flag']." = ? AND ".$col['xFlag']." = ? AND  a.txn != 3 AND a.txn != 6) OR ";
		$qry.= " (".$col['or']." = ? AND ".$col['or2']." = ?  AND ".$col['xFlag4']." = ? AND  a.txn != 3 AND a.txn != 6) ";
		$qry.= " OR (".$col['or2']." = ? AND  a.txn != 3 AND a.txn != 6) ";
		$qry.= " OR ( ".$col['or3']." = ? AND ".$col['or4']." = ? AND  a.txn != 3 AND a.txn != 6)";				 
		//$qry.="  AND  a.txn != ? AND a.txn != ? ";
		$prepare=$db->prepare($qry);

		$prepare->execute(array($col['val'],$col['val2'],$col['orVal'],$col['orVal2'],$col['val5'],$col['orVal2'],$col['orVal3'],$col['orVal4']));		
		$fetch = $prepare->fetchAll();
		return $fetch;
	}
	/* For can approve */
	public function getNoti2($db,$col){
		$qry = " SELECT * FROM tbl_notify a ";
		$qry.= " LEFT JOIN tbl_dr_hdr b ";						 
		$qry.= " ON a.id = b.dr_number ";						 
		$qry.= " WHERE ".$col['flag']." = ? AND ".$col['xFlag']." = ? AND ".$col['xFlag2']." = ? AND a.txn != 3 AND a.txn != 6 AND a.txn != 5 ";
		$qry.= "  OR ( ".$col['or']." = ? AND ".$col['or2']." = ? AND a.txn != 3 AND a.txn != 6 AND a.txn != 5) ";
		$qry.= "  OR (".$col['or2']." = ? AND a.txn != 3 AND a.txn != 6 AND a.txn != 5)";			
		if( $this-> canApprove() ){
			$qry.= "  OR (mUpd = 1 AND vUpd =1 AND pUpd = 0 AND a.txn != 3 AND a.txn != 6 AND a.txn != 5)";	
		}	 
		$prepare=$db->prepare($qry);

		$prepare->execute(array($col['val'],$col['val2'],$col['val3'],$col['orVal'],$col['orVal2'],$col['orVal2']));		
		$fetch = $prepare->fetchAll();
		return $fetch;
	}

	/* For can receive */
	public function getNoti3($db,$col){
		$qry = " SELECT * FROM tbl_notify a ";
		$qry.= " LEFT JOIN tbl_dr_hdr b ";						 
		$qry.= "	 ON a.id = b.dr_number ";
		$qry.= " LEFT JOIN tbl_lcseclocpa c ";						 
		$qry.= "	 ON c.lcstor = b.loc_code ";	
		$qry.= " LEFT JOIN tbl_transfer_hdr d ";						 
		$qry.= "	 ON d.trf_num = a.id ";				 
		$qry.= " WHERE (".$col['flag']." = ? AND ".$col['xFlag']." = ? AND  a.txn != 3 AND a.txn != 6) ";
		$qry.= "    OR ( ".$col['or']." = ? AND ".$col['or2']." = ? AND  a.txn != 3 AND a.txn != 6) ";
		$qry.= "    OR (".$col['or2']." = ? AND  a.txn != 3 AND a.txn != 6 )";
		$qry.= " 	OR ( ".$col['or3']." = ? AND ".$col['or4']." = ? AND  a.txn != 3 AND a.txn != 6)";
		$qry.= " AND c.lcuseid = ? ";				 
		$prepare=$db->prepare($qry);

		$prepare->execute(array($col['val'],$col['val2'],$col['or'],$col['orVal2'],$col['orVal2'],$_SESSION['uid'],$col['orVal3'],$col['orVal4']));		
		$fetch = $prepare->fetchAll();
		return $fetch;
	}
	/* For coordinator */
	public function getNoti4($db,$col){ /* remotve trf & pullout*/
		$qry = " SELECT * FROM tbl_notify a ";
		$qry.= " LEFT JOIN tbl_dr_hdr b ";						 
		$qry.= " ON a.id = b.dr_number ";
		$qry.= " WHERE ".$col['flag']." = ? AND ".$col['xFlag3']." = ?  AND a.txn != ? AND  a.txn != 3 AND a.txn != 6 ";				 
		$prepare=$db->prepare($qry);

		$prepare->execute(array($col['val'],$col['val4'],5));		
		$fetch = $prepare->fetchAll();
		$col = array();
		return $fetch;
	}

	/* For can receive  transfer receiving */
	public function getNoti5($db,$col){
		$qry = " SELECT * FROM tbl_notify a ";		
		$qry.= " LEFT JOIN tbl_transfer_hdr b ";						 
		$qry.= "	 ON a.id = b.trf_num ";					 
		$qry.= " WHERE  ".$col['tcol']." = ? AND ".$col['tcol2']." = ? ";
		$qry.="   	 OR (".$col['tcol']." = ? AND ".$col['tcol2']." = ? AND ".$col['tcol3']." = ? ) ";	
		//if( $this->isCoordinator() ){
			//$qry.=" AND b.toLoc IN (SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid = '".$_SESSION['uid']."')";
		//}	 		 
		$prepare=$db->prepare($qry);

		$prepare->execute(array($col['tVal'],$col['tVal2'],$col['tVal'],$col['tVal2'],$col['tVal3']));		
		$fetch = $prepare->fetchAll();
		$col = array();
		return $fetch;		
	}
	/*  pullout*/
	public function getNoti6($db,$col){
		$qry = " SELECT * FROM tbl_notify a ";		
		$qry.= " LEFT JOIN tbl_pullout_hdr b ";						 
		$qry.= "	 ON a.id = b.pullout_num ";					 
		$qry.= " WHERE  ( (".$col['pcol']." = ? OR ".$col['pcol']." = ?)  AND ".$col['pcol2']." = ? )";
		$qry.="   	 OR ( (".$col['pcol']." = ? OR ".$col['pcol']." = ?)  AND ".$col['pcol2']." = ?  AND ".$col['pcol3']." = ? ) ";			 		
		if( $this->isVendor() ){/*notification after picked up*/
			$qry.="  OR ( (".$col['pcol']." = 3 OR ".$col['pcol']." = 6) AND a.pUpd = 1 AND a.vVwd = 0 ) ";
		}
		$prepare=$db->prepare($qry);

		$prepare->execute(array(3,6,$col['pVal2'],3,6,$col['pVal2'],$col['pVal3']));		
		$fetch = $prepare->fetchAll();
		//$col = array();
		return $fetch;		
	}
	public function getNoti7($db,$col){
		$qry = " SELECT * FROM tbl_notify a ";		
		$qry.= " LEFT JOIN tbl_pullout_hdr b ";						 
		$qry.= "	 ON a.id = b.pullout_num ";					 
		$qry.= " WHERE  ( (".$col['pcol']." = ? OR ".$col['pcol']." = ?)  AND ".$col['pcol2']." = ?  AND ".$col['pcol3']." = ? )";		 
		$prepare=$db->prepare($qry);

		$prepare->execute(array(3,6,$col['pVal2'],$col['pVal3']));		
		$fetch = $prepare->fetchAll();
		//$col = array();
		return $fetch;		
	}
	public function orgArrays($result,$col){

		$myArr = array();
		$rows = array();
		//$result = array();
		$counter = array();

		$counter['flag'] = 0;
		$counter['flag2'] = 0;
		$counterT5['flag'] = 0;
		$counterT4['flag'] = 0;
		$counterT3['flag'] = 0;
		$counterT2['flag'] = 0;
		$counterT['flag']  = 0;
		$counter11['flag'] = 0;
		$counter10['flag'] = 0;
		$counter8['flag'] = 0;
		$counter9['flag'] = 0;
		$counter7['flag'] = 0;
		$counter6['flag'] = 0;
		$counter5['flag'] = 0;
		$counter4['flag'] = 0;
		$counter3['flag'] = 0;
		$counter2['flag2'] = 0;
		$counter2['flag'] = 0;
		$counter['flag22'] = 0;
		
		$rowss = ''; 
		$rowss2 = '';
		$row3 = '';
		$rowss3 = '';

		$rowssT2 = '';			 
		$rowssT3 = '';			
		$rowssT4 = '';			
		$rowssT5 = '';
		$row_merch = '';
		$row = '';	
		$rows2= '';	
		$rows3= '';	
		$rowss4= '';
		$rowss5= '';	
		$rowss6= '';	
		$rowss7= '';	
		$rowss8= '';	
		$rowss9= '';
		$rowssT= '';	
		$rowsst= '';
		$row2 = '';
	 	
		for($i = 0; $i < count($result);$i++){
			$rows['id'] = $result[$i]['id'];
			$rows['rcvr'] = $result[$i]['rcvr'];
			$rows['txn'] = $result[$i]['txn'];
			$rows['loc'] = $result[$i]['loc'];
			$rows['vUpd'] = $result[$i]['vUpd'];
			$rows['mUpd'] = $result[$i]['mUpd'];
			$rows['pUpd'] = $result[$i]['pUpd'];
			$rows['vVwd'] = $result[$i]['vVwd'];
			$rows['mVwd'] = $result[$i]['mVwd'];
			$rows['pVwd'] = $result[$i]['pVwd'];
			$rows['ctorVwd'] = $result[$i]['ctorVwd'];
			$rows['status'] = $result[$i]['status'];		
			
			
			/*-----STORE DELIVERY--------------*/
			if( $rows['txn'] == 4){
				$rows['vendor_code'] = $result[$i]['vendor_code'];
				/*val=vUpd , val2=mUpd , val3=pUpd , val4=ctorVwd*/
				if( $this->canApprove() ){ /*0,0,0,0*/
					if($rows['vUpd'] == 0 && $rows['mUpd'] == 0 &&	$rows['pUpd'] == 0 && $rows['ctorVwd'] == 0){
						$counter['flag'] += 1;
						//$rows['msg'] = ' You have '.$counter['flag']." delivery for Approval";
						$row = ' You have '.$counter['flag']." delivery for Approval::3";				
					}
					else if( $rows['vUpd'] == 1 && $rows['mUpd'] == 1 &&	$rows['pUpd'] == 0 ){
						$counter['flag22'] += 1;
						//$rows['msg'] = ' You have '.$counter['flag']." delivery for Approval";
						$row_merch = " ".$counter['flag22']." Ordered Status for receive::22";		
					}
					else if( /*$result[$i]['mUpd'] == 1 &&*/ $result[$i]['pUpd'] == 1 && $rows['mVwd'] == 0 ){  /* status = 1 receive */
						$counter['flag2'] += 1;
						$rowss = ' You have '.$counter['flag2']." dr received::2";		
					}
					else{
						//$row['msg'] = "";
					}
					
				}else if( $this->canReceive() ){ /*1,1,0,0*/
					$rows['lcuseid'] = $result[$i]['lcuseid'];
					if($rows['vUpd'] == 1 && $rows['pUpd'] == 0 && $rows['lcuseid'] == $_SESSION['uid']){
						$counter['flag'] += 1;
						$row  = ' You have '.$counter['flag']." Ordered Status::0";		
					}
					else if( $result[$i]['status'] == 1){  /* status = 1 receive */
						//$counter['flag2'] += 1;
						//$rowss = " ";		
					}else{
						//$row['msg'] = "";
					}
					
				}else if( $this->isVendor() ){ /*0,1,0,0*/
					if($rows['vendor_code'] != $_SESSION['uid'])
						continue;

					if($rows['vUpd'] == 0 && $rows['mUpd'] == 1 &&	$rows['pUpd'] == 0 && $rows['ctorVwd'] == 0){
						$counter['flag'] += 1;
						$row = $this->addSpaces('You have '.$counter['flag']." dr notification::1");		
					}
					else if( $rows['vUpd'] == 1 && $rows['pUpd'] == 1 && $rows['vVwd'] == 0 && $rows['vendor_code'] == $_SESSION['uid']){  /* status = 1 receive */
						$counter['flag2'] += 1;
						$rowss = $this->addSpaces('You have '.$counter['flag2']." dr received::8");		
					}
					else{
						//$row['msg'] = "";
					}
					 
				}else if( $this->isCoordinator() ){
					if($rows['pUpd'] == 1 && $rows['ctorVwd'] == 0 && $this->getMyStore2($_SESSION['uid']) == $result[$i]['loc']){
						$counter['flag'] += 1;
						$row = $this->addSpaces(''.$counter['flag']." dr received::0");		
					}
				}
			}/*----------ADJUSTEMENT---------------*/
			else if( $rows['txn'] == 1){
				if( $this->isVendor() ){
					if($rows['rcvr'] == $_SESSION['uid'] && $rows['pUpd'] == 1 &&	$rows['vUpd'] == 0 ){
						$counter2['flag'] += 1;				
						$row2 = ' You have '.$counter2['flag']." Adjustment for Approval::5";							 		
					}					 					 
				}
				else if( $this->canApprove() ){  
					if($rows['pUpd'] == 1 && $rows['vUpd'] == 1 &&	$rows['mVwd'] == 0 ){
						$counter2['flag'] += 1;
						//$rows['msg'] = ' You have '.$counter['flag']." delivery for Approval";
						$row2 = ''.$counter2['flag']." Adjustment Transaction::6";				
					}
					else if( /*$result[$i]['mUpd'] == 1 &&*/ $result[$i]['pUpd'] == 1 && $rows['mVwd'] == 0 ){  /* status = 1 receive */
						$counter2['flag2'] += 1; 
						$rowss2 = ' You have '.$counter2['flag2']." dr received::2";		
					}					 
				}
				else if( $this->isCoordinator() ){
					if($rows['vUpd'] == 1 && $rows['ctorVwd'] == 0){
						$counter2['flag'] += 1;
						$row2 = $this->addSpaces('   '.$counter2['flag']." Adjustment Transaction::7");		
					}
				}				

			}/* Transfer In */
			else if( $rows['txn'] == 5 ){
				 /* use $row the same above because qry uses txn ==5 specific will not loop above if statements*/
				$rows['toLoc'] = $result[$i]['toLoc'];
				//$rows['toLoc'] == ($this->getMyStore2($_SESSION['uid'])
				if( $this->canReceive() ){  
					//$rows['lcuseid'] = $result[$i]['lcuseid'];
					if($rows['pUpd'] == 0 && $rows['toLoc'] == ($this->getMyStore($_SESSION['uid'])) ){
						$counter3['flag'] += 1;
						$row3  = ' You have '.$counter3['flag']." Transfer For Receiving ::8";		
					}					 
				}else if( $this->isVendor() ){
					$rows['vendor_code'] = $result[$i]['vendor_num'];
					if($rows['vendor_code'] == $_SESSION['uid'] && $rows['pUpd'] == 1 && $rows['toLoc'] == ($this->getMyStore($_SESSION['uid'])) && $rows['vVwd'] == 0){
						$counter4['flag'] += 1;
						$rows3  = '  '.$counter4['flag']." Transfer Receiving Transaction::9";		
					}	
				}
				else if( $this->canApprove() ){
					if( $rows['pUpd'] == 1 && $rows['mVwd'] == 0){
						$counter5['flag'] += 1;
						$rowss3  = '  '.$counter5['flag']." Transfer Receiving Transaction::10";		
					}	
				}
				else if( $this->isCoordinator() ){
					if( $rows['pUpd'] == 1 && $rows['ctorVwd'] == 0 && $rows['toLoc'] == ($this->getMyStore2($_SESSION['uid'])) ){
						$counter6['flag'] += 1;
						$rowss4  = '  '.$counter6['flag']." Transfer Receiving Transaction::11";		
					}	
				}
			}
			/* 3 regular ; 6 total pullout */
			else if( $rows['txn'] == 3 ){
				if( $this->isVendor() ){
					$rows['vendor_code'] = $result[$i]['vendor_num'];
					if($rows['vendor_code'] != $_SESSION['uid'])
						continue;
					if( $rows['pUpd'] == 0 && $rows['vUpd'] == 0 ){
						$counter7['flag'] += 1;
						$rowss5  = '  You have  '.$counter7['flag']."  Regular Pull-Out Request for approval::12";		
					}					 
					if( $rows['pUpd'] == 1 && $rows['vUpd'] == 1 && $rows['vVwd'] == 0){
						$counter9['flag'] += 1;
						$rowss7  = '   '.$counter9['flag']."  Regular Pull-Out Transaction::14";		
					}
				}
				if( $this->canReceive() ){
					if( $rows['pUpd'] == 0 && $rows['vUpd'] == 1 && $rows['loc'] == ($this->getMyStore($_SESSION['uid'])) ){
						$counter8['flag'] += 1;
						$rowss6  =  '  You have  '.$counter8['flag']." Regular Pull-out Approved::13";		
					}
				}
				if( $this->canApprove() ){
					if( $rows['pUpd'] == 1 && $rows['mVwd'] == 0 ){
						$counter10['flag'] += 1;
						$rowss8  = ' '.$counter10['flag']."  Pull-out Transaction ::15";		
					}
				}
				if( $this->isCoordinator() ){
					if( $rows['pUpd'] == 1 && $rows['ctorVwd'] == 0 ){
						$counter11['flag'] += 1;
						$rowss9  = ' '.$counter11['flag']."  Pull-out Transaction ::16";		
					}
				}
			}
			else if( $rows['txn'] == 6  ){
				if( $this->isVendor() ){
					if( $rows['pUpd'] == 0 && $rows['vUpd'] == 0 ){
						$counterT['flag'] += 1;
						$rowssT  = '  You have  '.$counterT['flag']."  Total Pull-Out Request for approval::17";		
					}					 
					if( $rows['pUpd'] == 1 && $rows['vUpd'] == 1 && $rows['vVwd'] == 0){
						$counterT2['flag'] += 1;
						$rowssT2  = '   '.$counterT2['flag']."  Total Pull-Out Transaction::21";		
					}
				}
				if( $this->canReceive() ){
					if( $rows['pUpd'] == 0 && $rows['vUpd'] == 1 ){
						$counterT3['flag'] += 1;
						$rowssT3  = '  You have  '.$counterT3['flag']." Total Pull-out Approved::18";		
					}
				}
				if( $this->canApprove() ){
					if( $rows['pUpd'] == 1 && $rows['mVwd'] == 0 ){
						$counterT4['flag'] += 1;
						$rowssT4  = ' '.$counterT4['flag']."  Total Pull-out Transaction::19";		
					}
				}
				if( $this->isCoordinator() ){
					if( $rows['pUpd'] == 1 && $rows['ctorVwd'] == 0 ){
						$counterT5['flag'] += 1;
						$rowssT5  = ' '.$counterT5['flag']."  Total Pull-out Transaction::20";		
					}
				}
			}						
		}

		if( $row != "")			 
			array_push($myArr, $row);					
		if( $row2 != "")		 
			array_push($myArr, $row2);					
		if($rowss != '')
				array_push($myArr, $rowss);
		if($rowss2 != '')
			array_push($myArr, $rowss2);
		/*---Transfer-----------*/		 
		if($row3 != '')
			array_push($myArr, $row3);
		if($rows3 != '')
			array_push($myArr, $rows3);
		if($rowss3 != '')
			array_push($myArr, $rowss3);
		if($rowss4 != '')
			array_push($myArr, $rowss4);
		if($rowss5 != '')
			array_push($myArr, $rowss5);
		if($rowss6 != '')
			array_push($myArr, $rowss6);
		if($rowss7 != '')
			array_push($myArr, $rowss7);
		if($rowss8 != '')
			array_push($myArr, $rowss8);
		if($rowss9 != '')
			array_push($myArr, $rowss9);
		/*Total*/
		if($rowssT != '')
			array_push($myArr, $rowssT);
		if($rowssT2 != '')
			array_push($myArr, $rowssT2);
		if($rowssT3 != '')
			array_push($myArr, $rowssT3);
		if($rowssT4 != '')
			array_push($myArr, $rowssT4);
		if($rowssT5 != '')
			array_push($myArr, $rowssT5);
		if($row_merch != '')
			array_push($myArr, $row_merch);

		$result = array();
		return $myArr;
	}


	private function addSpaces($str){
		return ''.$str.'';
	}
	private function getMyStore($lcuseid){

		$qry = " SELECT lcstor FROM tbl_lcseclocpa ";
	 	$qry.= " WHERE lcuseid = ?";			
		$prepare = $this->db->prepare($qry);

		$prepare->execute(array($lcuseid));
		$fetch = $prepare->fetchAll();

		return $fetch[0][0];
	}
	private function getMyStore2($lcuseid){

		$qry = " SELECT lcstor FROM tbl_lcsecloc";
	 	$qry.= " WHERE lcuseid = ?";			
		$prepare = $this->db->prepare($qry);

		$prepare->execute(array($lcuseid));
		$fetch = $prepare->fetchAll();

		return $fetch[0][0];
	}
}							   
/*-------------------------------------------------------*/

?>