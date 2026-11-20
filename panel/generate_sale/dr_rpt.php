<?php
require "../../class-main/connection.class.php";
require "../../control/functions.php";
include "../../MPDF57/mpdf.php";
session_start();
set_time_limit(0);
//ini_set('memory_limit', '20000M');
ini_set('memory_limit', '512M');

/* redirect to home.php if dr not set*/
if(!isset($_GET['dr'])){
	header("Location:../home.php");
}
$dr=$_GET['dr'];

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
				font-size: 10pt;
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
				font-size: 10pt;
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
				font-size:8pt;
				font-family:tahoma;
			}
			.headers{ font-family:tahoma;}
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
	$qry.=" ,i.remarks,j.remarks as delRemarks,k.remarks as rcvRemarks";
	$qry.=" ,j.dr ";
	//$qry.=",if(e.received_by='','',CONCAT('Received by: ',CONCAT(CONCAT(e.received_by,'-'),d.ee_name))) as rec_by ";
	$qry.=" FROM tbl_dr_hdr a left join tbl_store_delivery b on a.dr_number=b.dr_number ";
	$qry.=" left join (SELECT store,user_id,ee_name FROM tbl_user_info WHERE user_id=? ) d  ";
	$qry.=" on a.loc_code=d.store  ";
	$qry.=" left join tbl_receive e on e.dr_number=b.dr_number AND e.sku=b.sku AND e.stock_num=b.stock_num ";
	$qry.=" left join tbl_sku_cost f on f.sku=b.sku ";
	$qry.=" left join tbl_invmst c on b.sku=c.sku ";
	$qry.=" left join tbl_vendor_master g on a.vendor_code=g.v_code ";
	$qry.=" left join tbl_store_master h on h.strnum=a.loc_code ";
	$qry.=" LEFT JOIN tbl_deliverytype i on i.dr_number = b.dr_number ";
	$qry.=" LEFT JOIN tbl_delwindow j on j.vd_dr = i.dr_number ";
	$qry.=" LEFT JOIN tbl_receive_hdr k on k.dr_number = j.vd_dr ";
	$qry.=" WHERE a.dr_number=?";

	if($fetch[0][1]==1){
		$qry.=" AND b.qty > 0 ";
	}
	
	$prepare=$db->prepare($qry);
	//$prepare=$db->prepare("SELECT a.dr_number,b.sku,c.descr,f.cost as amt,b.stock_num,b.qty,b.date_created,e.rec_qty,a.status,if(e.received_by='','',CONCAT('Received by: ',e.received_by)) as rec_by,coalesce(d.ee_name,'none') as ename FROM tbl_dr_hdr a left join tbl_store_delivery b on a.dr_number=b.dr_number left join tbl_user_info d on a.loc_code=d.store AND a.user_id=d.user_id left join tbl_receive e on e.dr_number=b.dr_number AND e.sku=b.sku AND e.stock_num=b.stock_num left join tbl_sku_cost f on f.sku=b.sku  left join tbl_invmst c on b.sku=c.sku  WHERE a.dr_number=?  ");
	//$prepare=$db->prepare("SELECT a.dr_number,b.sku,c.descr,00 as amt,b.stock_num,b.qty,b.date_created FROM tbl_dr_hdr a left join tbl_store_delivery b on a.dr_number=b.dr_number left join tbl_invmst c on b.sku=c.sku WHERE a.user_id=? AND a.dr_number=?");
	//$prepare=$db->prepare("SELECT a.dr_number,a.sku,b.descr,00 as amt,a.stock_num,a.qty,a.date_created FROM tbl_store_delivery a left join tbl_invmst b on a.sku=b.sku WHERE a.user_id=? AND a.dr_number=?");
	//$prepare->execute(array($_SESSION['uid'],$dr));	 1457517866-590004
	$prepare->execute(array($_SESSION['uid'],$dr));	
	$row=$prepare->fetchAll();

	if(count($row) < 1){
		echo '<h4>VP-DR not found!</h4>';
		exit;
	}
	$qry="SELECT coalesce(ee_name,'') as name FROM tbl_user_info WHERE user_id=?";
	$prepare2=$db->prepare($qry);
	$prepare2->execute(array($row[0]['received_by']));	
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
				<td style="color:#0000BB;" colspan="4"><strong><tt></tt></strong></td>
				<td width="80px" align="right"><strong>'.date('m/d/Y').'</strong></td>
				<td width="60px" align="right"><strong>'.date('H:i:s').'</strong></td>
			</tr>
			 
		</table>
		 
		<table class="headers" border="0">
			<tr>
				
				<td colspan="6" style="border:0px solid;text-align:center;font-size:14px;font-weight:bold;font-family:tahoma">CONCESSION ARRIVAL REFERENCE</td>

			</tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr>				
				<td align="left" width="100" ><span style="font-weight:bold;font-size:12px;">Vendor Name</span></td>			
				<td width="10"><tt>:</tt></td>
				<td width="600" style="font-size:12px;">'.$row[0]['v_name'].'</td>
				<td align="left" width="30" style="font-weight:bold;font-size:12px;">VP-DR #</td>			
				<td width="10"><tt>:</tt></td>
				<td width="250" style="font-size:12px;">'.$row[0]['dr_number'].'</td>
			</tr>
			<tr>	
				<td align="left" width="100" ><span style="font-weight:bold;font-size:12px;">Store Name</span></td>						
				<td width="10"><tt>:</tt></td>
				<td width="400" style="font-size:12px;">'.$row[0]['strnam'].'</td>
				<!--<td align="left" ><span style="font-weight:bold;font-size:12px;">DR #</span></td>			-->
				<td align="left" ><span style="font-weight:bold;font-size:12px;">Date Created</span></td>
				<td width="10"><tt>:</tt></td>
				<td width="250" style="font-size:12px;">'.date("Y-m-d",strtotime($row[0]['date_created'])).'</td>	
			</tr>
			<tr>	
				<td align="left" ><tt><span style="font-weight:bold;font-size:12px;"> </span></tt></td>						
				<td width="10"><tt></tt></td>
				<td width="10"><tt></tt></td>
				<td align="left" ><span style="font-weight:bold;font-size:12px;">Date Received</span></td>			
				<td width="10"><tt>:</tt></td>
				<td width="250"><tt>'.(date("Y-m-d",strtotime($row[0]['date_created']))>date("Y-m-d",strtotime($row[0]['date_received']))?"":date("Y-m-d",strtotime($row[0]['date_received']))).'</tt></td>				
			</tr>
			<tr>			
				<td width="10"><tt></tt></td>
				<td width="10"><tt></tt></td>
				<<td align="left" ><tt><span style="font-weight:bold;font-size:12px;"></span></tt></td>						
				<td align="left"  width="110"><span style="font-weight:bold;font-size:12px;">Status</span></td>			
				<td width="10"><tt>:</tt></td>
				<td width="250" style="font-size:12px;">'.($row[0]["status"]==1?"Open":($row[0]["status"]==0?"Received":($row[0]["status"]==2?"Ordered":"Cancelled"))).'</td>		
			</tr>
			<tr>			
				<td width="10"><tt></tt></td>
				<td width="10"><tt></tt></td>
				<<td align="left" ><tt><span style="font-weight:bold;font-size:12px;"></span></tt></td>						
				<td align="left"  width="110"><span style="font-weight:bold;font-size:12px;">DR #</span></td>			
				<td width="10"><tt>:</tt></td>
				<td width="250" style="font-size:12px;">'.$row[0]['dr'].'</td>		
			</tr>
			<!--<tr>			
				<td width="10"><tt></tt></td>
				<td width="10"><tt></tt></td>
				<<td align="left" ><tt><span style="font-weight:bold;font-size:12px;"></span></tt></td>						
				<td align="left"  width="110"><tt><span style="font-weight:bold;font-size:12px;"></span></tt></td>		
				<td width="10"><tt></tt></td>
				<td width="250"><tt></tt></td>
					
			</tr>-->			 
		</table>
		 
	</htmlpageheader>

	<htmlpagefooter name="myfooter">
		<table width="100%" border="0">
			<!----11111111111111111111111-->
			<tr>
				<td width="70">Prepared By : </td> 
				<td width="50" align="left"><tt><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></tt></td>
				<td width="70">Received By : </td>
				<td width="150"> <tt><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(strtoupper($row2[0][0])==''?$nbsp:strtoupper($row2[0][0])).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></tt></td>				
				<td width="90"></td>
				<td width="100"><tt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tt></td>						
				 
			</tr>
			<!----2222222222222222222222222--> 
			<tr>
				
				<td width="70"><tt>&nbsp;</tt></td>
				<td width="50" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vendor Representative</td>
				<td width="30"><tt>&nbsp;</tt> </td>
				<td width="150" align="left" border="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Promo Assistant</td> 
				
				<td width="90"><tt>&nbsp;</tt> </td>
				<td width="100" align="left	">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				 
			</tr>
			<!--33333333333333333333333333333333333-->
			<tr>
				<td><tt>&nbsp;</tt> </td>
				<td align="left">&nbsp;&nbsp;Signature Over Printed Name/Date </td>
				<td><tt>&nbsp;</tt> </td>
				<td align="left">Signature Over Printed Name/Date </td>
				<td width="90" align="center"><tt></tt></td>
				<td  align="left">  &nbsp; </td>
								
			</tr>
			<!----444444444444444444444444444444-->
			<tr>
				<td><tt>&nbsp;</tt></td>
				<td ><tt></tt> </td>
				<td><tt>&nbsp;</tt> </td>
				<td><tt>&nbsp;</tt> </td>
				<td><tt>&nbsp;</tt> </td>
				<td><tt>&nbsp;</tt> </td>
			</tr> 
			<!----55555555555555555555555555555555-->
			<tr>
				<td><tt>&nbsp;</tt> </td>
				<td><tt>&nbsp;</tt> </td>	
				<td><tt>&nbsp;</tt> </td>
				<td><tt>&nbsp;</tt> </td>
				<td><tt>&nbsp;</tt> </td>
				<td><tt>&nbsp;</tt> </td>
			</tr> 
			<tt></tt>
		</table>
		<div style="font-size: 8pt; text-align: center; padding-top: 3mm; ">
			<tt>Page {PAGENO} of {nb}</tt>
		</div>
	</htmlpagefooter>

	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->'; 

	$html='<table><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table>';
	$html.='<table class="items2"><tr><td>&nbsp;</td></tr><tr>					 
					<!--<td><strong>DR # : </td><td><tt>'.$row[0]['dr_number'].'</strong></tt></td> -->
					<td>&nbsp;&nbsp;&nbsp;</td>
					<!--<td>Date Created : </td><td><tt><strong>'.date("Y-m-d",strtotime($row[0]['date_created'])).'</strong></tt></td> -->
					<!--<td><u>&nbsp;&nbsp;&nbsp;&nbsp;Date Received : </td><td><tt><strong>'.date("Y-m-d",strtotime($row[0]['date_received'])).'</strong></tt></u></td> -->
					<td>&nbsp;&nbsp;&nbsp;</td>
					<!--<td>Status : </td><td><tt><strong>'.($row[0]["status"]==1?"Open":($row[0]["status"]==0?"Received":"Cancelled")).'</strong></tt></td> -->
					<td>&nbsp;&nbsp;&nbsp;</td>
					<!--<td><tt><strong>'.$row[0]['rec_by'].'-'.strtoupper($row2[0][0]).'</strong></tt></td>-->
		   </tr></table>';	

	$html.='<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;text-align:center;" cellpadding="7">
			<thead>
				<tr>
					<th width="90" align="center" >SKU</th>
					<th >DESCRIPTION</th>
					<th  width="90">STOCK #</th>
					<th  width="90">UNIT RETAIL</th>					
					<th  width="90">EXTENDED RETAIL</th>					
					<th  width="90">QUANTITY</th>
					<th  width="90">RECEIVED QUANTITY</th> 
					<th  width="200">REMARKS</th> 
				</tr>
			</thead>
			<tbody>';	 
	$lineAmt=0;		
	for($i=0;$i<count($row);$i++){
		//$qry="SELECT coalesce(cost,0.0000) as cost FROM tbl_sku_cost WHERE sku=?";
		//$prepare->execute(array($row[0]["sku"]));	
		//$fetchCost=$prepare->fetchAll();
		$lineAmt+=$row[$i]['amt']*$row[$i]['qty'];
		$totalAmt+=$lineAmt;

		$html.='<tr>';
		$html.='<td>'.$row[$i]['sku'].'</td>';
		$html.='<td align="left">'.$row[$i]['descr'].'</td>';
		$html.='<td align="center">'.$row[$i]['stock_num'].'</td>';
		$html.='<td align="right">'.number_format($row[0]["amt"],2).'</td>';
		$html.='<td align="right">'.number_format($lineAmt,2).'</td>';		
		$html.='<td align="right">'.number_format($row[$i]['qty'],2).'</td>';

		$html.='<td align="right">'.number_format($row[$i]['rec_qty'],2).'</td>';
		$rem = "<font color='blue'>Merch </font>: ".$row[$i]['remarks'].", <font color='blue'>Vendor</font> :".$row[$i]['delRemarks'].", <font color='blue'>PA</font> :".$row[$i]['rcvRemarks'];
		$html.='<td align="left">'.($i==0?$rem:"").'</td>';
		//$flag['remarks']="<font color='blue'>Merch </font>: ".$row2[0]['remarks'].", <font color='blue'>Vendor</font> :".$row2[0]['delRemarks'].", <font color='blue'>PA</font> :".$row2[0]['rcvRemarks'];
		$html.='</tr>';
		$totalQty+=$row[$i]['qty']; 
		$totalRecQty+=$row[$i]['rec_qty'];
		$lineAmt=0;
	}
		$html.='<tfoot>';
		$html.='<tr class="gtot">';
		$html.='<td><strong><tt>GRAND TOTAL</tt></strong></td>';
	 	$html.='<td><strong><tt></tt></strong></td>';
	 	
	 	$html.='<td><strong><tt></tt></strong></td>';
	 	
	 	$html.='<td><strong><tt></tt></strong></td>';
	 	$html.='<td  width="" align="right" style="padding-rights:18px;"><tt>'.number_format($totalAmt,2).'</tt></td>';
	 	$html.='<td align="right" style="padding-rights:20px;"><tt>'.number_format($totalQty,2).'</tt></td>';
	 	$html.='<td align="right" style="padding-rights:20px;"><tt>'.number_format($totalRecQty,2).'</tt></td>';
	 	$html.='<td align="right" style="padding-rights:20px;"><tt></tt></td>';
		$html.='</tfoot>';						
	$html.='</tbody></table>';	 
    
	$bottom='</body></html>';
	//$mpdf->WriteHTML($top.utf8_encode($html).$bottom);
	$mpdf->WriteHTML($top.($html).$bottom);
	$mpdf->setTitle("CONCESSION ARRIVAL REFERENCE");
	$mydate=getdate(date("U"));
	/*Save as with specific*/
	$mpdf->Output($mydate['month'].$mydate['mday'].$mydate['year']."(".$mydate['hours']."_".$mydate['minutes']."_".$mydate['hours'].").PDF","I");
	//$mpdf->Output();
	//$mPDF->render();
	//file_put_contents(time(), $mpdf->Output());

	/* UPDATE isViewed=1 if this report seen by vendor already*/
	if(isset($_GET['sd'])){
		if($_GET['sd'] == 1)
			exit;
	}
	if($_SESSION['gid']==2){
		$qry="UPDATE tbl_deliverytype SET isViewedV=? WHERE dr_number=?";
		$prepare=$db->prepare($qry);
		$prepare->execute(array(1,$dr));	
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
	$prepare->execute(array(1,$dr));
?>