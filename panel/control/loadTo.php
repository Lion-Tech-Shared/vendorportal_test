<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();	 /* redirect if session not set */

$conn = new getConnection();
$db = $conn->PDO();
$arr=array(); 
$isWhse=(isset($_SESSION['isWhse'])?1:0);
if($isWhse==1){
	$strhdo='W';
}else{
	$strhdo='S';
}
/*$qry=" SELECT strnum FROM tbl_store_master WHERE strwhs ";
$qry=" IN( ";
$qry.="    SELECT b.strnum FROM `tbl_lcsecloc` a LEFT JOIN tbl_store_master b ON a.lcstor=b.strnum ";
$qry.="    WHERE  a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG')";
$qry.=") AND strhdo=? ";*/


/*$qry=" SELECT DISTINCT a.lvendr AS vendor,b.ccmnam as vendor_name FROM TBL_LCSUCN A ";
$qry.="	LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM  ";
$qry.="	LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS  ";
$qry.=" WHERE  c.cctloc in( ";
$qry.="       SELECT b.strnum FROM `tbl_lcsecloc` a ";
$qry.="       LEFT JOIN tbl_store_master b ON a.lcstor=b.strnum "; 
$qry.="       WHERE a.lcuseid=? AND b.strhdo=?  ";
$qry.=" ) AND curdate() BETWEEN c.cctlsd AND c.cctled  ";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],'S'));
*/
$vendor_num=$_POST['vendor_num'];
$store_num=$_POST['store_num'];

/**********************QUERY STORE*****************************************/
/*$qry="  SELECT DISTINCT b.strnum,b.strnam FROM `tbl_lcsecloc` a 		   ";
$qry.=" LEFT JOIN tbl_store_master b on a.lcstor=b.strnum 				   ";
$qry.=" WHERE 															   ";
$qry.=" b.strnum IN(													   ";
$qry.=" 	SELECT DISTINCT c.cctloc FROM TBL_LCSUCN A 					   ";
$qry.="		LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM  				   ";
$qry.="		LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS  			       ";
$qry.="		WHERE a.lvendr=? AND c.cctloc!=?							   ";
$qry.=" ) AND b.strsbu IN('DS','HG') AND b.strnam NOT LIKE('%INACTIVE%')   ";
$qry.="   AND b.strnum IN( SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid=?)";
*/

//$qry.=" WHERE a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG') ";
//$prepare=$db->prepare($qry);
//$prepare->execute(array($vendor_num,$store_num,$_SESSION['uid']));
//$fetch=$prepare->fetchAll();

/******************QUERY WHSE LINKED TO USER *********************************/
/*$qry="  SELECT DISTINCT b.strnum,b.strnam FROM `tbl_lcsecloc` a 		   ";
$qry.=" LEFT JOIN tbl_store_master b on a.lcstor=b.strnum 				   ";
$qry.=" WHERE 															   ";
$qry.=" b.strnum IN(													   ";
$qry.=" 	SELECT DISTINCT c.cctloc FROM TBL_LCSUCN A 					   ";
$qry.="		LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM  				   ";
$qry.="		LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS  			       ";
$qry.="		WHERE a.lvendr=? AND c.cctloc!=?							   ";
$qry.=" ) AND b.strsbu IN('DS','HG') AND b.strnam NOT LIKE('%INACTIVE%')   ";
$qry.="   AND b.strnum IN( SELECT lcstor FROM tbl_lcsecloc WHERE lcuseid=?)";

$qry="  SELECT DISTINCT a.lvendr AS vendor,b.ccmnam as vendor_name FROM TBL_LCSUCN A ";
$qry.="	LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM  ";
$qry.="	LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS  ";
$qry.=" WHERE  c.cctloc in( ";
$qry.="    SELECT strnum FROM tbl_store_master WHERE strwhs IN( ";
$qry.="       SELECT b.strnum FROM `tbl_lcsecloc` a ";
$qry.="       LEFT JOIN tbl_store_master b ON a.lcstor=b.strnum ";*/
/*												     changeable to accomodate SMR		*/
/*$qry.="       WHERE a.lcuseid=? AND b.strhdo=? AND b.strsbu  IN('DS','HG') ";
$qry.="    ) AND strhdo=? ";
$qry.=" ) AND curdate() BETWEEN cctlsd AND cctled";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],'W','S'));*/



$strhdo='S';
/***STORE*******************STORE**********************STORE******/
$qry="		SELECT DISTINCT c.cctloc,d.strnam FROM tbl_lcsucn a 		     ";
$qry.="		LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum  					 ";
$qry.="		LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss  					 ";
$qry.="		LEFT JOIN tbl_store_master d on d.strnum=c.cctloc				 ";
$qry.="     WHERE ";
$qry.="     d.strhdo=? AND d.strsbu IN('DS','HG') 		    	 	 ";
$qry.="		AND a.lvendr=? AND d.strnam NOT LIKE('%INACTIVE%')";
$qry.="		AND c.cctloc!=? ";
//$qry.="     AND curdate() BETWEEN date(c.cctlsd) AND date(c.cctled) ";
 
