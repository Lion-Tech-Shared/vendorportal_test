<?PHP
	require "../class-main/connection.class.php";
	require "../control/mysqli_function.php";
	require "../control/functions.php";
	require_once "../check_attempts.php";
	require_once "../getIp.php";
	date_default_timezone_set('Asia/Taipei');
	session_start();	
	//For captcha
	require_once "../securimage/securimage.php";
	$securimage=new Securimage();
	
	//db connection
	$conn = new getConnection();
	//$db = $conn->MySQLi(); comment by uls
	$db=$conn->PDO();
	
	$getip=new getIp();
	$ip=$getip->get_ip();
	
	$login_attempts=new checkAttempts();
	
	//Get form variables 
	$uid = trim(strtoupper($_POST["uid"]));
	$pwd = trim(strtoupper($_POST["pwd"]));
	/*$res = SQL("SELECT user_id, group_id FROM tbl_user_account WHERE user_id = ? and user_pass = ? limit ?",$uid,strToHex(mysql_aes_encrypt($pwd,MD5($uid))), 1); comment by uls*/
	//CHECK IF ACCOUNT IS LOCKED
	$check_if_locked="SELECT * FROM login_attempts WHERE userid=?";
	$pre=$db->prepare($check_if_locked);
	$pre->execute(array($uid));
	$fetch=$pre->fetchAll();
	
	if( count($fetch) > 0 ){		
			
		$current_date=date('Y-m-d H:i:s');
		$lock_date=$fetch[0]["datetime"];
		 		 
		$dt=new DateTime($current_date);
		$dt2=new DateTime($lock_date);	 
		$day_diff=$dt->diff($dt2)->d;
		$minutes_diff=$dt->diff($dt2)->i;
		$hours_diff=$dt->diff($dt2)->h;
		if( $day_diff > 0 || $hours_diff >= 1  || $minutes_diff > 60 ){ 
			$clear_attempts="DELETE FROM login_attempts WHERE userid=?";
			$prepare_clear=$db->prepare($clear_attempts);
			$prepare_clear->execute(array($uid));
			unset($_SESSION['msg']);
		}else{
			$_SESSION['msg']=$minutes_diff;	
			//$_SESSION['diff']='day'.$day_diff.'  minutes:'.$minutes_diff;				
		}
	}else{
		unset($_SESSION['msg']);	
	}
	
	if(!isset($_SESSION['msg'])){	
		$res = $db->prepare("SELECT user_id,group_id,status FROM tbl_user_account WHERE user_id = ? and user_pass = ?  limit 1 ");					 
		$res->execute(array($uid, strToHex(mysql_aes_encrypt($pwd, MD5($uid)))));			
		$res_fetch=$res->fetchAll();		 		
		
		if(count($res_fetch)>0) {			
			
			if($securimage->check($_POST['captcha_code'])==false){
				$_SESSION['error_captcha']=true;			
			}else{
				if($res_fetch[0]["status"] <> 1){
					$_SESSION['status']='Disabled';
					header("Location:../home.php");	
					exit;
				}
				if($res_fetch[0]["group_id"] == 2) {
				$qry = "SELECT
							v_email_address as eml
							FROM
								tbl_vendor_info
							WHERE
								v_code = ? ";
				}
				else {
					$qry = "SELECT
								ee_email_address as eml
							FROM
								tbl_user_info
							WHERE
								user_id = ? ";
				}
				$row=$db->prepare($qry);
				$row->execute(array($res_fetch[0]["user_id"]));
				$row_fetch=$row->fetchAll();
				//comment by uls
				//$row = SQL($qry, $res[0]["user_id"]);
				/*$db->query("UPDATE 	tbl_user_account
							SET last_log_date = '".date("Y-m-d H:i:s")."',login_ip = '".$_SERVER['REMOTE_ADDR']."'
							WHERE 	user_id = '".$res[0]["user_id"]."'");*/
				$update=$db->prepare("UPDATE 
								tbl_user_account
							SET
								last_log_date = ?,
								login_ip = ?
							WHERE
								user_id = ?");
				$update->execute(array(date("Y-m-d H:i:s"),$ip,$res_fetch[0]["user_id"]));				
				
				$_SESSION["passed"] = true;
				$_SESSION["uid"] = $res_fetch[0]["user_id"];
				$_SESSION["gid"] = $res_fetch[0]["group_id"];
				$_SESSION["eml"] = $row_fetch[0]["eml"];			
				if(strtoupper($uid)==$pwd)
					$_SESSION['change_pwd']=true;
			}
	
			
		}
		else {
			
			$_SESSION["passed"] = false;
			$_SESSION["uid"] = $uid;
			$_SESSION["pwd"] = $pwd;									
			
			//track login attempts 
			$login_attempts->check_attempts($uid,$ip,$db);
		}
		
		/*echo strtoupper(strToHex(mysql_aes_encrypt($pwd, MD5($uid))));
		echo '<br/>';
		echo fnDecrypt($pwd, MD5($uid));*/
	}
	header("Location: ../home.php");
?>
