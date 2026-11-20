<?PHP
	require "../../class-main/connection.class.php";
	date_default_timezone_set('Asia/Taipei');
	session_start();
	
	$conn = new getConnection();
	$db = $conn->MySQLi();
	
	$db->query("UPDATE 
					tbl_disclaimer
				SET
					content = '".$_POST["content"]."',
					update_date = '".date("Y-m-d H:i:s")."',
					update_by = '".$_SESSION["uid"]."'");
	
	$_SESSION["saved_disc"] = true;
	
	header("Location: ../disclaimer.php");
?>