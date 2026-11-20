<?PHP
	require "../../class-main/connection.class.php";
	session_start();
	
	$conn = new getConnection();
	$db = $conn->PDO();

	$qry = "SELECT
				*
			FROM
				tbl_app_menu
			WHERE
				menu_dsc = '".$_POST["mdsc"]."' ";
				
	$res = $db->prepare($qry);
	$res->execute();
	$menuExist = $res->fetchAll();
	
	$qry_ins_menu = "INSERT INTO 
						tbl_app_menu
					VALUES
						(:menu_id, :menu_dsc, :menu_link, :menu_has_sub, :menu_ico)";
	
	if(count($menuExist) == 0) {
		try {
			$q = $db->prepare($qry_ins_menu);
			
			$db->beginTransaction();
			$q->execute(array(	':menu_id'=>"",
								':menu_dsc'=>$_POST["mdsc"],
								':menu_link'=>$_POST["mlink"],
								':menu_has_sub'=>($_POST["mlink"] == "#" ? true : false),
								':menu_ico'=>$_POST["mico"]
							));
			$db->commit();
		}
		catch(PDOExecption $e) {
			$db->rollback();
			
			$res->closeCursor();
			$db = null;
		}
	}
	
	header("Location: ../menu.php");
?>