<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set   */

	$conn = new getConnection();
	$db = $conn->PDO();
	//$arr=array();

	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}	
	$myClass=new myClass2();

	$myCart=$_GET['myCart'];

	//echo $myCart[0][1];
	//exit;
	$myArr=array();

	foreach ($myCart as $key => $value) {		 
		 $sku=$value[0];
		 $vendor=$value[1];
		 $loc=$value[2];
		 $qty=$value[3]; 
		 $arr=array('sku'=>$sku,''=>$vendor,'store'=>$loc,'qty'=>$qty,'on_hand'=>$myClass->getInv($sku,$vendor,$loc,$db)); 
		 array_push($myArr,$arr);
		 
	}
	echo json_encode($myArr);
	
	 

/*---------My Class-------------------*/

class myClass2{

	
	public function getInv($sku,$vendor,$store,$db){ 
		$qry="  SELECT * FROM tbl_inventory      "; 
		$qry.=" WHERE vendor=? AND store=? AND sku=?";		 
		$array=array($vendor,$store,$sku);	  
		$prepare=$db->prepare($qry);
		$prepare->execute($array);
		$fetch=$prepare->fetchAll();
		return $fetch[0]['on_hand'];		
	}

}  
?>