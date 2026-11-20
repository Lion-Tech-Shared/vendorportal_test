<?php
require "../../class-main/connection.class.php";
require "../../control/functions.php";
include "../../MPDF57/mpdf.php";
session_start();
ini_set('memory_limit', '1026M');

if($_SESSION['gid']==2)
	$vendor_num=$_SESSION['uid'];
else
	$vendor_num=$_GET['vendor'];

$store=$_GET['store'];
$stores=$_GET['stores'];



$mpdf = new mPDF('', 'A4', 0, '', 10, 10, 18, 15, 9, 9);
$mpdf->AddPage('L');

$mpdf->showWatermarkText = true;
$mpdf->watermarkText = " ";

$conn = new getConnection();
$db = $conn->PDO();
$arr=array();

$qry='SELECT d.lvendr as vendor,a.store,a.sku,b.descr,c.ccmnam,e.cost,sum(on_hand) as total,f.strnam ';
$qry.=' FROM `tbl_inventory` ';
$qry.=' a LEFT JOIN tbl_invmst b on a.sku=b.sku ';
$qry.=' left join tbl_cssmst c on b.sub_dept=c.ccmnum  ';
$qry.=' left join tbl_lcsucn d on d.lcnvdr=c.ccmnum ';
$qry.=' LEFT JOIN tbl_sku_cost e on e.sku=a.sku ';
$qry.=' left join tbl_store_master f on f.strnum=a.store ';
$qry.=' WHERE d.lvendr=? ';
$qry.=' group by d.lvendr,a.store,a.sku';
$flag=0;

/*************check if PA***************************/
$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['gid']));
$fetch=$prepare->fetchAll();
$isPa=$fetch[0]['IsPA'];

 

