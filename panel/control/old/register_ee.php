<?PHP
	require $_SERVER["DOCUMENT_ROOT"]."/supplierportal/class-main/connection.class.php";
	require $_SERVER["DOCUMENT_ROOT"]."/supplierportal/control/functions.php";
	date_default_timezone_set('Asia/Taipei');
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$qry = "SELECT * FROM
				tbl_user_info
			WHERE
				ee_no = ".$_POST["ee_no"];
				
	$res = $db->query($qry);
	$row = $res->fetchAll(PDO::FETCH_ASSOC);

	if(count($row)) {
		$row = $res->fetch(PDO::FETCH_ASSOC);
		
		$res->closeCursor();
		$db = null;
		
		header("Location: ../home.php?active=".md5(100));
	}
	else {
		$qry = "INSERT INTO 
					tbl_user_info
				VALUES
					(:ee_no, :user_id, :ee_reg_no, :ee_name, :ee_dept, :ee_email_address, :ee_reg_date, :ee_exp_date)";

		$q = $db->prepare($qry);

		try {
			$db->beginTransaction();
			$q->execute(array(':ee_no'=>$_POST["ee_no"],
								':user_id'=>0,
								':ee_reg_no'=>0,
								':ee_name'=>$_POST["ee_name"],
								':ee_dept'=>$_POST["ee_dept"],
								':ee_email_address'=>$_POST["ee_email_address"],
								':ee_reg_date'=>date('Y-m-d'),
								':ee_exp_date'=>null
							));
			$db->commit();
			
			$res->closeCursor();
			$db = null;
			
			header("Location: ../home.php?active=".md5(99));
		}
		catch(PDOExecption $e) {
			$db->rollback();
			
			$res->closeCursor();
			$db = null;
			
			header("Location: ../home.php?active=".md5(100));
		}
	}
?>