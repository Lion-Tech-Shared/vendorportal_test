<?php
require "../../class-main/connection.class.php";
require "../../control/functions.php";
include "../../MPDF57/mpdf.php";
session_start();
ini_set('memory_limit', '1026M');

/* redirect to home.php if dr not set*/
if(!isset($_GET['trf'])){
	header("Location:../home.php");
}
$trf_num=$_GET['trf'];

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
				font-family: tahoma;
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
				font-family: tahoma;
			}
			
			table td {
				font-size: 8pt;
			}
			.headers{ font-family: tahoma;}
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

	$qry="SELECT a.dr_number,b.sku,c.descr,f.cost as amt,b.stock_num,";
	$qry.="b.qty,a.date_created,e.rec_qty,a.status,";
	$qry.="if(e.received_by='','',CONCAT('Received by: ',e.received_by)) as rec_by,e.received_by,g.v_name,h.strnam,e.date_received";
	//$qry.=",if(e.received_by='','',CONCAT('Received by: ',CONCAT(CONCAT(e.received_by,'-'),d.ee_name))) as rec_by ";
	$qry.=" FROM tbl_dr_hdr a left join tbl_store_delivery b on a.dr_number=b.dr_number ";
	$qry.=" left join (SELECT store,user_id,ee_name FROM tbl_user_info WHERE user_id=? ) d  ";
	$qry.=" on a.loc_code=d.store  ";
	$qry.=" left join tbl_receive e on e.dr_number=b.dr_number AND e.sku=b.sku AND e.stock_num=b.stock_num ";
	$qry.=" left join tbl_sku_cost f on f.sku=b.sku  left join tbl_invmst c on b.sku=c.sku left join tbl_vendor_master g on a.vendor_code=g.v_code left join tbl_store_master h on h.strnum=a.loc_code WHERE a.dr_number=?";
	if($fetch[0][1]==1){
		$qry.=" AND b.qty > 0 ";
	}
	
	$qry="  SELECT a.trf_num,d.ccmnam as v_name, "; /*0,1*/
	$qry.="        b.sku,e.descr,sum(b.qty) as qty,";			/*2,3,4*/
	$qry.="        a.ccn_mf_num,a.fromLoc,a.toLoc,a.date_created,f.stat_desc,"; /*5,6,7,8,9*/
	$qry.="	  	   g.cost as amt,a.created_by,a.date_received,h.ee_name";
	$qry.="		  ,i.strnam as fromDesc,j.strnam as toDesc,b.rec_qty";
	$qry.=" FROM tbl_transfer_hdr a ";
	$qry.=" LEFT JOIN tbl_transfer_dtl b 		  ";
	$qry.=" 							ON a.trf_num=b.trf_num 			  ";
	$qry.=" LEFT JOIN tbl_lcsucn c    		  ";
	$qry.=" 							ON a.vendor_num=c.lvendr  	      ";
	$qry.=" LEFT JOIN tbl_cssmst d    		  ";
	$qry.=" 							ON c.lcnvdr=d.ccmnum	  	      ";
	$qry.=" LEFT JOIN tbl_invmst e    		  ";
	$qry.=" 							ON b.sku=e.sku 			 	      "; 	
	$qry.=" LEFT JOIN tbl_transfer_status f    		  ";
	$qry.=" 							ON a.status=f.id		 	      ";
	$qry.=" LEFT JOIN tbl_sku_cost g    		  ";
	$qry.=" 							ON g.sku=b.sku 			 	      ";
	$qry.=" LEFT JOIN tbl_user_info h    		  ";
	$qry.=" 							ON h.user_id=a.received_by 	      ";
	$qry.=" LEFT JOIN tbl_store_master i    		  ";
	$qry.=" 							ON i.strnum=a.fromLoc	 	      ";
	$qry.=" LEFT JOIN tbl_store_master j    		  ";
	$qry.=" 							ON j.strnum=a.toLoc	 	      ";
	$qry.=" WHERE a.trf_num=? 						  					  ";
	$qry.="	GROUP BY b.sku,a.fromLoc,a.toLoc";

	$prepare=$db->prepare($qry);
	$prepare->execute(array($trf_num));	
	$row=$prepare->fetchAll();

	$qry="SELECT coalesce(ee_name,'') as name FROM tbl_user_info WHERE user_id=?";
	$prepare2=$db->prepare($qry);
	$prepare2->execute(array($row[0]['created_by']));	
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
				
				<td colspan="6" style="border:0px solid;text-align:center;font-size:14px;font-weight:bold;font-family:tahoma">TRANSFER SUMMARY</td>

			</tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr>				
				<td align="left" width="100" ><span style="font-weight:bold;font-size:11px;">Vendor Name</span></td>			
				<td width="10">:</td>
				<td width="600" style="font-size:12px;">'.$row[0]['v_name'].'</td>
				<td align="left" width="30" style="font-weight:bold;font-size:11px;">VP-TRF #</td>			
				<td width="10">:</td>
				<td width="250" style="font-size:12px;">'.$row[0]['trf_num'].'</td>
			</tr>
			<tr>	
				<td align="left" width="100" ><span style="font-weight:bold;font-size:11px;">CCN MF #</span></td>						
				<td width="10">:</td>
				<td width="400" style="font-size:12px;">'.$row[0][5].'</td>
				<!--<td align="left" ><span style="font-weight:bold;font-size:11px;">DR #</span></td>			-->
				<td align="left" ><span style="font-weight:bold;font-size:11px;">Date Created</span></td>
				<td width="10">:</td>
				<td width="250" style="font-size:12px;">'.date("Y-m-d",strtotime($row[0]['date_created'])).'</td>	
			</tr>
			<tr>	
				<<td align="left" ><span style="font-weight:bold;font-size:11px;"></span></td>						
				<td width="10"></td>
				<td width="10"></td>
				<td align="left" ><span style="font-weight:bold;font-size:11px;">Date Received</span></td>			
				<td width="10">:</td>
				<td width="250" style="font-size:12px;">'.(date("Y-m-d",strtotime($row[0]['date_created']))>date("Y-m-d",strtotime($row[0]['date_received']))?"":date("Y-m-d",strtotime($row[0]['date_received']))).'</td>				
			</tr>
			<tr>			
				<td width="10"></td>
				<td width="10"></td>
				<<td align="left" ><span style="font-weight:bold;font-size:12px;"></span></td>						
				<td align="left"  width="110"><span style="font-weight:bold;font-size:11px;">Status</span></td>			
				<td width="10">:</td>
				<td width="250" style="font-size:12px;">'.($row[0]['stat_desc']).'</td>		
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
				<td width="280"><tt><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></tt></td>
				<!--<td width="280"><tt><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(strtoupper($row[0]['ee_name'])==''?$nbsp:strtoupper($row[0]['ee_name'])).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></tt></td>-->
				<td width="80">Received By: </td>

				<td width="100"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(strtoupper($row2[0][0])==''?$nbsp:strtoupper($row2[0][0])).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>				
				<td width="90"> </td> 
				<td width="290"><tt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tt></td>	
			 
			</tr>
			<!----2222222222222222222222222-->
			<tr>
				<td width="90">&nbsp;</td>
				<td width="200" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vendor Representative</td>
				<td width="80">&nbsp; </td>
				<td width="100" align="left" border="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Promo Assistant</td> 
				 <td width="90">&nbsp; </td>
				<td width="100" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				
			</tr>
			<!--33333333333333333333333333333333333-->
			<tr>
				<td>&nbsp; </td>
				<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature Over Printed Name/Date </td>
				<td>&nbsp; </td>
				<td align="left">&nbsp;&nbsp;Signature Over Printed Name/Date </td>
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
					<th width="90" align="center">Sku</th>
					<th >Description</th>
					<th width="150">From</th>
					<th width="150">To</th>
					<th width="100">Unit Retail</th>					
					<th width="100">Extended Retail</th>					
					<th width="100">Quantity</th>
					<th width="100">Received Qty</th>
					<!--<th>RECEIVED QUANTITY</th> -->
					<!--<th>REMARKS</th> -->
				</tr>
			</thead>
			<tbody>';	 
	$lineAmt=0;		
	$totalRecQty=0;
	$totalRecQty2=0;
	for($i=0;$i<count($row);$i++){
		//$qry="SELECT coalesce(cost,0.0000) as cost FROM tbl_sku_cost WHERE sku=?";
		//$prepare->execute(array($row[0]["sku"]));	
		//$fetchCost=$prepare->fetchAll();
		$lineAmt+=$row[$i]['amt']*$row[$i]['qty'];
		$totalAmt+=$lineAmt;

		$html.='<tr>';
		$html.='<td align="center">'.$row[$i]['sku'].'</td>';
		$html.='<td align="left">'.$row[$i]['descr'].'</td>';
		$html.='<td align="left">'.$row[$i]['fromLoc'].'-'.$row[$i]['fromDesc'].'</td>';
		$html.='<td align="left">'.$row[$i]['toLoc'].'-'.$row[$i]['toDesc'].'</td>';
		$html.='<td align="right">'.number_format($row[$i]["amt"],2).'</td>';
		$html.='<td align="right">'.number_format($lineAmt,2).'</td>';		
		$html.='<td align="right">'.number_format($row[$i]['qty'],2).'</td>';
		$html.='<td align="right">'.number_format($row[$i]['rec_qty'],2).'</td>';
		//$html.='<td align="right"  width="100">'.number_format($row[$i]['rec_qty'],2).'</td>';
		//$html.='<td align="right" width="200"></td>';
		$html.='</tr>';
		$totalQty+=$row[$i]['qty'];
		$totalRecQty+=$row[$i]['rec_qty'];
		//$totalRecQty2+=	
		$lineAmt=0;
	}
		$html.='<tfoot>';
		$html.='<tr class="gtot">';
		$html.='<td><strong>GRAND TOTAL</strong></td>';
	 	$html.='<td><strong></strong></td>';
	 	
	 	$html.='<td><strong></strong></td>';
	 	$html.='<td align="right" style="padding-rights:20px;"></td>';
	 	$html.='<td><strong></strong></td>';
	 	$html.='<td  width="" align="right" style="padding-rights:18px;">'.number_format($totalAmt,2).'</td>';
	 	$html.='<td align="right" style="padding-rights:20px;">'.number_format($totalQty,2).'</td>';
	 	$html.='<td align="right" style="padding-rights:20px;">'.number_format($totalRecQty,2).'</td>';
	 	//$html.='<td align="right" style="padding-rights:20px;">'.number_format($totalRecQty,2).'</td>';
	 	//$html.='<td align="right" style="padding-rights:20px;"></td>';
	 
		$html.='</tfoot>';						
	$html.='</tbody></table>';	 
    
	$bottom='</body></html>';

	$mpdf->WriteHTML($top.utf8_encode($html).$bottom);
	$mpdf->setTitle("TRANSFER SUMMARY");
	$mydate=getdate(date("U"));
	/*Save as with specific*/
	$mpdf->Output($mydate['month'].$mydate['mday'].$mydate['year']."(".$mydate['hours']."_".$mydate['minutes']."_".$mydate['hours'].").PDF","I");	 
	/* UPDATE isViewed=1 if this report seen by vendor already*/

	if(isset($_GET['sd'])){
		if($_GET['sd'] == 1)
			exit;
	}
	
	if($_SESSION['gid']==2){
		$qry="  UPDATE tbl_notificationFlag SET isVendorVwd=?,vendorVwdDate=now() WHERE id=? ";		 
		$prepare=$db->prepare($qry);
		$prepare->execute(array(1,$trf_num));			  
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
	$prepare->execute(array(1,$trf_num));
	
	 
?>