if(isset($_SESSION['stores'])){

	/*$qry2='SELECT a.lvendr as vendor,e.strnam,d.store,d.sku,f.descr,d.on_hand AS total,b.ccmnam,g.cost ';
	$qry2.=' FROM tbl_lcsucn A ';
	$qry2.=' LEFT JOIN tbl_cssmst B ON A.lcnvdr=B.ccmnum ';
	//$qry2.=' LEFT JOIN tbl_cssmct C ON B.ccmnum=C.CCTCSS ';
	$qry2.=' LEFT JOIN (select distinct cctloc,cctcss from tbl_cssmct) C ON B.ccmnum=C.CCTCSS ';
	$qry2.=' left JOIN TBL_INVENTORY D ON C.CCTLOC=d.store ';
	$qry2.=' LEFT JOIN tbl_store_master E ON E.strnum=d.store ';
	$qry2.=' LEFT JOIN tbl_invmst F ON F.sku=d.sku ';
	$qry2.=' LEFT JOIN tbl_sku_cost g on g.sku=d.sku ';
	$qry2.=' WHERE A.lvendr=? AND d.store IN('.implode(",", $_SESSION['stores']).') AND d.store is not null AND d.vendor=?';
	$qry2.=' ';*/
	if($vendor_num==''){
		$qry2='	 SELECT a.vendor,e.strnam,a.store,a.sku,f.descr,a.on_hand AS total,c.ccmnam,g.cost ';
		$qry2.=' FROM tbl_inventory a';
		$qry2.=' LEFT JOIN tbl_lcsucn b ON a.vendor=b.lvendr';
		$qry2.=' LEFT JOIN tbl_cssmst c ON b.lcnvdr=c.ccmnum ';
		//$qry2.=' LEFT JOIN tbl_cssmct C ON B.ccmnum=C.CCTCSS ';
		//$qry2.=' LEFT JOIN (select distinct cctloc,cctcss from tbl_cssmct) C ON B.ccmnum=C.CCTCSS ';
		//$qry2.=' left JOIN TBL_INVENTORY D ON C.CCTLOC=d.store ';
		$qry2.=' LEFT JOIN tbl_store_master e ON e.strnum=a.store ';
		$qry2.=' LEFT JOIN tbl_invmst f ON f.sku=a.sku ';
		$qry2.=' LEFT JOIN tbl_sku_cost g on g.sku=a.sku ';
		$qry2.=' WHERE a.store IN('.implode(",", $_SESSION['stores']).') AND a.store is not null AND a.on_hand > 0 ';
		if($isPa==1){
			$qry2.=" AND a.vendor IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid='".$_SESSION['uid']."')";
		}else{			
			$qry2.=' ';				
		}
		$qry2.=" ORDER BY a.store ";
		$prepare=$db->prepare($qry2);
		$prepare->execute();
		$row=$prepare->fetchAll();	
	}else{
		$qry2='	 SELECT a.vendor,e.strnam,a.store,a.sku,f.descr,a.on_hand AS total,c.ccmnam,g.cost ';
		$qry2.=' FROM tbl_inventory a';
		$qry2.=' LEFT JOIN tbl_lcsucn b ON a.vendor=b.lvendr';
		$qry2.=' LEFT JOIN tbl_cssmst c ON b.lcnvdr=c.ccmnum ';
		//$qry2.=' LEFT JOIN tbl_cssmct C ON B.ccmnum=C.CCTCSS ';
		//$qry2.=' LEFT JOIN (select distinct cctloc,cctcss from tbl_cssmct) C ON B.ccmnum=C.CCTCSS ';
		//$qry2.=' left JOIN TBL_INVENTORY D ON C.CCTLOC=d.store ';
		$qry2.=' LEFT JOIN tbl_store_master e ON e.strnum=a.store ';
		$qry2.=' LEFT JOIN tbl_invmst f ON f.sku=a.sku ';
		$qry2.=' LEFT JOIN tbl_sku_cost g on g.sku=a.sku ';
		$qry2.=' WHERE a.vendor=? AND a.store IN('.implode(",", $_SESSION['stores']).') AND a.store is not null AND a.vendor=? AND a.on_hand > 0';
		$qry2.=' ORDER BY a.store';
		$prepare=$db->prepare($qry2);
		$prepare->execute(array($vendor_num,$vendor_num));
		$row=$prepare->fetchAll();
	}
	
	//$qry2.=' LEFT JOIN tbl_sku_cost e ON e.sku=d.sku ';
	
//$qry2.=' GROUP BY A.lvendr,d.store,d.sku';
}else{
	if($isPa==1){
		$qry2='	 SELECT a.vendor,e.strnam,a.store,a.sku,f.descr,a.on_hand AS total,c.ccmnam,g.cost ';
		$qry2.=' FROM tbl_inventory a';
		$qry2.=' LEFT JOIN tbl_lcsucn b ON a.vendor=b.lvendr';
		$qry2.=' LEFT JOIN tbl_cssmst c ON b.lcnvdr=c.ccmnum ';	 
		$qry2.=' LEFT JOIN tbl_store_master e ON e.strnum=a.store ';
		$qry2.=' LEFT JOIN tbl_invmst f ON f.sku=a.sku ';
		$qry2.=' LEFT JOIN tbl_sku_cost g on g.sku=a.sku ';
		$qry2.=' WHERE a.store IN(SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid=?) AND a.store is not null ';		
		$qry2.=" AND a.vendor IN(SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid='".$_SESSION['uid']."') AND a.on_hand > 0";		
		$qry2.=" ORDER BY a.store ";
		$prepare=$db->prepare($qry2);
		$prepare->execute(array($_SESSION['uid']));
		$row=$prepare->fetchAll();	
	}else{

		$flag=1;
		$qry2='SELECT "" as vendor,e.strnam,d.store,d.sku,f.descr,d.on_hand AS total,"",g.cost ';
		//$qry2.=' FROM tbl_lcsucn A ';
		//$qry2.=' LEFT JOIN tbl_cssmst B ON A.lcnvdr=B.ccmnum ';
		//$qry2.=' LEFT JOIN tbl_cssmct C ON B.ccmnum=C.CCTCSS ';
		//$qry2.=' LEFT JOIN (select distinct cctloc,cctcss from tbl_cssmct) C ON B.ccmnum=C.CCTCSS ';
		//$qry2.=' LEFT JOIN tbl_cssmct C ON B.ccmnum=C.CCTCSS ';
		$qry2.=' FROM tbl_inventory d ';
		$qry2.=' LEFT JOIN tbl_store_master e ON e.strnum=d.store ';
		$qry2.=' LEFT JOIN tbl_invmst f ON f.sku=d.sku ';
		$qry2.=' LEFT JOIN tbl_sku_cost g on g.sku=d.sku ';
		//$qry2.=' LEFT JOIN tbl_sku_cost e ON e.sku=d.sku ';
		//$qry2.=" WHERE d.store IN(SELECT store FROM TBL_USER_INFO WHERE user_id='".$_SESSION['uid']."') ";
		$qry2.=" WHERE d.vendor=? AND d.store IN(SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid='".$_SESSION['uid']."')  AND d.on_hand > 0";
		//$qry2.=' WHERE A.lvendr=? AND d.store IN('.implode(",", $_SESSION['stores']).') AND d.store is not null AND d.vendor=?';
		$qry2.=' ORDER BY d.store ';
		//$qry2.=' GROUP BY A.lvendr,d.store,d.sku';
		$prepare=$db->prepare($qry2);
		$prepare->execute(array($vendor_num));
		//$prepare->execute(array($vendor_num,$vendor_num));
		$row=$prepare->fetchAll();
	}
}





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
			}
			
			table td {
				font-size: 8pt;
			}
		</style>
						 
