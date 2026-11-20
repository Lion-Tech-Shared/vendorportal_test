<?PHP
	//echo '<center><h1>Loading Data to CCN Portal.......</h1></center>';
	//exit;
	//require "../../class-main/connection.class.php";
	
	
	$connection = new myClass();
	$connection->_require();
	require "../../control/functions.php";
	include "../../MPDF57/mpdf.php";
	session_start();
	
	ini_set('memory_limit', '1026M');
	
	$conn = new getConnection();
	$db = $conn->PDO();

	/*check if user gid can access sales report*/
	$query=' SELECT * FROM tbl_user_group_menu_sub ';
	$query.=" WHERE group_id=? AND menu_id_sub=(SELECT menu_id_sub FROM tbl_app_menu_sub WHERE menu_dsc_sub=?)";	
	$prepare=$db->prepare($query);
	$prepare->execute(array($_SESSION['gid'],"Sales"));
	$count_fetch=$prepare->fetchAll();
	if(count($count_fetch)<1){
		echo '<strong>Forbidden to access sales report temporarily.</strong>';
		exit;
	}
	/*---------------end-----------------------*/
	if($_GET["sbu"] == '0'){
        echo '<strong>Invalid SBU!</strong>';  
		exit;
	}
	$sbu = strtoupper($_GET['sbu']);
    $tbl = ( $sbu == 'DS'?"":"Stage");
	$lvendr = $_GET["vcode"];
	if($lvendr == 6300122)
		$lvendr = 630012;
	
	$storeCode = explode(",", $_GET["store"]);
	$store = "";
	
	for($i = 0; $i < count($storeCode); $i ++) {
		$storeCodeCont = explode(" : ", $storeCode[$i]);		
		
		if($i > 0) {
			$store .= ",";
		}
				
		$store .= $storeCodeCont[0];
	}
	
	$fromS = $_GET["from"];
	$toS = $_GET["to"];
	
	$fromArr = explode("/", $fromS);
	$toArr = explode("/", $toS);
	
	$from = (substr($fromArr[2], 2, 2).$fromArr[0].$fromArr[1]);
	$to = (substr($toArr[2], 2, 2).$toArr[0].$toArr[1]);
	
	//get and group sales detail by location, ccn code, vendor code and contract
	//a.cslctp a.cslloc, a.cslcss, d.v_code, d.v_name, a.cslcct, a.cctdsc,
	 									
	$qry = "SELECT
				distinct a.cslloc, a.cslcss, d.v_code, d.v_name, a.cslcct, a.cctdsc 
			FROM
				tbl_ccn_sales_dtl a ";
	
	//pag vendor dae 
	if($_GET["isv"] == "true") {		
		if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) {
			//$check_query="SELECT * FROM tbl_lcsecloc ";
		//if($_SESSION["gid"]) {
			$qry .= "LEFT JOIN
					(select lcstor from tbl_lcsecloc".$tbl." where lcuseid = '".$_SESSION["uid"]."' AND lcstor <> '') b on a.cslloc = b.lcstor ";
		}
	}
				
	$qry .= "LEFT JOIN
				tbl_lcsucn".$tbl." c on a.cslcss = c.lcnvdr
			LEFT JOIN
				tbl_vendor_master".$tbl." d on c.lvendr = d.v_code
			LEFT JOIN
				tbl_store_master".$tbl." e on a.cslloc = e.strnum
			WHERE
				a.csldat BETWEEN ".$from." AND ".$to." ";
	
	if($_GET["sbu"]) {
		$qry .= "AND
					e.strsbu IN('".$_GET["sbu"]."','HG' )";
	}
	
	if($_GET["isv"] == "true") {
		if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) {
			//$qry .= "AND b.lcstor <> '' ";			
		}
		
		$qry .= "AND
					d.v_code = ".$lvendr." ";
	}
	else {
		$qry .= "AND
					a.cslloc = ".$lvendr."
				AND
					a.cslcct in (".trim($cslcct, " ").") ";
	}
	
	if($store) {
		$qry .= "AND a.cslloc in (".trim($store, " ").") ";
	}
	
	$qry .=	"ORDER BY
				a.cslloc, d.v_code, a.cslcss, a.cslcct ";
	
	$res = $db->prepare($qry);
	$res->execute();
	$cslloc = $res->fetchAll();
	
	/*for($i = 0; $i < count($cslloc); $i ++) {
				 echo $cslloc[$i]["cslctp"]."<br>";
				 echo $cslloc[$i]["cslloc"]."<Br>";
				 echo $cslloc[$i]["cslcss"]."<br>";
				 echo $cslloc[$i]["v_code"]."<br>";
				 echo $cslloc[$i]["v_name"]."<br>";
				 echo $cslloc[$i]["cslcct"]."<br>";
				 echo $cslloc[$i]["cctdsc"]."<br>";			 
	}
	echo "uls:".count($cslloc);
	exit;
	*/
	$qry_ctr=$db->prepare('SELECT * FROM tbl_cm');		
	$qry_ctr->execute();
	$qry_ctr_fetch=$qry_ctr->fetchAll();
	
	//totaled fraction-------------------------------------------------------------------------------------.
	if($_GET["format"] == "t"){
		$mpdf = new mPDF('', 'A4', 0, '', 10, 10, 18, 15, 9, 9);
		$mpdf->AddPage('L');
		
		$mpdf->showWatermarkText = true;
		$mpdf->watermarkText = "FOR VENDOR USE ONLY";
			
		$top = 	'<html>
					<head>
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<meta name="description" content="">
						<meta name="author" content="">
						<style>
							body {
								font-family: sans-serif;
								font-size: 8pt;
							}
							
							p {
								margin: 0pt;
							}
							
							td {
								vertical-align: top;
							}
							
							table thead th {
								text-align: center;
								border-color: #000000;
								border-style: dashed;
								border-width: 0.1mm;
								font-size: 7pt;
								font-weight: bold;
							}							
							table tfoot td {
								border: none;
								border-top-color: #000000;
								border-top-style: dashed;
								border-top-width: 0.1mm;
								font-weight: bold;
							}
							
							table td {
								font-size: 7pt;
							}
						</style>
					</head>
					<body>
						<!--mpdf
						<htmlpageheader name="myheader">
							<table width="100%">
								<tr>
									<td style="color:#0000BB;" colspan="4"><strong><tt>'.$cslloc[0]["cslcss"].' - '.$cslloc[0]["v_name"].'</tt></strong></td>
									<td width="80px" align="right"><strong><tt>'.date('m/d/Y').'</tt></strong></td>
									<td width="60px" align="right"><strong><tt>'.date('H:i:s').'</tt></strong></td>
								</tr>
								<tr>
									<td colspan="3" style="color:#0000BB;" colspan="4"><strong><tt>Period: '.date("F", strtotime($fromS)).' '.date("d", strtotime($fromS)).'-'.date("d", strtotime($toS)).', '.date("Y", strtotime($fromS)).'</tt></strong></td>
								</tr>
							</table>
						</htmlpageheader>

						<htmlpagefooter name="myfooter">
							<div style="font-size: 8pt; text-align: center; padding-top: 3mm; ">
								<tt>Page {PAGENO} of {nb}</tt>
							</div>
						</htmlpagefooter>

						<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
						<sethtmlpagefooter name="myfooter" value="on" />
						mpdf-->';
	
		if(count($cslloc) == 0) {
			$html =			'<br/>
							<div><center><tt>No sale detail.</tt></center></div>';
			
		}
		else {
				
			$html = 	'<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="7">
							<thead>
								<tr>
									<th width="50px"><tt>STORE CODE</tt></th>
									<th><tt>STORE NAME</tt></th>
									<th><tt>COMPANY</tt></th>
									<th width="80px"><tt>CONTRACT #</tt></th>
									<th><tt>CONTRACT DESC</tt></th>
									<th width="80px"><tt>Comm Rate</tt></th>
									<th width="80px"><tt>TOTAL QTY</tt></th>									 								    							 
									<th width="100px"><tt>TOTAL AMOUNT</tt></th>
								</tr>
							</thead>
							<tbody>';
							
			$tqty = 0;
			$tsls = 0;
			
			for($i = 0; $i < count($cslloc); $i ++) {
				$qry = "SELECT 
							strnum, strnam, glcnam
						FROM
							tbl_store_master".$tbl."
						WHERE
							strnum = ".$cslloc[$i]["cslloc"]."
						ORDER BY
							strnum
						ASC";
							
				$res = $db->prepare($qry);
				$res->execute();
				$str = $res->fetchAll();
				
				$qry = "SELECT 
							sum(cslqty) as qty,
							sum(csramt) as net
						FROM
							tbl_ccn_sales_dtl
						WHERE
							cslcss = ".$cslloc[$i]["cslcss"]."
						AND
							cslloc = ".$str[0]["strnum"]."
						AND
							cslcct = ".$cslloc[$i]["cslcct"]."
						AND
							csldat BETWEEN ".$from." AND ".$to." ";
							
				$res = $db->prepare($qry);
				$res->execute();
				$sales = $res->fetchAll();
				
				//my query
				  $qry ="SELECT distinct a.cslctp,b.ccprte FROM tbl_ccn_sales_dtl a left join tbl_cssctp".$tbl." b on a.cslctp=b.ccpcod ";
				  $qry.="WHERE cslcss=".$cslloc[$i]["cslcss"]." ";
				  $qry.=" AND csldat BETWEEN ".$from." AND ".$to." ";
				  $qry.=" AND cslloc=".$cslloc[$i]["cslloc"]." AND cslcct=".$cslloc[$i]["cslcct"]."  ORDER BY cslctp ASC";													
				  $res_com_rate = $db->prepare($qry);
				  $res_com_rate->execute();
				  $fetchs = $res_com_rate->fetchAll();						  				 				 
			    //end my query
							
			 
				for($ii = 0; $ii < count($fetchs); $ii ++) {
					  $get_sum='SELECT SUM(csramt) as totals,SUM(cslqty) as total_qty 
								FROM tbl_ccn_sales_dtl 
								WHERE cslctp="'.$fetchs[$ii]['cslctp'].'" AND cslcss="'.$cslloc[$i]['cslcss'].'"
								AND cslloc = '.$str[0]["strnum"].'
								AND cslcct = '.$cslloc[$i]["cslcct"].'
								AND	csldat BETWEEN '.$from.' AND '.$to.'';											
					  $prepare_qry=$db->prepare($get_sum);
					  $prepare_qry->execute();
					  $get_val=$prepare_qry->fetchAll();
					  
					 if($ii<1){
						$html.= 	'<tr class="det">
										<td><tt><center>'.$str[0]["strnum"].'</center></tt></td>
										<td><tt>'.$str[0]["strnam"].'</tt></td>
										<td><tt>'.$str[0]["glcnam"].'</tt></td>
										<td align="right"><tt><center>'.$cslloc[$i]["cslcct"].'</center></tt></td>
										<td><tt>'.$cslloc[$i]["cctdsc"].'</tt></td>
										<td align="center"><tt>'.number_format($fetchs[$ii]['ccprte'],0).'% </tt></td>
										<td align="right"><tt>'.number_format($get_val[0]["total_qty"], 2).'</tt></td>';	
												 
						$html.=		'<td align="right"><tt>'.number_format($get_val[0]["totals"], 2).'</tt></td>
									</tr>';
										//my query
										//total qty ->  <td align="right"><tt>'.number_format($sales[0]["qty"], 2).'</tt></td>';
										//total net-> <td align="right"><tt>'.number_format($sales[0]["net"], 2).'</tt></td>
										//$html.=		 '<tr ><td colspan="2" align="right">Comm. Rate :</td>';							 					 
									  //$html.= "<td align='left' width='120' style='border:1px solid;'>".number_format($fetchs[$ii]['ccprte'],0)." %-->".number_format($get_val[0]['totals'],2)."---".$get_val[0]['total_qty']."</td>";
					 }else{
						  $html.='<tr>
									  <Td></td><Td></td><Td></td><Td></td><Td></td>
									  <td align="center" ><tt>'.number_format($fetchs[$ii]['ccprte'],0).'%</tt></td>
									  <Td align="right"><Tt>'.number_format($get_val[0]["total_qty"], 2).'</tt></td>
									  <Td align="right"><tt>'.number_format($get_val[0]["totals"], 2).'</tt></td>
								  </tr>'; 
					 }
				} 								 				
							//my query	
						    //$html.=		'</tr>';		
				$tqty += $sales[0]["qty"];
				$tsls += $sales[0]["net"];
			}
			
			$html .= 		' </tbody>
								<tfoot>
									<tr class="gtot">
										<td colspan="5"><tt>TOTAL</tt></td>
										<td align="right"><tt></tt></td>
										<td align="right"><tt>'.number_format($tqty, 2).'</tt></td>										 										 																							
										<td align="right"><tt>'.number_format($tsls, 2).'</tt></td>
									</tr>
								</tfoot>
							</table>';
		}
	}
	//detailed-----------------------------------------------------------------------
	else {
		//per date per date
		if($_GET["per"] == "dt") {
			$mpdf = new mPDF('', 'A4', 0, '', 10, 10, 18, 15, 9, 9);
//			$mpdf = new mPDF('', 'A4', 0, '', 10, 10, 18, 15, 9, 9);
			//$mpdf->AddPage('L');
			
			$mpdf->showWatermarkText = true;
			$mpdf->watermarkText = "CCN Vendor Portal Generated";
			
			$top = 		'<html>
							<head>
								<meta charset="utf-8">
								<meta http-equiv="X-UA-Compatible" content="IE=edge">
								<meta name="viewport" content="width=device-width, initial-scale=1">
								<meta name="description" content="">
								<meta name="author" content="">
								<style>
									body {
										font-family: sans-serif;
										font-size: 8pt;
									}
									
									p {
										margin: 0pt;
									}
									
									td {
										vertical-align: top;
									}
									
									table thead th {
										text-align: center;
										border-color: #000000;
										border-style: dashed;
										border-width: 0.1mm;
										font-size: 7pt;
										font-weight: bold;
									}
									
									table tfoot td {
										border: none;
										border-top-color: #000000;
										border-top-style: dashed;
										border-top-width: 0.1mm;
										font-weight: bold;
									}
									
									table td {
										font-size: 7pt;
									}
								</style>
							</head>
							<body style="border:1px solid;">
								<!--mpdf
								<htmlpageheader name="myheader">
								 
									<table width="100%">										 										 
										<tr>
											<td style="color:#0000BB;" colspan="4"><strong><tt>CONCESSIONAIRE SUMMARY SALES REPORT</tt></strong></td>
											<td width="80px" align="right"><strong><tt>'.date('m/d/Y').'</tt></strong></td>
											<td width="60px" align="right"><strong><tt>'.date('H:i:s').'</tt></strong></td>
										</tr>
										<tr>
											<td colspan="3" style="color:#0000BB;" colspan="4"><strong><tt>Period: '.date("F", strtotime($fromS)).' '.date("d", strtotime($fromS)).'-'.date("d", strtotime($toS)).', '.date("Y", strtotime($fromS)).'</tt></strong></td>
										</tr>
									</table>
								</htmlpageheader>

								<htmlpagefooter name="myfooter">
									<div style="font-size: 8pt; text-align: center; padding-top: 3mm; ">
										<tt>Page {PAGENO} of {nb}</tt>
									</div>
								</htmlpagefooter>

								<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
								<sethtmlpagefooter name="myfooter" value="on" />
								mpdf-->';
			
			if(count($cslloc) == 0) {
				$html =			'<br/>
								<div><center><tt>No sale detail.</tt></center></div>';				
			}
			else {
				$html = '';
				
				$counter=0;
				for($i = 0; $i < count($cslloc); $i ++) {
				  $totQty=0;
				  $gqty=0;	
				  $tin='';
				  $qry ="SELECT distinct a.cslctp,b.ccprte FROM tbl_ccn_sales_dtl a left join tbl_cssctp".$tbl." b on a.cslctp=b.ccpcod ";
				  $qry.="WHERE cslcss=".$cslloc[$i]["cslcss"]." ";
				  $qry.=" AND csldat BETWEEN ".$from." AND ".$to." ";
				  $qry.=" AND cslloc=".$cslloc[$i]["cslloc"]." AND cslcct=".$cslloc[$i]["cslcct"]."";													
				  $res_com_rate = $db->prepare($qry);
				  $res_com_rate->execute();
				  $fetchs = $res_com_rate->fetchAll();		
				  $fetchs2 = $res_com_rate->fetchAll();		
				  
				  $qry ="SELECT tin FROM tbl_tin".$tbl."  WHERE store=?";																			
				  $pTin= $db->prepare($qry);
				  $pTin->execute(array($cslloc[$i]["cslloc"]));
				  $cTin = $pTin->fetchAll();		

				  if(count($cTin) < 1){
				  	$tin='0';
				  }else{
				  	$tin=$cTin[0][0];
				  }
				  $countComm = count($fetchs);
				  for($ii = 0; $ii < $countComm; $ii ++) {
					  	
						$fromL = $fromS;
						$toL = $toS;	
						$fromL2 = $fromS;
						$toL2 = $toS;	
						$qry = "SELECT 
								strnum, strnam, glcnam
							FROM
								tbl_store_master".$tbl."
							WHERE
								strnum = ".$cslloc[$i]["cslloc"]."
							ORDER BY
								strnum
							ASC";
								
						$res = $db->prepare($qry);
						$res->execute();
						$str = $res->fetchAll();
					/*----call myClass----*/
					$myFunct = new myClass();		
					if( $countComm == 1 ){		
						$my_total=$myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$ii]["cslctp"],$db);
						$mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$my_total['total'],$db);					
						$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");				
					}else if( $countComm > 1 ){	
						/* Process only once with multiple commission rate because this will loop according to num. of comm. rates */
						if($sbu == 'DS'){
							$my_total=$myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$ii]["cslctp"],$db);
							$mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$my_total['total'],$db);					
							$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");				
						}
						else{						
							if( $ii == 0 ){
								$myTotal = 0.00;
								for($myI = 0; $myI < $countComm; $myI++) {		
									$my_total = $myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$myI]["cslctp"],$db);						
									$myTotal +=  $my_total['total'];

									/*Process only if the last record of comm rate loop*/						
									if( $countComm == ($myI+1)) {
										$parot = '';
										$mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$myTotal,$db);						
									}	
									
								}	
							$html .='<table width="100%"><tr><td>'.$fetchs[$myI]["cslctp"].'</td></tr></table>';	
							}		
						}	
						$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");		
					}
					//$my_total=$myFunct->_getTotal();
					if($counter> 0) {
						$html .= '<pagebreak /><br />';
					}	
					if($fetchs[$ii]["cslctp"] != $cslloc[$i]['cslctp']){
						// 	$fromL = date ("m/d/Y", strtotime("+1 day", strtotime($fromL)));
						// 	continue;
						// }
					}	
					$counter++;						 
					$html .= 	'<table width="100%">
									<tr>
										<td width="80px"><tt>Company</tt></td>
										<td width="3px"><tt>:</tt></td>
										<td colspan="6"><strong><tt>'.$str[0]["glcnam"].'</tt></strong></td>
									</tr>
									<tr>
										<td><tt>Store</tt></td>
										<td><tt>:</tt></td>
										<td width="40px" align="right"><strong><tt>'.$str[0]["strnum"].'</tt></strong></td>
										<td width="3px"><tt>-</tt></td>
										<td><strong><tt>'.$str[0]["strnam"].'</tt></strong></td>
										<td width="80px" align="right"><tt>TIN</tt></td>
										<td width="3px"><tt>:</tt></td>
										<td width="150px"><strong>'.$tin.'</strong></td>
									</tr>
									<tr>
										<td><tt>Concessionaire</tt></td>
										<td><tt>:</tt></td>
										<td align="right"><strong><tt>'.$cslloc[$i]["cslcss"].'</tt></strong></td>
										<td><tt>-</tt></td>
										<td  ><strong><tt>'.$cslloc[$i]["v_name"].'</tt></strong></td>										
										<td width="80px"><tt>Contract Desc</tt></td>
										<td width="3px"><tt>:</tt></td>
										<td width="150px"><strong><tt>'.$cslloc[$i]["cctdsc"].'</tt></strong></td>
									</tr>
									<tr>
										<td><tt>Contract #</tt></td>
										<td><tt>:</tt></td>
										<td align="right"><strong>'.$cslloc[$i]["cslcct"].'</strong></td>
										<td colspan="2">-  '.number_format($fetchs[$ii]["ccprte"],0).'</td>';

									$html.='<td colspan="3" id="mySerial" class = "'.(count($fetchs) > 1 ? $cslloc[$i]["cslcct"]:"").'" >'.($mySerial[0]['serial'] ? "Serial #: <strong>".$mySerial[0]['serial']."</strong> " : "").'</td>';
									$html.='</tr>
									 
								</table>
								<br/>
								<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="7">
									<thead>
										<tr>
											<th width="11%" rowspan="2"><tt>DATE</tt></th>
											<th colspan="3"><tt>CASH SALES</tt></th>
											<th colspan="3"><tt>CREDIT SALES</tt></th>
											<th width="11%" rowspan="2"><tt>TOTAL QTY</tt></th>
											<th rowspan="2"><tt>TOTAL AMOUNT</tt></th>
										</tr>
										<tr>
											<th ><tt>REGULAR</tt></th>
											<th width="11%"><tt>MARKDOWN</tt></th>
											<th width="11%"><tt>TOTAL AMOUNT</tt></th>
											<th width="11%"><tt>REGULAR</tt></th>
											<th width="11%"><tt>MARKDOWN</tt></th>
											<th ><tt>TOTAL AMOUNT</tt></th>
										</tr>
									</thead>
									<tbody>';
					//$mpdf->watermarkText =($serial[0]["serial"] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");															
					//$mpdf->setWatermarkText =($serial[0]["serial"] ? " For counter" : "Not for counter");
					if($myFlag==0){
						$myFlag=($mySerial[0]['serial'] ? "0" :"1");
					}else{
						$myFlag=1;
					}

					$gqty = 0;
						
					$gCashRsls = 0;
					$gCashMsls = 0;
					$gCash = 0;
					
					$gCredRsls = 0;
					$gCredMsls = 0;
					$gCred = 0;
					
					$gTotsls = 0;
					$cash_total=0;
					$credit_total=0;
					while (strtotime($fromL) <= strtotime($toL)) {
					    // if($fetchs[$ii]["cslctp"] != $cslloc[$i]['cslctp']){
						// 	$fromL = date ("m/d/Y", strtotime("+1 day", strtotime($fromL)));
						// 	continue;
						// }
						$dtArr = explode("/", $fromL);
						$dt = (substr($dtArr[2], 2, 2).$dtArr[0].$dtArr[1]);
						$totQty = 0;
						$cash_total=0;
						$credit_total=0;
						
						$qry = "SELECT 
									csldat, sum(cslqty) as qty,	sum(csramt - cscamt) as sls
								FROM
									tbl_ccn_sales_dtl
								WHERE
									cslcss = ".$cslloc[$i]["cslcss"]."
								AND
									cslloc = ".$str[0]["strnum"]."
								AND
									cslcct = ".$cslloc[$i]["cslcct"]."
								AND
									csmflg = 'R'
								AND
									csldat = ".$dt."
								and 
									cslctp = ".$fetchs[$ii]['cslctp']."  
								GROUP BY
									csldat";
									
						$res = $db->prepare($qry);
						$res->execute();
						$salesCashR = $res->fetchAll();
						
						$totQty += $salesCashR[0]["qty"];
						//$flag += $totQty;
						$cash_total +=$salesCashR[0]["qty"];
					
					
						$qry = "SELECT 
									csldat, sum(cslqty) as qty,	sum(csramt - cscamt) as sls
								FROM
									tbl_ccn_sales_dtl
								WHERE
									cslcss = ".$cslloc[$i]["cslcss"]."
								AND
									cslloc = ".$str[0]["strnum"]."
								AND
									cslcct = ".$cslloc[$i]["cslcct"]."
								AND
									csmflg = 'M'
								AND
									csldat = ".$dt."
								and 
									cslctp = ".$fetchs[$ii]['cslctp']."   						 
									
								GROUP BY
									csldat";
									
						$res = $db->prepare($qry);
						$res->execute();
						$salesCashM = $res->fetchAll();
						
						$totQty += $salesCashM[0]["qty"];
						$cash_total += $salesCashM[0]["qty"];
						
						$qry = "SELECT 
									csldat, sum(cslqty) as qty,	sum(cscamt) as sls
								FROM
									tbl_ccn_sales_dtl
								WHERE
									cslcss = ".$cslloc[$i]["cslcss"]."
								AND
									cslloc = ".$str[0]["strnum"]."
								AND
									cslcct = ".$cslloc[$i]["cslcct"]."
								AND
									csmflg = 'R'
								AND
									csldat = ".$dt."
								and 
									cslctp = ".$fetchs[$ii]['cslctp']."   						 
								GROUP BY
									csldat";
									
						$res = $db->prepare($qry);
						$res->execute();
						$salesCredR = $res->fetchAll();
						
						
						$totQty += $salesCredR[0]["qty"];
						$credit_total +=$salesCredR[0]["qty"];
						$qry = "SELECT 
									csldat, sum(cslqty) as qty,	sum(cscamt) as sls
								FROM
									tbl_ccn_sales_dtl
								WHERE
									cslcss = ".$cslloc[$i]["cslcss"]."
								AND
									cslloc = ".$str[0]["strnum"]."
								AND
									cslcct = ".$cslloc[$i]["cslcct"]."
								AND
									csmflg = 'M'
								AND
									csldat = ".$dt."
								and 
									cslctp = ".$fetchs[$ii]['cslctp']."   
							 									
								GROUP BY
									csldat";
									
						$res = $db->prepare($qry);
						$res->execute();
						$salesCredM = $res->fetchAll();
						
						$totQty += $salesCredM[0]["qty"];
						$credit_total += $salesCredM[0]["qty"];
						if($cash_total>0){
							$totQty=$cash_total;
						}else{
							$totQty=$credit_total; 
						}
						$html .=		'<tr class="det">
											<td align="center"><tt>'.$fromL.'</tt></td>
											<td align="right">'.number_format($salesCashR[0]["sls"], 2).'</td>
											<td align="right">'.number_format($salesCashM[0]["sls"], 2).'</td>
											<td align="right">'.number_format(($salesCashR[0]["sls"] + $salesCashM[0]["sls"]), 2).'</td>
											<td align="right">'.number_format($salesCredR[0]["sls"], 2).'</td>
											<td align="right">'.number_format($salesCredM[0]["sls"], 2).'</td>
											<td align="right">'.number_format(($salesCredR[0]["sls"] + $salesCredM[0]["sls"]), 2).'</td>
											<td align="right">'.number_format($totQty, 2).'</td>
											<td align="right">'.number_format(($salesCashR[0]["sls"] + $salesCashM[0]["sls"] + $salesCredR[0]["sls"] + $salesCredM[0]["sls"]), 2).'</td>
										</tr>';
						
						$fromL = date ("m/d/Y", strtotime("+1 day", strtotime($fromL)));
						
						$gqty += $totQty;
						
						$gCashRsls += $salesCashR[0]["sls"];
						$gCashMsls += $salesCashM[0]["sls"];
						$gCash += ($salesCashR[0]["sls"] + $salesCashM[0]["sls"]);
						
						$gCredRsls += $salesCredR[0]["sls"];
						$gCredMsls += $salesCredM[0]["sls"];
						$gCred += ($salesCredR[0]["sls"] + $salesCredM[0]["sls"]);
						
						$gTotsls += ($salesCashR[0]["sls"] + $salesCashM[0]["sls"] + $salesCredR[0]["sls"] + $salesCredM[0]["sls"]);
						
					} /* End of while loop */
					
					$html .= 		'</tbody>
									<tfoot>
										<tr class="gtot">
											<td><center><tt>TOTAL</tt><center></td>
											<td align="right">'.number_format($gCashRsls, 2).'</td>
											<td align="right">'.number_format($gCashMsls, 2).'</td>
											<td align="right">'.number_format($gCash, 2).'</td>
											<td align="right">'.number_format($gCredRsls, 2).'</td>
											<td align="right">'.number_format($gCredMsls, 2).'</td>
											<td align="right">'.number_format($gCred, 2).'</td>
											<td align="right">'.number_format($gqty, 2).'</td>
											<td align="right">'.number_format($gTotsls, 2).'</td>
										</tr>
									</tfoot>
								</table>';	
								//$html .= '<pagebreak /><br />';				    
					 		
				}/* End For loop*/
			  }
			}
		}
		else {
			//per sku per sku per sku per sku per sku per sku per sku per sku per sku.
			$mpdf = new mPDF('', 'A4-L', 0, '', 10, 10, 18, 15, 9, 9);
			
			$mpdf->showWatermarkText = true;
			//$mpdf->watermarkText = "CCN Vendor Portal Generated";
			
			$top = 	'<html>
						<head>
							<meta charset="utf-8">
							<meta http-equiv="X-UA-Compatible" content="IE=edge">
							<meta name="viewport" content="width=device-width, initial-scale=1">
							<meta name="description" content="">
							<meta name="author" content="">
							<style>
								body {
									font-family: sans-serif;
									font-size: 8pt;
								}
								
								p {
									margin: 0pt;
								}
								
								td {
									vertical-align: top;
								}
								
								table thead th {
									text-align: center;
									border-color: #000000;
									border-style: dashed;
									border-width: 0.1mm;
									font-size: 7pt;
									font-weight: bold;
								}
								
								table tfoot td {
									border: none;
									border-top-color: #000000;
									border-top-style: dashed;
									border-top-width: 0.1mm;
									font-weight: bold;
								}
								
								table td {
									font-size: 7pt;
								}
								
								table td.sub {
									border: none;
									border-top-color: #000000;
									border-top-style: dashed;
									border-top-width: 0.1mm;
									font-weight: bold;
								}
							</style>
						</head>
						<body>
							<!--mpdf
							<htmlpageheader name="myheader">
								<table width="100%">
									<tr>
										<td style="color:#0000BB;" colspan="4"><strong><tt>CONCESSIONAIRE SUMMARY SALES REPORT</tt></strong></td>
										<td width="80px" align="right"><strong><tt>'.date('m/d/Y').'</tt></strong></td>
										<td width="60px" align="right"><strong><tt>'.date('H:i:s').'</tt></strong></td>
									</tr>
									<tr>
										<td colspan="3" style="color:#0000BB;" colspan="4"><strong><tt>Period: '.date("F", strtotime($fromS)).' '.date("d", strtotime($fromS)).'-'.date("d", strtotime($toS)).', '.date("Y", strtotime($fromS)).'</tt></strong></td>
									</tr>
								</table>
							</htmlpageheader>

							<htmlpagefooter name="myfooter">
								<div style="font-size: 8pt; text-align: center; padding-top: 3mm; ">
									<tt>Page {PAGENO} of {nb}</tt>
								</div>
							</htmlpagefooter>

							<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
							<sethtmlpagefooter name="myfooter" value="on" />
							mpdf-->';
			
			if(count($cslloc) == 0) {
				$html =		'<br/>
							<div><center><tt>No sale detail.</tt></center></div>';
			}
			else {
				$html = '';
				
				$counter=0;
				for($i = 0; $i < count($cslloc); $i ++) {
					$tin='';
					$qry2 ="SELECT distinct a.cslctp,b.ccprte FROM tbl_ccn_sales_dtl a left join tbl_cssctp".$tbl." b on a.cslctp=b.ccpcod  ";
					$qry2.="WHERE a.cslcss=".$cslloc[$i]["cslcss"]."  ";
					$qry2.=" AND a.csldat BETWEEN ".$from." AND ".$to."  ";
					$qry2.=" AND a.cslloc=".$cslloc[$i]["cslloc"]." AND a.cslcct=".$cslloc[$i]["cslcct"]."";													
					$res_com_rate = $db->prepare($qry2);
					$res_com_rate->execute();
					$fetchs = $res_com_rate->fetchAll();	
					
					$qry ="SELECT tin FROM tbl_tin".$tbl."  WHERE store=?";																			
					$pTin= $db->prepare($qry);
					$pTin->execute(array($cslloc[$i]["cslloc"]));
					$cTin = $pTin->fetchAll();		

					if(count($cTin) < 1){
					  $tin='0';
					}else{
					  $tin=$cTin[0][0];
					} 
					$countComm = count($fetchs) ;
					for($ii = 0;$ii < count($fetchs); $ii++){		
					//get serial number //tbl_ccn_sales_srl tbl_ccn_sales_srl tbl_ccn_sales_srl					 
						$fromL2 = $fromS;
						$toL2 = $toS;		
						$qry = "SELECT 
									strnum, strnam, glcnam
								FROM
									tbl_store_master".$tbl."
								WHERE
									strnum = ".$cslloc[$i]["cslloc"]."
								ORDER BY
									strnum
								ASC";								
						$res = $db->prepare($qry);
						$res->execute();
						$str = $res->fetchAll();
					$myFunct=new myClass();			
					if( $countComm == 1 ){
						
						$my_total=$myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$ii]["cslctp"],$db);
						$mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$my_total['total'],$db);					
						$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");					
						//$mpdf->watermarkText =($serial[0]["serial"] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");				
					}else if( $countComm > 1 ){						
						if($sbu == 'DS'){
							$my_total=$myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$ii]["cslctp"],$db);
							$mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$my_total['total'],$db);					
							$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");				
						}
						else{	
							/* Process only once with multiple commission rate because this will loop according to num. of comm. rates */
							if( $ii == 0 ){
								$myTotal = 0.00;
								for($myI = 0; $myI < $countComm; $myI++) {		
									$my_total = $myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$myI]["cslctp"],$db);						
									$myTotal +=  $my_total['total'];
									/*Process only if the last record of comm rate loop*/	
									$parot = 'parot';
									if( $countComm == ($myI+1)) {
										$parot = '';
										$mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$myTotal,$db);						
									}	
								}	
							}		
							$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");		
						}
					}
					 
					if($counter > 0) {
						$html .= '<pagebreak /><br />';
					}
					$counter++;	
					$html .= 	'<table width="100%" border="0">
									<tr>
										<td width="80px"><tt>Company</tt></td>
										<td width="3px"><tt>:</tt></td>
										<td colspan="10"><strong><tt>'.$str[0]["glcnam"].'</tt></strong></td>
									</tr>
									<tr>
										<td><tt>Store</tt></td>
										<td><tt>:</tt></td>

										<td width="30" align="left"><strong>'.$str[0]["strnum"].'</strong></td>
										 
										<td width="200" align="left"><strong>-  '.$str[0]["strnam"].'</strong></td>';
										
										$htmls='<td width="100"><tt>Comm Rate:'.number_format($fetchs[$ii]["ccprte"],0).' %</tt></td>';
										$html.='<td width="150"><tt></tt></td>';
										$html.='<td width="80px" align="right"><tt> </tt></td>';
										$html.='<td width="5">TIN : '.$tin.'</td>'; 
									 
									
									$html.='</tr>
									<tr>
										<td  width="60px"><tt>Supplier</tt></td>
										<td width="3"><tt>:</tt></td>
										<td width="10" align="left"><strong>'.$cslloc[$i]["cslcss"].'</strong></td>
										<td colspan="3"><tt>- <strong>'.$cslloc[$i]["v_name"].'</tt></strong></td>
										<td width="5" align="left"><tt>Contract Desc  : '.$cslloc[$i]["cctdsc"].'</tt></td>
										 
										<!--<td colspan="1"><strong><tt>'.$cslloc[$i]["cctdsc"].'</tt></strong></td>-->
									</tr>
									<tr>
										<td><tt>Contract #</tt></td>
										<td><tt>:</tt></td>
										<td align="right"><strong>'.$cslloc[$i]["cslcct"].'</strong></td>
										<td width="150" align="left">-  '.number_format($fetchs[$ii]["ccprte"],0).'</td>
										<td width="350px"><tt></tt></td>										
										<td><strong><tt></tt></strong></td>';
										$html.='<td width="310" id="mySerial">'.($mySerial[0]['serial'] ? "Serial #: <strong>".$mySerial[0]['serial']."</strong> " : "").'</td>';
									$html.='</tr>
								</table>
								<br/>
								<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="7">
									<thead>
										<tr>
											<th><tt>SKU</tt></th>
											<th><tt>DESCRIPTION</tt></th>
											<th><tt>CASH SALES</tt></th>
											<th><tt>CREDIT SALES</tt></th>
											<th><tt>QTY</tt></th>
											<th><tt>TOTAL AMOUNT</tt></th>
										</tr>
									</thead>
									<tbody>';					

					//$mpdf->watermarkText =($serial[0]["serial"] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");						
					if($myFlag==0){
						$myFlag=($mySerial[0]['serial'] ? "0" :"1");
					}else{
						$myFlag=1;
					}

					$gcssls = 0;
					$gcrsls = 0;
					$gqty = 0;
					$gsls = 0;
					//tbl_ccn_sales_dtl tbl_ccn_sales_dtl
					$qry = "SELECT 
								cslsku, idescr, sum(cslqty) as qty, sum(csramt) as net
							FROM
								tbl_ccn_sales_dtl
							WHERE
								cslcss = ".$cslloc[$i]["cslcss"]."
							AND
								cslloc = ".$cslloc[$i]["cslloc"]."
							AND
								cslcct = ".$cslloc[$i]["cslcct"]."
							AND 
								cslctp=".$fetchs[$ii]["cslctp"]." 
							AND
								csldat BETWEEN ".$from." AND ".$to." 
							GROUP BY
								cslsku
							ORDER BY
								cslsku";
								
					$res = $db->prepare($qry);
					$res->execute();
					$sales = $res->fetchAll();
					
					for($j = 0; $j < count($sales); $j ++) {
						//tbl_ccn_sales_dtl------------------------------------------
						
						$qry = "SELECT 
									cslsku,	sum(csramt - cscamt) as sls
								FROM
									tbl_ccn_sales_dtl
								WHERE
									cslcss = ".$cslloc[$i]["cslcss"]."
								AND
									cslloc = ".$str[0]["strnum"]."
								AND
									cslcct = ".$cslloc[$i]["cslcct"]."
								AND
									cslsku = ".$sales[$j]["cslsku"]."
								AND 
									cslctp=".$fetchs[$ii]["cslctp"]."  
								AND
									csldat BETWEEN ".$from." AND ".$to."
								GROUP BY
									cslsku";
									
						$res = $db->prepare($qry);
						$res->execute();
						$salesCash = $res->fetchAll();
						
						
						//tbl_ccn_sales_dtl
						$qry = "SELECT 
									cslsku,	sum(cscamt) as sls
								FROM
									tbl_ccn_sales_dtl
								WHERE
									cslcss = ".$cslloc[$i]["cslcss"]."
								AND
									cslloc = ".$str[0]["strnum"]."
								AND
									cslcct = ".$cslloc[$i]["cslcct"]."
								AND
									cslsku = ".$sales[$j]["cslsku"]."
								AND 
									cslctp=".$fetchs[$ii]["cslctp"]."  
								AND	
									csldat BETWEEN ".$from." AND ".$to."
								GROUP BY
									cslsku";
									
						$res = $db->prepare($qry);
						$res->execute();
						$salesCred = $res->fetchAll();
						//echo $sales[$j]["cslsku"];
						//exit;
						//echo count($salesCred);
					//exit;
					
						$html .=		'<tr class="det">
											<td width="100px" align="center"><tt>'.$sales[$j]["cslsku"].'</tt></td>
											<td><tt>'.$sales[$j]["idescr"].'</tt></td>
											<td width="200px" align="right">'.number_format($salesCash[0]["sls"], 2).'</td>
											<td width="150px" align="right">'.number_format($salesCred[0]["sls"], 2).'</td>
											<td width="100px" align="right">'.number_format($sales[$j]["qty"], 2).'</td>
											<td width="100px" align="right">'.number_format($sales[$j]["net"], 2).'</td>
										</tr>';
						
						$gcssls += $salesCash[0]["sls"];
						$gcrsls += $salesCred[0]["sls"];
						$gqty += $sales[$j]["qty"];
						$gsls += $sales[$j]["net"];
					}
					//$counter++;
				 
					
					$html .= 		'</tbody>
									<tfoot>
										<tr class="gtot">
											<td><strong><tt>GRAND TOTAL</tt></strong></td>
											<td class="rtdpad" align="right"><tt></tt></td>
											<td class="rtdpad" align="right">'.number_format($gcssls, 2).'</td>
											<td class="rtdpad" align="right">'.number_format($gcrsls, 2).'</td>
											<td class="rtdpad" align="right">'.number_format($gqty, 2).'<tt></tt></td>
											<td class="rtdpad" align="right">'.number_format($gsls, 2).'<tt></tt></td>
										</tr>
									</tfoot>
								</table>';
				  }			
				}
			}
		}
	}
	if($myFlag==1){
		$html.="<style>#mySerial{visibility:hidden;color:White;}</style>";
		$mpdf->watermarkText ="Not For Counter";
	}
	
	$bottom = 			'</body>
					</html>';
					
	//echo $top."<br/>".$html."<br/>".$bottom;
	//$html.=" <Br>ulysses:".$cslcct;
	//$mpdf->SetProtection(array('copy'));
	$mpdf->WriteHTML($top.utf8_encode($html).$bottom);
	
	$mpdf->Output();
	exit; 
   

	/*-------------------------------------------------------------------------------*/ 
    /*-----------------------------CLass myClass-------------------------------------*/
	/*-------------------------------------------------------------------------------*/

	class myClass{
		//public $gTotsls; 

		public function __construct(){
							
			
		}
		public function _insert($myid,$vendor_num,$db){
			//$db=$this->getConnection();	
			$qry="SELECT * FROM tbl_adjustment WHERE id='".$myid."'";
			$prepare=$db->prepare($qry);
			$prepare->execute();
			$fetch=$prepare->fetchAll();

				if(count($fetch)<1){
				//$date=NOW();;				
				//$qry.='SELECT DISTINCT C.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM TBL_LCSUCN A LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS LEFT JOIN TBL_STORE_MASTER D ON c.cctloc=D.STrnum WHERE A.LVENDR='.$vendor_num.' ';							 
					//$funct->_insert($id,$vendor_num,$db);			
					$qry=" INSERT INTO tbl_adjustment (id,userid,viewDate,is_approved,remarks) ";
					$qry.=" VALUES ('".$myid."','".$vendor_num."',NOW(),0,' ') ";
					$prepare=$db->prepare($qry);
					$prepare->execute();
					echo 1; 
				} 	
		}
		public function _serialing(){
				
		}
		private function getConnection(){

			return $this->conn;
		}
		private function _return(){

			return $this->$db;
		}

		public function _getTotal(){
			//return $this->$gTotsls;
		}

		public function _require(){
			require "../../class-main/connection.class.php";        
		}

		public function _getSerial($tbl,$cnv,$loc,$cnn,$from,$to,$my_total,$db){
			$qry_srl = "SELECT 
							serial, srlamt
						FROM
							tbl_ccn_sales_srl".$tbl."  
						WHERE
							srlcnv = ".$cnv."
						AND
							srlnum = ".$loc."
						AND
							srlcnn = ".$cnn."
						AND
							srlfmd = ".$from."
						AND
							srltod = ".$to."
						AND 
							srlamt = ".$my_total."
						ORDER BY
							serial DESC
						limit 1 ";			
			//$serial = array();					
			$res_srl = $db->prepare($qry_srl);
			$res_srl->execute();
			$serial = $res_srl->fetchAll();		
			return $serial;
		}
		public function _serialAmt($froml2,$tol2,$dtArr,$cslcss,$strnum,$cslcct,$cslctp,$db){
			//for serial labeling---------------------------------------------------------------{	
				//return $tol2;
				$gqty = 0;
					
				$gCashRsls = 0;
				$gCashMsls = 0;
				$gCash = 0;
				
				$gCredRsls = 0;
				$gCredMsls = 0;
				$gCred = 0;
				$gTotsls = 0;
				$cash_total=0;
				$credit_total=0;
				$x=0;
				while (strtotime($froml2) <= strtotime($tol2)) {
			
					$dtArr = explode("/", $froml2);
					$dt = (substr($dtArr[2], 2, 2).$dtArr[0].$dtArr[1]);
					$totQty = 0;
					$cash_total=0;
					$credit_total=0;
					
					$qry = "SELECT 
								csldat, sum(cslqty) as qty,	sum(csramt - cscamt) as sls
							FROM
								tbl_ccn_sales_dtl
				  			WHERE
								cslcss = ".$cslcss."
							AND
								cslloc = ".$strnum."
							AND
								cslcct = ".$cslcct."
							AND
								csmflg = 'R'
							AND
								csldat = ".$dt."
							AND 
							 	cslctp=".$cslctp."	
								
							GROUP BY
								csldat";
								
					$res = $db->prepare($qry);
					$res->execute();
					$salesCashR = $res->fetchAll();
					
					$totQty += $salesCashR[0]["qty"];
					//$flag += $totQty;
					$cash_total +=$salesCashR[0]["qty"];
									
					$qry = "SELECT 
								csldat, sum(cslqty) as qty,	sum(csramt - cscamt) as sls
							FROM
								tbl_ccn_sales_dtl
							WHERE
								cslcss = ".$cslcss."
							AND
								cslloc = ".$strnum."
							AND
								cslcct = ".$cslcct."
							AND
								csmflg = 'M'
							AND
								csldat = ".$dt."
							AND 
								cslctp=".$cslctp."								 
								
							GROUP BY
								csldat";
								
					$res = $db->prepare($qry);
					$res->execute();
					$salesCashM = $res->fetchAll();
					
					$totQty += $salesCashM[0]["qty"];
					$cash_total += $salesCashM[0]["qty"];
					 
					$qry = "SELECT 
								csldat, sum(cslqty) as qty,	sum(cscamt) as sls
							FROM
								tbl_ccn_sales_dtl
							WHERE
								cslcss = ".$cslcss."
							AND
								cslloc = ".$strnum."
							AND
								cslcct = ".$cslcct."
							AND
								csmflg = 'R'
							AND
								csldat = ".$dt."
							AND 
								cslctp=".$cslctp."								 
								
							GROUP BY
								csldat";
								
					$res = $db->prepare($qry);
					$res->execute();
					$salesCredR = $res->fetchAll();
										
					$totQty += $salesCredR[0]["qty"];
					$credit_total +=$salesCredR[0]["qty"];
					$qry = "SELECT 
								csldat, sum(cslqty) as qty,	sum(cscamt) as sls
							FROM
								tbl_ccn_sales_dtl
							WHERE
								cslcss = ".$cslcss."
							AND
								cslloc = ".$strnum."
							AND
								cslcct = ".$cslcct."
							AND
								csmflg = 'M'
							AND
								csldat = ".$dt."
							AND 
								cslctp=".$cslctp."								 											
							GROUP BY
								csldat";
								
					$res = $db->prepare($qry);
					$res->execute();
					$salesCredM = $res->fetchAll();
					
					$totQty += $salesCredM[0]["qty"];
					$credit_total += $salesCredM[0]["qty"];
					if($cash_total>0){
						$totQty=$cash_total;
					}else{
						$totQty=$credit_total; 
					}
				 	$froml2 = date ("m/d/Y", strtotime("+1 day", strtotime($froml2)));
					$gqty += $totQty;					 
					$gTotsls += ($salesCashR[0]["sls"] + $salesCashM[0]["sls"] + $salesCredR[0]["sls"] + $salesCredM[0]["sls"]);					
					$x++; 
				}
				//$gTotsls+=1;
				return array('total'=>$gTotsls);	
			//for serial labeling --------------------------------------------------------------}								
		}

	}
?>