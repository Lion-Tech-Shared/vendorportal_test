<?PHP
	$host = "192.168.10.19";
	$db_name = "MM4R6LIB";
	$username = "windss";
	$password = "winds1";   
	$conn_m = null;

	$conn_m = odbc_connect("DRIVER=Client Access ODBC Driver (32-bit);System=".$host.";DBQ=".$db_name, $username, $password);
	if($conn_m == null){
		die('Could not connect: ' . odbc_errormsg());
		exit;
	}
	
	$host = "localhost";
	$db_name = "supplier_portal";
	$username = "root";
	$password = "execadmin08";
	$conn = null;

	try{
		$conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
	}catch(PDOException $exception){
		echo "Connection error: " . $exception->getMessage();
	}
	
	$conn->exec("DELETE FROM tbl_ccn_sales_dtl");
	
	$qry_m = "SELECT cslcss, cslcct, cslloc, csldat, cslsku, cslamt,
			csldis, csltax, csramt, csgamt
			FROM CSSCSL
			ORDER BY cslloc, csldat";
			//FETCH FIRST ROWS ONLY";
	
	$stmt = odbc_prepare($conn_m, $qry_m);
	odbc_execute($stmt);
	while(odbc_fetch_row($stmt)){
		
		$qry = "INSERT INTO tbl_ccn_sales
				VALUES(:cslcss, :cslcct, :cslloc, :csldat, :cslsku, :cslamt, :csldis, :csltax, :csramt, :csgamt)";
				
		$q = $conn->prepare($qry);
		
		/*$q->execute(array(':cslcss'=>odbc_result($stmt, "cslcss"),
						':cslcct'=>odbc_result($stmt, "cslcct"),
						':cslloc'=>odbc_result($stmt, "cslloc"),
						':csldat'=>odbc_result($stmt, "csldat"),
						':cslsku'=>odbc_result($stmt, "cslsku"),
						':cslamt'=>odbc_result($stmt, "cslamt"),
						':csldis'=>odbc_result($stmt, "csldis"),
						':csltax'=>odbc_result($stmt, "csltax"),
						':csramt'=>odbc_result($stmt, "csramt"),
						':csgamt'=>odbc_result($stmt, "csgamt")
					));*/
	}
?>