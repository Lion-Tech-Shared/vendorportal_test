<?php
require_once "../../class-main/PHPMailer-master/class.phpmailer.php";
require_once "../../class-main/PHPMailer-master/class.smtp.php";
require_once "../../class-main/connection.class.php";
session_start();
$email=$_POST['email'];
$mail=new PHPMailer();
$mail->IsHTML(true);
$mail->addAddress($email);
$exc=new phpmailerException();

if($mail->validateAddress($email)){	
	$_SESSION['isemailvalid']=1;
	
	$con=new getConnection();
	$db=$con->PDO();
	
	if($_SESSION['gid']==2){
		//$query="SELECT v_email_address FROM tbl_vendor_info WHERE v_code=? ";												
		$col="v_email_address";
		$tbl="tbl_vendor_info";
		$primary="v_code";
	}
	else{
		//$query="SELECT ee_email_address FROM tbl_user_info WHERE v_code=? ";													
		$col="ee_email_address";
		$tbl="tbl_user_info";
		$primary="user_id";
	}					
	$update_email="UPDATE ".$tbl." SET  ".$col."=?  WHERE  ".$primary."=?";
	$prepare=$db->prepare($update_email);
	$prepare->execute(array($email,$_SESSION['uid']));
	
	header("Location:../change_email.php");
	exit;
}else{
//	$_SESSION['isemailvalid'];
	$_SESSION['isemailvalid']=0;
	header("Location:../change_email.php");
	exit;
}


?>
