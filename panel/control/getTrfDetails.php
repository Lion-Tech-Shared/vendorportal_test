<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();
 

$conn = new getConnection();
$db = $conn->PDO();
//$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
//$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

if(!isset($_SESSION["uid"]) ) {
    header("Location:../home.php");
} 
/* redirect if accessing through address */
if(!isset($_GET['flag'])){
	header("Location:../home.php");
}
$txt_trf=$_GET['txt_trf'];  

$qry= "  SELECT a.trf_num,d.ccmnam as v_name, "; /*0,1*/
$qry.="        b.sku,e.descr,sum(b.qty) as qty,";			/*2,3,4*/
$qry.="        a.ccn_mf_num,a.fromLoc,a.toLoc,a.date_created,f.stat_desc,"; /*5,6,7,8,9*/
$qry.="	  	   g.cost as amt,a.created_by,a.date_received,h.ee_name";

$qry.="	  	   ,i.strnam as fromLocDesc,j.strnam as toLocDesc";

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
$qry.=" 							ON j.strnum=a.toLoc  		      ";

$qry.=" WHERE a.trf_num=? 						  					  ";
$qry.="	GROUP BY b.sku,a.fromLoc,a.toLoc";
 
$prepare=$db->prepare($qry); 
$prepare->execute(array($txt_trf));
$fetch=$prepare->fetchAll();

echo json_encode($fetch);
?>
