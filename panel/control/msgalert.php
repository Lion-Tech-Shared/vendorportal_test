<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();
 
if($_SESSION['gid'] != 2){
	header("Location:../home.php");
}

$qry=" SELECT * FROM tbl_adjustment WHERE userid=? AND is_approved=?";
$prepare=$db->prepare($qry);
$prepare->execute(array($_SESSION['uid'],0));
$fetch=$prepare->fetchAll();
echo json_encode($fetch);

?>
