<?PHP
	include "../class-main/connection.class.php";
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	# create query to get system date and time
	$query = "SELECT TIME(NOW()) AS cur_time, DATE(NOW()) AS cur_date, NOW( ) AS cur_datetime";
	$res = $db->query($query);
	$row = $res->fetch(PDO::FETCH_ASSOC);
	
	# system time HH:MM:SS format
	$SYSTEM_TIME = $row['cur_time'];
	
	# system date YYYY-MM-DD format
	$SYSTEM_DATE = $row['cur_date'];
	
	# system datetime YYYY-MM-DD HH:MM:SS format
	$SYSTEM_DATETIME = $row['cur_datetime'];
	
	# system time and date use to compute new vendor and employee password
	$systime = date("hi-A", strtotime($SYSTEM_TIME));
	$sysdate = date("mdy", strtotime($SYSTEM_DATE));
?>