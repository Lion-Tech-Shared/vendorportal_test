<?php
require_once "../class-main/connection.class.php";

$con=new getConnection();
$db=$con->PDO();

$query="SELECT * FROM user_effectivity";
$prepare=$db->prepare($query);
$prepare->execute();

$fetch=$prepare->fetchAll();

echo $fetch[0]["months"];

?>