<?php
session_start();
if(!isset($_SESSION['uid']) || !isset($_POST['pass'])){
	header("Location:../../home.php");
	exit;	
}

require_once "../../class-main/connection.class.php";
$array=array();
$con=new getConnection();
$db=$con->PDO();

$query="SELECT user_id,status FROM tbl_user_account WHERE group_id <> ?";
$prepare=$db->prepare($query);
$prepare->execute(array(1));
$fetch=$prepare->fetchAll();
for($i = 0; $i < count($fetch); $i++){
	$arr['user_id']=$fetch[$i]["user_id"];
	$status=$fetch[$i]["status"]==1?"Enabled":"Disabled";
	$arr['status']=$status;
	
	array_push($array,$arr);
}

echo json_encode($array);
?>