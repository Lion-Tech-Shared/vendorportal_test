<?php
if(!$_POST['userid']){
	header("Location:../../home.php");
	exit;
}	

require_once "../../class-main/PHPMailer-master/class.phpmailer.php";
require_once "../../class-main/PHPMailer-master/class.smtp.php";
require_once "../../class-main/connection.class.php";
require_once "../../getIp.php"; 
require_once "../../control/functions.php";

$newip=new getIp();
$ip=$newip->get_ip();
$con=new getConnection();
$db=$con->PDO();
 
$userid=trim($_POST['userid']);
//check if userid exist
$query="SELECT user_id,group_id FROM tbl_user_account WHERE user_id = ? limit 1 ";
$prepare=$db->prepare($query);
$prepare->execute(array($userid));
$fetch=$prepare->fetchAll();
if(count($fetch) < 1){
	echo -1;
	exit;	
}
$gid=$fetch[0]['group_id'];
//--------------------------

//get email
if($gid==2){
	$get_email=$db->prepare("SELECT v_email_address FROM tbl_vendor_info WHERE v_code=?"); 
	$get_email->execute(array($userid));
	$fetch_email=$get_email->fetchAll();
	$email=$fetch_email[0][0]; 
}
else{
	$get_email=$db->prepare("SELECT ee_email_address FROM tbl_user_info WHERE user_id=?"); 
	$get_email->execute(array($userid));
	$fetch_email=$get_email->fetchAll();
	$email=$fetch_email[0][0]; 	
}

$query="SELECT * FROM tbl_send_link WHERE userid=?";

$prepare=$db->prepare($query);
$prepare->execute(array($userid));
$fetch=$prepare->fetchAll();
$append=rand(0,1000);
//$random=md5(mcrypt_create_iv(32,MCRYPT_DEV_RANDOM));
$random = randomstring();
$random=$random."".$append;
$ip="192.168.10.213";

$select2="SELECT * FROM tbl_email_link_exp";	
$prepare=$db->prepare($select2);
$prepare->execute();
$fetch2=$prepare->fetchAll();
$duration=$fetch2[0]['exp_duration'];

if(count($fetch)>0){
	$msg="https://".$_SERVER['SERVER_NAME']."/reset_password.php?rgn=".$random."&userid=".$userid;
	//$msg="http://".$ip."/ccnportaltest/reset_password.php?rgn=".$random."&userid=".$userid;
	$bool=sendMail($email,"Vendor Portal Password Reset",$msg);
	if($bool==1){
		$update="UPDATE tbl_send_link SET link=?,date=NOW() WHERE userid=?";	
		$prepare=$db->prepare($update);
		$prepare->execute(array($random,$userid));					
		
		echo "1-".$duration;
	}else if($bool==0){
		echo -2;	
	}
}else{
	$msg="https://".$_SERVER['SERVER_NAME']."/reset_password.php?rgn=".$random."&userid=".$userid;
	//$msg="http://".$ip."/ccnportaltest/reset_password.php?rgn=".$random."&userid=".$userid;
	$bool=sendMail($email,"Vendor Portal Password Reset",$msg);
	if($bool==1){
		$insert="INSERT INTO tbl_send_link VALUES(?,?,NOW())";	
		$prepare=$db->prepare($insert);
		$prepare->execute(array($userid,$random));
		echo "1-".$duration;
	}else{
		echo -2;	
	}
}
?>