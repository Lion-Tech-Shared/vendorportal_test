<?php
$number=$_GET['total_amt'];
$return=number_format($number,2,".",",");
echo $return;
?>