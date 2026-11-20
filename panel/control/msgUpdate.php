<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

$flag=$_POST['flag'];
$id=$_POST['idi'];

if($_SESSION['gid'] != 2){
	header("Location:../home.php");
}

if($flag==1){
	$qry=" UPDATE tbl_adjustment SET is_viewed=? WHERE userid=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array(1,$_SESSION['uid']));
}else{
	$qry=" UPDATE tbl_adjustment SET is_viewed=? WHERE id=?";
	$prepare=$db->prepare($qry);
	$prepare->execute(array(1,$id));
}

 
echo $id;
//SELECT d.lvendr,sum(on_hand) FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum group by d.lvendr

?>
