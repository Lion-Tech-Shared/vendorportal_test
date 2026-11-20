<?PHP
	require "../../class-main/PHPMailer-master/class.phpmailer.php";
	require "../../class-main/PHPMailer-master/class.smtp.php";
	require "../../class-main/connection.class.php";
	require "../../control/functions.php";
	//require "../../control/datetime.php";
	session_start();
	
	$vcodeArr = explode(" : ", $_POST["vcode"]);
	$vcode = $vcodeArr[0];
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$qry_sel = "SELECT user_id FROM
					tbl_user_account
				WHERE
					user_id = '".$vcode."'";
	
	$res = $db->query($qry_sel);
	$row = $res->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($row)) {
		$_SESSION["result"] = false;
		header("Location: ../failed.php");
		exit;
	}
	
	//$init_pass = generatePass($vcode);
	$init_pass = strtoupper($vcode);
	
	$qry_sel = "SELECT v_code, v_name, v_principal_code, v_street, v_city, v_contact_name, v_land_line, v_fax_no FROM
					tbl_vendor_master".($_POST["sbu"]=="SMR"?"Stage":"")."
				WHERE
					v_code = '".$vcode."'";
	
	$res = $db->query($qry_sel);
	$row = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$qry_ins_inf = "INSERT INTO 
						tbl_vendor_info
					VALUES
						(:v_count, :v_code, :v_name, :v_principal_code,
						:v_street, :v_city, :v_contact_name, :v_land_line,
						:v_fax_no, :v_email_address, :v_reg_date, :v_exp_date)";

	$qry_ins_acc = "INSERT INTO 
						tbl_user_account
					VALUES
						(:user_id, :user_pass, :group_id, :status, :date_created, :last_update, :last_log_date, :login_ip, :activated_by)";
						
	if(count($row)) {
		try {
			$q = $db->prepare($qry_ins_inf);
			
			$db->beginTransaction();
			$q->execute(array(	':v_count'=>"",
								':v_code'=>$row[0]["v_code"],
								':v_name'=>$row[0]["v_name"],
								':v_principal_code'=>$row[0]["v_principal_code"],
								':v_street'=>$row[0]["v_street"],
								':v_city'=>$row[0]["v_city"],
								':v_contact_name'=>$row[0]["v_contact_name"],
								':v_land_line'=>$row[0]["v_land_line"],
								':v_fax_no'=>$row[0]["v_fax_no"],
								':v_email_address'=>$_POST["email"],
								':v_reg_date'=>date('Y-m-d'),
								':v_exp_date'=>null
							));
			$db->commit();
			
			$q = $db->prepare($qry_ins_acc);
			
			$db->beginTransaction();
			$q->execute(array(	':user_id'=>$row[0]["v_code"],
								':user_pass'=>strToHex(mysql_aes_encrypt($init_pass, MD5($row[0]["v_code"]))),
								//':user_pass'=>$init_pass,
								':group_id'=>2,
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
				sendMail($_POST["email"], $row[0]["v_name"], $row[0]["v_code"], $init_pass);
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