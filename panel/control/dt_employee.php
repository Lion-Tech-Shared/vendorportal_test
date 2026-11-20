<?PHP
	require "../../class-main/connection.class.php";
	session_start();
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	switch($_GET["lctr"]) {
		case 1:
			$qry = "SELECT
						user_id, user_dtl
					FROM
						tbl_employee_master
					WHERE
						user_id not in (SELECT 
											user_id
										FROM
											tbl_user_info)
					AND
						user_id not in (SELECT 
											user_id
										FROM
											tbl_user_account)
					ORDER BY
						user_id";
						
			$res = $db->prepare($qry);
			$res->execute();
			$row = $res->fetchAll();
			
			echo json_encode($row);
			break;
		case 2:
			$qry = "SELECT a.user_id, a.user_pass, a.last_log_date, a.status,
							b.ee_name, b.ee_dept, b.ee_email_address, b.ee_reg_date, b.ee_exp_date
					FROM
						tbl_user_account a
					LEFT OUTER JOIN
						tbl_user_info b
					ON
						a.user_id = b.user_id
					WHERE
						a.group_id = 3
					ORDER BY
						a.user_id";
						
			$res = $db->prepare($qry);
			$res->execute();
			$row = $res->fetchAll();
			
			echo json_encode($row);
			break;
	}
?>