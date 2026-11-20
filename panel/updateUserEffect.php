 <?php
require_once "../class-main/connection.class.php";
$con=new getConnection();
$db=$con->PDO();
$months=$_GET['months'];

$query="UPDATE user_effectivity SET months=?,date_set=NOW()";
$prepare=$db->prepare($query);
$prepare->execute(array($months));


?>