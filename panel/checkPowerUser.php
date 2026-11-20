<?php
 
$array=array(1,3);

if(isset($_SESSION["uid"]) && !in_array($_SESSION['gid'],$array)){
	header("Location: ../home.php");
}

?>