<?php
require "../../class-main/connection.class.php";
require "../../control/functions.php";
include "../../MPDF57/mpdf.php";
session_start();
ini_set('memory_limit', '1026M');

/* redirect to home.php if dr not set*/
if(!isset($_GET['adj'])){
	header("Location:../home.php");
}
$adj=$_GET['adj'];

$mpdf = new mPDF('', 'A4', 0, '', 10, 10, 18, 15, 9, 9);
$mpdf->AddPage('L');

$mpdf->showWatermarkText = true;
$mpdf->watermarkText = " ";

//header("Content-Disposition: attachment ; filename='".getdate()."'.pdt");
$top = 	'<html>
<head>
	<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<style>
			body {
				font-family: serif;
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
				border-style: solid;
				border-width: 0.1mm;
				font-size: 8pt;
				font-weight: bold;
			}
			
			table tfoot td {
				border: none;
				border-top-color: #000000;
				border-top-style: solid;
				border-top-width: 0.1mm;
				font-weight: bold;
				font-size: 8pt;
				font-family: serif;
			}
			
			table td {
				font-size: 8pt;
			}
			.headers{ font-family: serif;}
		</style>
						 
</head>';


	$conn = new getConnection();
	$db = $conn->PDO();
	$arr=array();

	/* Check if can Receive */
	$qryCanRec=" SELECT canApprove,canReceive FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qryCanRec);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();	
 	
 	$qry="  SELECT a.*,b.ee_name,a.store,c.is_approved,coalesce(f.descr,'') as descr, ";
 	$qry.="        coalesce(e.cost,0) as cost,g.type,a.vendor,h.v_name,a.requestDate,a.ref_num ";
 	$qry.="		,i.strnam as fromDesc";
	$qry.=" FROM  tbl_requestadj a ";
	$qry.=" LEFT JOIN tbl_invmst f ON f.sku=a.sku "; 
	$qry.=" LEFT JOIN tbl_sku_cost e ON e.sku=a.sku "; 
	$qry.=" LEFT JOIN tbl_user_info b ON a.userid=b.user_id ";
	$qry.=" LEFT JOIN tbl_user_account d ON d.user_id=b.user_id ";
	$qry.=" LEFT JOIN tbl_adjustment c ON a.id=c.id "; 
	$qry.=" LEFT JOIN tbl_type_adj g ON a.adjType=g.id "; 		
	$qry.="	LEFT JOIN tbl_vendor_master h ON h.v_code=a.vendor";
	$qry.="	LEFT JOIN tbl_store_master i ON i.strnum=a.store";
	$qry.=" WHERE a.id=?";
	$qry.=' ';
	//$qry.='SELECT DISTINCT c.cctloc AS STORE,d.STRNAM AS STORE_NAME FROM TBL_LCSUCN A LEFT JOIN TBL_CSSMST B ON a.LCNVDR=b.CCMNUM LEFT JOIN TBL_CSSMCT C ON b.CCMNUM=c.CCTCSS LEFT JOIN TBL_STORE_MASTER D ON c.cctloc=d.STrnum WHERE a.LVENDR='.$vendor_num.' ';
	$qry.='  ORDER BY a.requestDate ASC ';

	$prepare=$db->prepare($qry);
	$prepare->execute(array($adj));	
	$row=$prepare->fetchAll();

	$qry="SELECT coalesce(ee_name,'') as name FROM tbl_user_info WHERE user_id=?";
	$prepare2=$db->prepare($qry);
	$prepare2->execute(array($row[0]['userid']));	
	$row2=$prepare2->fetchAll();

	$totalAmt=0;
	$totalQty=0;
	$totalRecQty=0;
	$nbsp='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$top.='<body style="border:1px solid;">
	<!--mpdf
	<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td style="color:#0000BB;" colspan="4"><strong></strong></td>
				<td width="80px" align="right"><strong>'.date('m/d/Y').'</strong></td>
				<td width="50px" align="right"><strong>'.date('H:i:s').'</strong></td>
			</tr>
			 
		</table>
		 
		<table class="headers" border="0">
			<tr>
				
				<td colspan="6" style="border:0px solid;text-align:center;font-size:14px;font-weight:bold;font-family:serif">ADJUSTMENT REPORT</td>

			</tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr>				
				<td align="left" width="100" ><span style="font-weight:bold;font-size:11px;">Vendor Name</span></td>			
				<td width="10">:</td>
				<td width="600" style="font-size:12px;">'.$row[0]['v_name'].'</td>
				<td align="left" width="30" style="font-weight:bold;font-size:11px;">Ref #</td>			
				<td width="10">:</td>
				<td width="250" style="font-size:12px;">'.$row[0]['ref_num'].'</td>
			</tr> 
			<tr>	
				<td align="left" width="100" ><span style="font-weight:bold;font-size:11px;"> </span></td>						
				<td width="10"></td>
				<td width="400" style="font-size:12px;"></td>
				<!--<td align="left" ><span style="font-weight:bold;font-size:11px;">DR #</span></td>			-->
				<td align="left" ><span style="font-weight:bold;font-size:11px;">Date Created</span></td>
				<td width="10">:</td>
				<td width="250" style="font-size:12px;">'.date("Y-m-d",strtotime($row[0]['requestDate'])).'</td>	
			</tr>
			
			<tr>			
				<td width="10"></td>
				<td width="10"></td>
				<<td align="left" ><span style="font-weight:bold;font-size:12px;"></span></td>						
				<td align="left"  width="110"><span style="font-weight:bold;font-size:11px;">Status</span></td>			
				<td width="10">:</td>
				<td width="250" style="font-size:12px;">'.($row[0]['is_approved']==1?"Approved":"For Approval").'</td>		
			</tr>
			<tr>	
				<!--<td align="left" ><span style="font-weight:bold;font-size:11px;"></span></td>						
				<td width="10"></td>
				<td width="10"></td>
				<td align="left" ><span style="font-weight:bold;font-size:11px;">Date Received</span></td>			
				<td width="10">:</td>
				<td width="250" style="font-size:12px;">'.(date("Y-m-d",strtotime($row[0]['date_created']))>date("Y-m-d",strtotime($row[0]['date_received']))?"":date("Y-m-d",strtotime($row[0]['date_received']))).'</td>-->
			</tr>
			<!--<tr>			
				<td width="10"></td>
				<td width="10"></td>
				<<td align="left" ><span style="font-weight:bold;font-size:12px;"></span></td>						
				<td align="left"  width="110"><span style="font-weight:bold;font-size:12px;"></span></td>		
				<td width="10"></td>
				<td width="250"></td>
					
			</tr>-->			 
		</table>
		 
	</htmlpageheader>

	<htmlpagefooter name="myfooter">
		<table width="100%" border="0">
			<!----11111111111111111111111-->
			<tr>
				<td width="90">Prepared By : </td>
				<td width="280"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(strtoupper($row2[0][0])==''?$nbsp:strtoupper($row2[0][0])).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
				<!--<td width="280"><tt><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(strtoupper($row[0]['ee_name'])==''?$nbsp:strtoupper($row[0]['ee_name'])).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></tt></td>-->
				<td width="80"> </td>

				<td width="100"> </td>				
				<td width="90"> </td> 
				<td width="290"><tt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tt></td>	
			 
			</tr>
			<!----2222222222222222222222222-->
			<tr>
				<td width="90">&nbsp;</td>
				<td width="200" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Promo Assistant</td>
				<td width="80">&nbsp; </td>
				<td width="100" align="left" border="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
				 <td width="90">&nbsp; </td>
				<td width="100" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				
			</tr>
			<!--33333333333333333333333333333333333-->
			<tr>
				<td>&nbsp; </td>
				<td align="left"> &nbsp;&nbsp;&nbsp;&nbsp;Signature Over Printed Name/Date </td>
				<td>&nbsp; </td>
				<td align="left"> </td>
				<td width="90" align="center"></td>
				<td  align="left"> &nbsp;&nbsp;&nbsp;</td> 
				
			</tr>
			<!----444444444444444444444444444444-->
			<tr>
				<td>&nbsp;</td>
				<td > </td>
				<td>&nbsp; </td>
				<td>&nbsp; </td>
				<td>&nbsp; </td>
				<td>&nbsp; </td>
			</tr> 
			<!----55555555555555555555555555555555-->
			<tr>
				<td>&nbsp; </td>
				<td>&nbsp; </td>
				<td>&nbsp; </td>
				<td>&nbsp; </td>
				<td>&nbsp; </td>
				<td>&nbsp; </td>
			</tr> 
			
		</table>
		<div style="font-size: 8pt; text-align: center; padding-top: 3mm; ">
			Page {PAGENO} of {nb}
		</div>
	</htmlpagefooter>

	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->'; 

	$html='<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table>';
	$html.='<table class="items2"><tr><td>&nbsp;</td></tr><tr>					 
					<!--<td><strong>DR # : </td><td>'.$row[0]['dr_number'].'</strong></td> -->
					<td>&nbsp;&nbsp;&nbsp;</td>
					<!--<td>Date Created : </td><td><strong>'.date("Y-m-d",strtotime($row[0]['date_created'])).'</strong></td> -->
					<!--<td><u>&nbsp;&nbsp;&nbsp;&nbsp;Date Received : </td><td><strong>'.date("Y-m-d",strtotime($row[0]['date_received'])).'</strong></u></td> -->
					<td>&nbsp;&nbsp;&nbsp;</td>
					<!--<td>Status : </td><td><strong>'.($row[0]["status"]==1?"Open":($row[0]["status"]==0?"Received":"Cancelled")).'</strong></td> -->
					<td>&nbsp;&nbsp;&nbsp;</td>
					<!--<td><strong>'.$row[0]['rec_by'].'-'.strtoupper($row2[0][0]).'</strong></td>-->
		   </tr></table>';	

	$html.='<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="7">
			<thead>
				<tr>
					<th width="90" align="center">SKU</th>
					<th >DESCRIPTION</th>
					<th width="180">STORE</th>
					<th width="100">ADJUSTMENT TYPE</th>
					<th width="100">UNIT RETAIL</th>					
					<th width="100">EXTENDED RETAIL</th>					
					<th width="100">QUANTITY</th>
					<!--<th>RECEIVED QUANTITY</th> -->
					<!--<th>REMARKS</th> -->
				</tr>
			</thead>
			<tbody>';	 
	$lineAmt=0;		
	for($i=0;$i<count($row);$i++){
		//$qry="SELECT coalesce(cost,0.0000) as cost FROM tbl_sku_cost WHERE sku=?";
		//$prepare->execute(array($row[0]["sku"]));	
		//$fetchCost=$prepare->fetchAll();
		$lineAmt+=$row[$i]['cost']*$row[$i]['qty'];
		$totalAmt+=$lineAmt;

		$html.='<tr>';
		$html.='<td align="center">'.$row[$i]['sku'].'</td>';
		$html.='<td align="left">'.$row[$i]['descr'].'</td>';
		$html.='<td align="center">'.$row[$i]['store'].'-'.$row[$i]['fromDesc'].'</td>';
		$html.='<td align="center">'.$row[$i]['type'].'</td>';
		$html.='<td align="right">'.number_format($row[$i]["cost"],2).'</td>';
		$html.='<td align="right">'.number_format($lineAmt,2).'</td>';		
		$html.='<td align="right">'.number_format($row[$i]['qty'],2).'</td>';
		//$html.='<td align="right"  width="100">'.number_format($row[$i]['rec_qty'],2).'</td>';
		//$html.='<td align="right" width="200"></td>';
		$html.='</tr>';
		$totalQty+=$row[$i]['qty'];
		$totalRecQty+=$row[$i]['rec_qty'];
		$lineAmt=0;
	}
		$html.='<tfoot>';
		$html.='<tr class="gtot">';
		$html.='<td><tt><strong>GRAND TOTAL</strong></tt></td>';
	 	$html.='<td><strong></strong></td>';
	 	
	 	$html.='<td><strong></strong></td>';
	 	$html.='<td align="right" style="padding-rights:20px;"></td>';
	 	$html.='<td><strong></strong></td>';
	 	$html.='<td  width="" align="right" style="padding-rights:18px;">'.number_format($totalAmt,2).'</td>';
	 	$html.='<td align="right" style="padding-rights:20px;">'.number_format($totalQty,2).'</td>';
	 	//$html.='<td align="right" style="padding-rights:20px;">'.number_format($totalRecQty,2).'</td>';
	 	//$html.='<td align="right" style="padding-rights:20px;"></td>';
	 
		$html.='</tfoot>';						
	$html.='</tbody></table>';	 
    
	$bottom='</body></html>';

	$mpdf->WriteHTML($top.utf8_encode($html).$bottom);
	$mpdf->setTitle("ADJUSTMENT SUMMARY");
	$mydate=getdate(date("U"));
	/*Save as with specific*/
	$mpdf->Output($mydate['month'].$mydate['mday'].$mydate['year']."(".$mydate['hours']."_".$mydate['minutes']."_".$mydate['hours'].").PDF","I");	 
	/* UPDATE isViewed=1 if this report seen by vendor already*/
	
	if($_GET['param'] == 2){
		$col = 'vVwd';
	}else if($_GET['param2'] == 1){
		$col = 'mVwd';
	}
	else if($_GET['param3'] == 1){
		$col = 'ctorVwd';
	}
	$qry = " UPDATE tbl_notify SET ".$col." = ?";
	$qry.= " WHERE id = ? ";

	$prepare = $db->prepare($qry); 
	$prepare->execute(array(1,$_GET['adj']));

?>