</head>
<body style="border:1px solid;">
	<!--mpdf
	<htmlpageheader name="myheader">
		 
		<table width="100%" class="headers" border="0">
			<tr>
				<td width="900" style="color:#0000BB;" colspan="4"><strong><tt></tt></strong></td>
				<td width="80px" align="right"><strong><tt>'.date('m/d/Y').''.$stores[1].'</tt></strong></td>
				<td width="60px" align="right"><strong><tt>'.date('H:i:s').'</tt></strong></td>
			</tr>
			<tr>
				
				<td align="CENTER" colspan="5" style="border:0px solid;text-align:center;font-size:14px;font-weight:bold;font-family:serif">CONCESSION INVENTORY REPORT</td>

			</tr>			 
		 
			<!--<tr>				
				<td align="left" width="100" ><span style="font-weight:bold;font-size:12px;">Vendor Name</span></td>			
				<td width="10"><tt>:</tt></td>
				<td width="600"><tt>'.$row[0]['ccmnam'].'</tt></td>
				<td width="100"><TT></TT></td>
				<td><TT></TT></td>
				 
			</tr>
			 
			<tr>	
				<<td align="left" ><tt><span style="font-weight:bold;font-size:12px;">Department</span></tt></td>						
				<td width="10"><tt>:</tt></td>
				<td width="10"><tt>'.$row[0]['v_name'].'</tt></td>
				<td><TT></TT></td>
				<td><TT></TT></td>
				 		
			</tr>-->
			 			 
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

	$html='';
	//foreach ($stores as $key => $value) {
		 
		//$htmls="".$value[1];
	//}
	$html.='<div><tt>'.$htmls.'</tt></div>';
	$html.='<table class="items2" width="100%" >
			 		<tr>					 
					<!--<td><strong>Vendor # : </td><td><tt>'.$row[0]['vendor'].'</strong></tt></td> -->
					<td>&nbsp;&nbsp;&nbsp;</td>
					<!--<td><strong>Vendor Name : </strong></td><td><tt>'.$row[0]['ccmnam'].'</tt></td> -->
					<td>&nbsp;&nbsp;&nbsp;</td>
					 
		   </tr></table>';
	if($flag==1){	   	
		$html.='<table><tr>				
					<td align="left" width="100" ><tt><span style="font-weight:bold;font-size:12px;"> </span></tt></td>			
					<td width="10"><tt></tt></td>
					<!--<td width="600"><tt>'.$row[0][2].'</tt></td>-->
					<td width="600"><tt></tt></td>
					<td width="100"><TT></TT></td>
					<td><TT></TT></td>
					 
				</tr>
				 
			 </table>';
	}else{
		$html.='<table><tr>				
				<td align="left" width="100" ><span style="font-weight:bold;font-size:12px;">'.($vendor_num==''?"Per store":"Vendor Name").'</span></td>			
				<td width="10"><tt>:</tt></td>
				<td width="600">'.($vendor_num==''?"":$row[0]['ccmnam']).'</td>
				<td width="100"><TT></TT></td>
				<td><TT></TT></td>
				 
			</tr>
			 
			<!--<tr>	
				<<td align="left" ><tt><span style="font-weight:bold;font-size:12px;">Department</span></tt></td>						
				<td width="10"><tt>:</tt></td>
				<td width="10"><tt>'.$row[0]['v_name'].'</tt></td>
				<td><TT></TT></td>
				<td><TT></TT></td>
				 		
			</tr>--></table>';
	}		

	$html.='<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="7">
			<thead>
				<tr>
					<th align="center"><tt>store</tt></th>
					<th ><tt>sku</tt></th>
					<th><tt>DESCRIPTION</tt></th>
					<th><tt>ON HAND</tt></th>					 
					<th><tt>UNIT RETAIL</tt></th>
					<th><tt>EXTENDED RETAIL</tt></th>
				</tr>
			</thead>
			<tbody>';	 
	$total=0;
	$total2=0;
	$total_cost=0;
	$grand_cost=0;
	$totalB=0;
	$total2B=0;
	$total_costB=0;
	$grand_costB=0;
	$stur=$row[0]['store'];

	for($i=0;$i<count($row);$i++){
		//$qry="SELECT coalesce(cost,0.0000) as cost FROM tbl_sku_cost WHERE sku=?";
		//$prepare->execute(array($row[0]["sku"]));	
		//$fetchCost=$prepare->fetchAll();

		$total+=$row[$i]['total']; 	
		$total2+=$row[$i]['cost']; 	
		$total_cost=$row[$i]['cost']*$row[$i]['total'];
		$grand_cost+=$total_cost;
		
		$totalB+=$row[$i]['total']; 	
		$total2B+=$row[$i]['cost']; 	
		$total_costB=$row[$i]['cost']*$row[$i]['total'];
		$grand_costB+=$total_cost;
		if($stur==$row[$i]['store']){
			$stur=$row[$i]['store'];
		}
		else{
			//$total-=$row[$i]['total'];
			$stur=$row[$i]['store'];
			$html.='<tr border="1" style=" border-top:1px solid;border-bottom:0px solid;border-left:0px;border-right:0px;">';
			$html.='<td align="center"><strong>Total</strong></td>';
			$html.='<td align="center"><u> </u></td>';
			$html.='<td align="left"><u> </u></td>';
			$html.='<td align="right" style="padding-rights:20px;"><strong>'.number_format(($total-$row[$i]['total']),2).'</strong></td>';
		 	//$html.='<td align="right" style="padding-rights:20px;"><strong>'.number_format(($total2-$row[$i]['cost']),2).'/</strong></td>';
		 	$html.='<td align="right" style="padding-rights:20px;"><strong></strong></td>';
		 	$html.='<td align="right" style="padding-rights:20px;"><strong>'.number_format(($grand_cost-$total_cost),2).'</strong></td>';
			$html.='</tr>';
			$html.='<tr><td></td></tr>';
			//re initialized vars
			$total=0;
			$total2=0;
			$total_cost=0;
			$grand_cost=0;
			//-----
			$total+=$row[$i]['total']; 	
			$total2+=$row[$i]['cost']; 	
			$total_cost=$row[$i]['cost']*$row[$i]['total'];
			$grand_cost+=$total_cost;
			//for grand total
			/*$totalB+=$total;
			$total2B+=$total2;
			$total_costB=$total_cost;
			$grand_costB+=$grand_cost;*/

		} 
		/*$totalB+=$total; 	
		$total2B+=$total2;
		$total_costB=$total_cost;
		$grand_costB+=$grand_cost;*/

		$html.='<tr>';
		$html.='<td align="center">'.$row[$i]['strnam'].'</td>';
		$html.='<td align="center">'.$row[$i]['sku'].'</td>';
		$html.='<td align="left">'.$row[$i]['descr'].'</td>';
		$html.='<td align="right">'.number_format($row[$i]['total'],2).'</td>';		
		$html.='<td align="right">'.number_format($row[$i]['cost'],2).'</td>';
		$html.='<td align="right">'.number_format($row[$i]['cost']*$row[$i]['total'],2).'</td>';
		$html.='</tr>';
		
		if(($i+1)==count($row)){

			$html.='<tr border="1" style=" border-top:1px solid;border-bottom:0px solid;border-left:0px;border-right:0px;">';
			$html.='<td align="center"><strong>Total</strong></td>';
			$html.='<td align="center"><u> </u></td>';
			$html.='<td align="left"><u> </u></td>';
			$html.='<td align="right" style="padding-rights:20px;"><strong>'.number_format(($total),2).'</strong></td>';
		 	//$html.='<td align="right" style="padding-rights:20px;"><strong>'.number_format(($total2),2).'</strong></td>';
		 	$html.='<td align="right" style="padding-rights:20px;"><strong></strong></td>';
		 	$html.='<td align="right" style="padding-rights:20px;"><strong>'.number_format(($grand_cost),2).'</strong></td>';
			$html.='</tr>';
			$html.='<tr><td></td></tr>';			
		}

		//if($i > 22)
			//$html .= '<pagebreak /><br />';
	}
		$html.='<tr><td></td></tr>';	

		$html.='<tfoot>';
		$html.='<tr class="gtot">';
		$html.='<td><strong>GRAND TOTAL</strong></td>';
	 	$html.='<td><strong><tt></tt></strong></td>';
	 	//$html.='<td align="right" style="padding-rights:8px;"><tt>'.number_format($totalAmt,4).'</tt></td>';
	 	$html.='<td><strong><tt></tt></strong></td>';	   
	 	$html.='<td align="right" style="padding-rights:20px;">'.number_format($totalB,2).'</td>';
	 	//$html.='<td align="right" style="padding-rights:20px;">'.number_format($total2B,2).'</td>';
	 	$html.='<td align="right" style="padding-rights:20px;"></td>';
	 	$html.='<td align="right" style="padding-rights:20px;">'.number_format($grand_costB,2).'</td>';
		$html.='</tfoot>';						
	$html.='</tbody></table>';	 
 
	$bottom='</body></html>';

	$mpdf->WriteHTML($top.utf8_encode($html).$bottom);
	
	$mpdf->Output();
	$mpdf->Output();
	//$mPDF->render();
	//file_put_contents(time(), $mpdf->Output());
	unset($_SESSION['stores']);

?>