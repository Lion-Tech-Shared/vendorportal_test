<?PHP
	echo '<header><link href="../../css-main/bootstrap.min.css" rel="stylesheet">
					<!--to circle icon-->
					<!--<link href="../css-main/sb-admin-2.css" rel="stylesheet">-->
					<link href="../../css-main/bootstrap.mod.css" rel="stylesheet">					 				 
					<!--[if lt IE 9]>
						<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
						<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>					 
						<script src="../lumino/js/html5shiv.js"></script>
						<script src="../lumino/js/respond.min.js"></script>						 
					<![endif]-->
					<!--<link href="../../css-main/cssmod/bootstrap.min.css" rel="stylesheet">-->
					<link href="../../css-main/cssmod/styles.css" rel="stylesheet"></header>
					';

	require_once "../../class-main/PHPMailer-master/class.phpmailer.php";
	require_once "../../class-main/PHPMailer-master/class.smtp.php";
	
	require "../../class-main/connection.class.php";
	require "../../control/mysqli_function.php";
	require "../../control/functions.php";
	require_once "../../panel/check_effect.php";
	
	session_start();
	
	if(isset($_SESSION["change_failed"])) {
		unset($_SESSION["change_failed"]);
	}
	
	$current = strtoupper(trim($_POST["cpass"]));
	$new = strtoupper(trim($_POST["npass"]));
	$rnew = strtoupper(trim($_POST["rnpass"]));
	$email=$_POST['email'];
		
	if($new != $rnew) {
		$_SESSION["change_failed"] = 1;
		
		header("Location: ../change_password.php");
		exit;
	}
	if(!preg_match("/[^a-z_\-0-9]/i",$new)){
		$_SESSION["change_failed"] = 8;
		header("Location: ../change_password.php");
		exit;
	}
	if($_SESSION['uid']==$new){
		$_SESSION["change_failed"] = 5;		
		header("Location: ../change_password.php");
		exit;
	}
	if(strlen($new)<6){
		$_SESSION["change_failed"] = 7;		
		header("Location: ../change_password.php");
		exit;
	}
	//check if email is valid.
	$return=validate_email($email,"CCN Vendor Portal");
	if($return==3){
		$_SESSION["change_failed"] = 6;		
		header("Location: ../change_password.php");	
		exit;
	}else if($return==2){
		$_SESSION['email_msg']="Email Address does not exist Or Invalid";	
		header("Location: ../change_password.php");	
		exit;
	}
	
	$conn = new getConnection();
	$db = $conn->MySQLi();
	$db2 = $conn->PDO();
	
	$res = SQL("SELECT
					user_id, group_id
				FROM
					tbl_user_account
				WHERE
					user_id = ? and user_pass = ? limit ? ", strtoupper($_SESSION["uid"]), strToHex(mysql_aes_encrypt($current, MD5(strtoupper($_SESSION["uid"])))), 1);
	
	if($res) {
		if($current==$new || $current==$rnew){
			$_SESSION["change_failed"] = 4;		
			header("Location: ../change_password.php");
			exit;
		}	
		
		$_SESSION["change_failed"] = 3;
		
		$qry_update = "UPDATE
							tbl_user_account
						SET
							user_pass = :user_pass
						WHERE
							user_id = :user_id";
		
		try {
			$q = $db2->prepare($qry_update);
			
			$db2->beginTransaction();
			$q->execute(array(	':user_pass'=>strToHex(mysql_aes_encrypt($new, MD5($_SESSION["uid"]))),
								':user_id'=>$_SESSION["uid"]
							));
			$db2->commit();
							
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
			$new_expiration=new check_if_expire();
			$new_expiration->update_expiry();
			
			
			$update_email="UPDATE ".$tbl." SET  ".$col."=?  WHERE  ".$primary."=?";
			$prepare=$db2->prepare($update_email);
			if($prepare->execute(array($email,$_SESSION['uid']))){
				//send email		 
				sendFirstMsg($email,"CCN Vendor Portal");				
			}
			
			$db2 = null;
			
			 
			unset($_SESSION['change_pwd']);
			unset($_SESSION['id_expired']);
		}
		catch(PDOException $e) {
			$db2->rollback();
			$db2 = null;
		}
	}
	else {
		if($current==$new || $current==$rnew){
			$_SESSION["change_failed"] = 4;		
			header("Location: ../change_password.php");
			exit;
		}	
		$_SESSION["change_failed"] = 2;
	}
	
	header("Location: ../change_password.php");
	exit;
?>