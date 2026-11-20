<?php
require_once "redirect.php";
session_start();

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();
/* redirect if accessing through address */
if(!isset($_POST['flag'])){
	header("Location:../home.php");
}

$_SESSION['isForRecLoaded']=1;



?>