$prepare=$db->prepare($qry);
$prepare->execute(array($strhdo,$vendor_num,$store_num));
$fetch=$prepare->fetchAll();
for($i=0;$i<count($fetch);$i++){
	$var['store']=$fetch[$i][0];
	$var['storeName']=$fetch[$i][1];
	array_push($arr, $var);
}

//$strhdo='W';
/***WAREHOUSE*******************WAREHOUSE**********************WAREHOUSE******/

/*$qry="		SELECT strnum,strnam FROM tbl_store_master WHERE strnum IN( ";

$qry.="			SELECT d.strwhs FROM tbl_lcsucn a 		     ";
$qry.="			LEFT JOIN tbl_cssmst b on a.lcnvdr=b.ccmnum  					 ";
$qry.="			LEFT JOIN tbl_cssmct c on b.ccmnum=c.cctcss  					 ";
$qry.="			LEFT JOIN tbl_store_master d on d.strnum=c.cctloc				 ";
//$qry.="			LEFT JOIN tbl_store_master e on e.strnum=d.strwhs				 ";
$qry.="     	WHERE ";
$qry.="     	d.strhdo=? and d.strsbu IN('DS','HG') 		    	 	 ";
$qry.="			AND a.lvendr=? AND d.strnam NOT LIKE('%INACTIVE%')";
//$qry.="		    AND curdate() BETWEEN date(c.cctlsd) AND date(c.cctled) 	";
$qry.="		) ";
$qry.=" 		AND strnum!=?	";
*/
$qry="  SELECT DISTINCT d.strwhs, e.strnam ";
$qry.=" FROM tbl_lcsucn a ";
$qry.=" LEFT JOIN tbl_cssmst b ON a.lcnvdr = b.ccmnum ";
$qry.=" LEFT JOIN tbl_cssmct c ON b.ccmnum = c.cctcss ";
$qry.=" LEFT JOIN tbl_store_master d ON d.strnum = c.cctloc ";
$qry.=" LEFT JOIN tbl_store_master e ON d.strwhs = e.strnum ";
$qry.=" WHERE d.strhdo=?";
$qry.=" AND d.strsbu IN ('DS', 'HG') ";
$qry.=" AND a.lvendr=? AND d.strnam NOT LIKE ('%INACTIVE%') ";
$qry.=" AND d.strnum!=?";


$prepare=$db->prepare($qry);
$prepare->execute(array($strhdo,$vendor_num,$store_num));
$fetch=$prepare->fetchAll();
for($i=0;$i<count($fetch);$i++){
	$var['store']=$fetch[$i][0];
	$var['storeName']=$fetch[$i][1];
	array_push($arr, $var);
}


//$strhdo='S';
/***WAREHOUSE*******************WAREHOUSE**********************WAREHOUSE******/
//$qry="		SELECT DISTINCT e.strwhs,d.strnam FROM TBL_LCSUCN A 		     ";
//$qry.="		LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM  					 ";
//$qry.="		LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS  					 ";
//$qry.="		LEFT JOIN ( 	"; //{{{{{{{
/**********Inserted from qry above*******************************/
// $qry.="  		 SELECT b.strnum,b.strnam FROM `tbl_lcseclocpa` a 			 ";
// $qry.="			 LEFT JOIN tbl_store_master b on a.lcstor=b.strnum 			 ";
// $qry.=" 		 WHERE a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG') ";
/****************************************************************/
// $qry.="		) D ON c.cctloc=D.strnum ";				 //}}}}}}}}}}
// $qry.="	    LEFT JOIN tbl_store_master e on c.cctloc=e.strnum";
// $qry.="		WHERE a.lvendr=? AND d.strnam is not null	   					 ";
// $prepare=$db->prepare($qry);
// $prepare->execute(array($_SESSION['uid'],$strhdo,$vendor_num));
// $fetch=$prepare->fetchAll();
// $arr[]=$fetch;

/*$qry="sELECT b.strnum,b.strnam FROM `tbl_lcsecloc` a ";
$qry.=" LEFT JOIN tbl_store_master b on a.lcstor=b.strnum ";
$qry.=" WHERE a.lcuseid=? AND b.strhdo=? AND b.strsbu IN('DS','HG') ";
*/

//array_push($fetch, $arr);
//$prepare=$db->prepare($qry);
//$prepare->execute(array($vendor_num,$store_num,$_SESSION['uid']));
//$fetch=$prepare->fetchAll();
//$qry2="";
echo json_encode($arr);
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
