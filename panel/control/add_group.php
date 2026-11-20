<?PHP
	require "../../class-main/connection.class.php";
	session_start();
	
	$conn = new getConnection();
	$db = $conn->PDO();

	$qry = "SELECT
				*
			FROM
				tbl_user_group
			WHERE
				group_code = '".$_POST["gname"]."' ";
				
	$res = $db->prepare($qry);
	$res->execute();
	$groupExist = $res->fetchAll();
	
	if(count($groupExist) == 0) {
		$qry_ins_menu = "INSERT INTO 
							tbl_user_group
						VALUES
							(:group_id, :group_code, :group_dsc)";
							
		try {
			$q = $db->prepare($qry_ins_menu);
			
			$db->beginTransaction();
			$q->execute(array(	':group_id'=>"",
								':group_code'=>$_POST["gname"],
								':group_dsc'=>$_POST["gdsc"]
							));
			$db->commit();
		}
		catch(PDOExecption $e) {
			$db->rollback();
			
			$res->closeCursor();
			$db = null;
		}
	}
	
	header("Location: ../user_group.php");
?>