<?php
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();
/* redirect if accessing through address */
if(!isset($_GET['flag'])){
	header("Location:../home.php");
}
$flag=0;
if(isset($_SESSION['clickLinkNotification'])){
	$flag=1;
}
echo $flag;
?>