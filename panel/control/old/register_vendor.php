<?PHP
	require $_SERVER["DOCUMENT_ROOT"]."/supplierportal/class-main/connection.class.php";
	require $_SERVER["DOCUMENT_ROOT"]."/supplierportal/control/functions.php";
	date_default_timezone_set('Asia/Taipei');
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$qry = "INSERT INTO 
				tbl_vendor_info
			VALUES
				(:ven_id, :v_code, :v_reg_no, :user_id,
				:v_name, :v_principal_name, :v_street,
				:v_city, :v_country, :v_other_name,
				:v_contact_name, :v_land_line,
				:v_local_no, :v_mobile_no, :v_fax_no,
				:v_email, :v_principal_email,
				:v_reg_date, :v_exp_date, :v_ftp_site)";

	$q = $db->prepare($qry);

	try {
		$db->beginTransaction();
		$q->execute(array(':ven_id'=>"",
							':v_code'=>0,
							':v_reg_no'=>0,
							':user_id'=>0,
							':v_name'=>$_POST["v_name"],
							':v_principal_name'=>$_POST["v_principal_name"],
							':v_street'=>$_POST["v_street"],
							':v_city'=>$_POST["v_city"],
							':v_country'=>$_POST["v_country"],
							':v_other_name'=>$_POST["v_other_name"],
							':v_contact_name'=>$_POST["v_contact_name"],
							':v_land_line'=>$_POST["v_land_line"],
							':v_local_no'=>$_POST["v_local_no"],
							':v_mobile_no'=>$_POST["v_mobile_no"],
							':v_fax_no'=>$_POST["v_fax_no"],
							':v_email'=>$_POST["v_email"],
							':v_principal_email'=>$_POST["v_principal_email"],
							':v_reg_date'=>date('Y-m-d'),
							':v_exp_date'=>null,
							':v_ftp_site'=>$_POST["v_ftp_site"],
						));
		$db->commit();
		
		$db = null;
		
		header("Location: ../home.php?active=".md5(99));
	}
	catch(PDOExecption $e) {
		$db->rollback();
		
		$db = null;
		
		header("Location: ../home.php?active=".md5(100));
	}
?>