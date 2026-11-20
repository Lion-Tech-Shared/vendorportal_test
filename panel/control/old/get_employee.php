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
?>