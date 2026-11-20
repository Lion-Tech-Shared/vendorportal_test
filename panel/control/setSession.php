<?php

session_start();

$stores=$_POST['stores'];
$_SESSION['stores']=$stores;
 
exit;

?>