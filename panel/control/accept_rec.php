<?php
require "../../class-main/connection.class.php";
session_start();
//dr_num:dr_num,arr:my_arr,con_num:contractnum,store:store,vndr_num:vendor_num,qty:total_qty,amt:total_amt}),

$conn = new getConnection();
$db = $conn->PDO();
$arr=$_POST['arr'];
$dr_num=$_POST['dr_num'];
$store=$_POST['store'];
//$contractNum=$_POST['con_num'];
$vendor=$_POST['vndr_num'];
$qty=$_POST['qty'];
$amt=$_POST['amt'];
$remarks=$_POST['remarks'];

$prepare=$db->prepare("SELECT * FROM tbl_dr_hdr WHERE dr_number=? AND status=?");
$prepare->execute(array($dr_num,2));	 
$my_row=$prepare->fetchAll();
//echo $arr.$dr_num.$store.$contractNum.$vendor.$qty.$amt;
if(count($my_row) < 1){
	echo 0;
	exit;
}	
foreach ($arr as $key => $value) {

		if($key < 1){
			$prepare=$db->prepare("INSERT INTO tbl_receive_hdr(dr_number,remarks) VALUES(?,?)");
			$prepare->execute(array($dr_num,$remarks));	
		}
 		//echo $value[0].'-'.$value[1]; 		 	 
		$prepare=$db->prepare("SELECT COUNT(*) as cnt FROM tbl_inventory WHERE store=? AND sku=? AND vendor=?");
		$prepare->execute(array($store,$value[0],$vendor));	 
		$row=$prepare->fetchAll();
		 
		if($row[0]['cnt']>0){			
	 		//$prepare=$db->prepare("UPDATE tbl_inventory SET on_hand=on_hand+?,lst_rec_date=NOW() WHERE store=? AND sku=? AND @cnt: > (SELECT COUNT(*) FROM tbl_dr_hdr WHERE status=1)");
	 		$prepare=$db->prepare("UPDATE tbl_inventory SET on_hand=on_hand+?,lst_rec_date=NOW() WHERE store=? AND sku=? AND vendor=?");
			$prepare->execute(array($value[3],$store,$value[0],$vendor));	
		}else{
	 		$prepare=$db->prepare("INSERT INTO tbl_inventory(vendor,store,sku,on_hand,lst_sales_dwnlod_date) SELECT @vendor:=$vendor,@store:='$store',@sku:='$value[0]',@qty:=$value[3],date_of_update FROM date_of_updates");
	 		//$prepare=$db->prepare("SELECT ".$store.",".$value[0].",".$value[3].",date_of_update FROM date_of_updates INSERT INTO tbl_inventory(store,sku,on_hand,lst_sales_dwnlod_date)");
			$prepare->execute();	
		}			 
		$prepare=$db->prepare("INSERT INTO tbl_receive(dr_number,sku,stock_num,rec_qty,received_by) VALUES(?,?,?,?,?)");
		$prepare->execute(array($dr_num,$value[0],$value[1],$value[3],$_SESSION['uid']));	
}
$prepare=$db->prepare("UPDATE tbl_dr_hdr SET status=? WHERE dr_number=?");
$prepare->execute(array(0,$dr_num));

$prepare=$db->prepare("UPDATE tbl_notify SET pUpd = ? WHERE id = ?");
$prepare->execute(array(1,$dr_num));	

echo 1; 


exit;
?>