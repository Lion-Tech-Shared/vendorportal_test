<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();

	/* redirect if accessing through address */
	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}
	if($_GET['flag']==1)
		echo $_SESSION['trf_num'];  
	else if($_GET['flag']==2)
		echo $_SESSION['regular'];
	else
		echo $_SESSION['total'];
?>