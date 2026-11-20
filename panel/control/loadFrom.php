<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();	 /* redirect if session not set */

$conn = new getConnection();
$db = $conn->PDO();

$vendor_num=$_POST['vendor_num']; 

$isWhse=(isset($_SESSION['isWhse'])?1:0);
$arr=array();
/*$qry=" SELECT strnum FROM tbl_store_master WHERE strwhs ";
$qry=" IN( ";
$qry.="    SELECT b.strnum FROM `tbl_lcsecloc` a LEFT JOIN tbl_store_master b ON a.lcstor=b.strnum ";
$qry.="    WHERE  a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG')";
$qry.=") AND strhdo=? ";*/
/*$qry="  SELECT b.strnum,b.strnam FROM `tbl_lcsecloc` a ";
$qry.=" LEFT JOIN tbl_store_master b on a.lcstor=b.strnum ";
$qry.=" WHERE a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG') ";


$prepare=$db->prepare($qry); 
$prepare->execute(array($_SESSION['uid'],$strhdo));
$fetch=$prepare->fetchAll();
*/

/***********************************WAREHOUSE****************************************/
if($isWhse==1){

	$strhdo='W';

	$qry="		SELECT DISTINCT c.cctloc,d.strnam FROM tbl_lcsucn a 		     ";
	$qry.="		LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum  					 ";
	$qry.="		LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss  					 ";
	$qry.="		LEFT JOIN ( 	"; //{{{{{{{
	/**********Inserted from qry above********************************************/
	$qry.="  		 SELECT b.strnum,b.strnam FROM `tbl_lcsecloc` a 			 ";
	$qry.="			 LEFT JOIN tbl_store_master b on a.lcstor=b.strnum 			 ";
	$qry.=" 		 WHERE a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG') ";
	/******************************************************************************/
	$qry.="		) d on c.cctloc=d.strnum ";				  //}}}}}}}}}}
	$qry.="		WHERE a.lvendr=? AND d.strnam is not null	   					 ";

	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['uid'],$strhdo,$vendor_num));
	$fetch=$prepare->fetchAll();
}else{
	$strhdo='S';
	/***STORE*******************STORE**********************STORE*******************/
	$qry="		SELECT DISTINCT c.cctloc,d.strnam FROM tbl_lcsucn a 		     ";
	$qry.="		LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum  					 ";
	$qry.="		LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss  					 "; 
	$qry.="		LEFT JOIN ( 	"; //{{{{{{{
	/**********Inserted from qry above*******************************/
	$qry.="  		 SELECT 													 ";
	$qry.="					b.strnum,b.strnam FROM `tbl_lcseclocpa` a 			 ";
	$qry.="			 LEFT JOIN ";
	$qry.="					tbl_store_master b ON a.lcstor=b.strnum 			 ";
	$qry.=" 		 WHERE ";
	$qry.="					a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG') ";
	/****************************************************************/
	$qry.="		) d on c.cctloc=d.strnum ";				 //}}}}}}}}}}
	$qry.="		WHERE a.lvendr=? AND d.strnam is not null	   					 ";
	//$qry.="			    AND curdate() BETWEEN date(c.cctlsd) AND date(c.cctled)";
	 
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['uid'],$strhdo,$vendor_num));
	$fetch=$prepare->fetchAll();

	for($i=0;$i<count($fetch);$i++){
		$myArr['0']=$fetch[$i][0];
		$myArr['1']=$fetch[$i][1];
		array_push($arr, $myArr); 
	}
 
	$qry="  SELECT ";
	$qry.=" 	strnum,strnam       ";
	$qry.="	FROM 					";
	$qry.="		tbl_store_master    ";
	$qry.="	WHERE strnum=(SELECT strwhs FROM tbl_store_master WHERE strnum=?)";				
	$prepare=$db->prepare($qry);
	$prepare->execute(array($fetch[0][0]));
	$fetch=$prepare->fetchAll();

	for($i=0;$i<count($fetch);$i++){
		//if(isset($_POST['transType']))
		//	break;
		$myArr['0']=$fetch[$i][0];
		$myArr['1']=$fetch[$i][1];
		array_push($arr, $myArr);
	}
	echo json_encode($arr);
	exit;

}
 


echo json_encode($fetch);
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
