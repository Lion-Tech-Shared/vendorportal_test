<?php
require_once "class-main/PHPMailer-master/class.phpmailer.php";
require_once "class-main/PHPMailer-master/class.smtp.php";
require_once "control/functions.php";
$receiver=$_POST['memail'];
$title=$_POST['mtitle'];
$msg=$_POST['mcontent'];
 
sendMail($receiver,$title,$msg);
?>