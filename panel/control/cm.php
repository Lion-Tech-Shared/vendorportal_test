<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

if(isset($_GET['flag'])){
	$array=array();
	$prepare=$db->prepare('SELECT * FROM tbl_cm ');
	$prepare->execute();
	$row=$prepare->fetchAll();

	/*$row2['notice']=$row[0]['notice'];
	$row2['for']=$row[0]['for_ctr'];
	$row2['not']=$row[0]['not_for_ctr'];
	array_push($array, $row2);
	echo json_decode($array);*/
	echo $row[0]['notice'].'--'.$row[0]['for_ctr'].'--'.$row[0]['not_for_ctr'];
	exit;
}

$notice=$_GET['notice'];
$forcounter=$_GET['forcounter'];
$not=$_GET['notFor'];

$prepare=$db->prepare('SELECT * FROM tbl_cm ');
//$prepare=$db->prepare("INSERT INTO tbl_cm VALUES(".$notice.",".$forcounter.",".$not.")");
$prepare->execute();
$row=$prepare->fetchAll();

if(count($row)>0){
	$prepare=$db->prepare("UPDATE tbl_cm SET notice='".$notice."',for_ctr='".$forcounter."',not_for_ctr='".$not."' ");
	//$prepare=$db->prepare("INSERT INTO tbl_cm VALUES(".$notice.",".$forcounter.",".$not.")");
	$prepare->execute();
	//$row=$prepare->fetchAll();
}else{
	$prepare=$db->prepare("INSERT INTO tbl_cm VALUES('".$notice."','".$forcounter."','".$not."')");
	$prepare->execute();
	//$row=$prepare->fetchAll();
}

?>