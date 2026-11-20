<?PHP
	require "../../class-main/PHPMailer-master/class.phpmailer.php";
	require "../../class-main/PHPMailer-master/class.smtp.php";
	require "../../class-main/connection.class.php";
	require "../../control/functions.php";
	//require "../../control/datetime.php";
	session_start();
	
	$uidArr = explode(" : ", $_POST["uid"]);
	$uid = trim($uidArr[0]);
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$qry_sel = "SELECT user_id FROM
					tbl_user_account
				WHERE
					user_id = '".$uid."'";
	
	$res = $db->query($qry_sel);
	$row = $res->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($row) > 0) {
		$_SESSION["result"] = false;
		header("Location: ../failed.php");
		exit;
	}
	
	//$init_pass = generatePass($uid);
	$init_pass = trim(strtoupper($uid));
	
	$qry_sel = "SELECT user_id, user_dtl FROM
					tbl_employee_master
				WHERE
					user_id = '".$uid."'";
	
	$res = $db->query($qry_sel);
	$row = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$qry_ins_inf = "INSERT INTO
						tbl_user_info
					VALUES
						(:user_count, :user_id, :ee_name, :ee_dept, :ee_email_address, :ee_reg_date, :ee_exp_date , :store)";

	$qry_ins_acc = "INSERT INTO
						tbl_user_account
					VALUES
						(:user_id, :user_pass, :group_id, :status, :date_created, :last_update, :last_log_date, :login_ip, :activated_by)";
	//echo trim($row[0]["user_id"])."-".md5(trim($row[0]["user_id"]))."<br>";
	//echo $init_pass."-".md5($init_pass)."<br>";
	//echo strToHex(mysql_aes_encrypt($init_pass, MD5(strtoupper($row[0]["user_id"]))));
	//echo strToHex(mysql_aes_encrypt($init_pass, MD5(strtoupper($row[0]["user_id"]))));

	//echo "<br>".strToHex(mysql_aes_encrypt($init_pass,MD5(trim($row[0]["user_id"]))));
	//exit;
						
	if(count($row) > 0) {
		try {
			$info = explode("-", $row[0]["user_dtl"]);
			$store = explode("--", $row[0]["user_dtl"]);
			
			if(count($store) < 2)
				$store='';

			if(count($info) < 2) {
				$name = trim($info[0], " ");
				$dept = "";
			}
			else {
				$name = trim($info[0], " ");
				$dept = trim($info[1], " ");
			}
			
			$q = $db->prepare($qry_ins_inf);
			
			$db->beginTransaction();
			$q->execute(array(	':user_count'=>"",
								':user_id'=>strtoupper($row[0]["user_id"]),
								':ee_name'=>$name,
								':ee_dept'=>$dept,
								':ee_email_address'=>$_POST["email"],
								':ee_reg_date'=>date('Y-m-d'),
								':ee_exp_date'=>null,
								':store'=>$store[1]
							));
			$db->commit();
			
			$q = $db->prepare($qry_ins_acc);
			
			$db->beginTransaction();
			$q->execute(array(	':user_id'=>trim(strtoupper($row[0]["user_id"])),
								':user_pass'=>strToHex(mysql_aes_encrypt($init_pass, MD5(trim(strtoupper($row[0]["user_id"]))))),
								//':user_pass'=>$init_pass,
								':group_id'=>$_POST["group"],
								':status'=>true,
								':date_created'=>date('Y-m-d'),
								':last_update'=>null,
								':last_log_date'=>null,
								':login_ip'=>null,
								':activated_by'=>$_SESSION["uid"]
							));
			$db->commit();
			
			$res->closeCursor();
			$db = null;
			
			if($_POST["email"]) {
				//send mail for user access	
				sendMail($_POST["email"], $name, $row[0]["user_id"], $init_pass);
			}
			
			$_SESSION["result"] = true;
			header("Location: ../activated.php");
		}
		catch(PDOExecption $e) {
			$db->rollback();
			
			$res->closeCursor();
			$db = null;
			
			$_SESSION["result"] = false;
			header("Location: ../failed.php");
		}
	}
	else {
		$_SESSION["result"] = false;
		header("Location: ../failed.php");
	}
?>