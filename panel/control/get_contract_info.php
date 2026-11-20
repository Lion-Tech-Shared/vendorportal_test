<?php
require "../../class-main/connection.class.php";
session_start();

$contractnum=$_GET['contract_num'];

$conn = new getConnection();
$db = $conn->PDO();

 
$prepare=$db->prepare('SELECT a.cctloc as store,b.ccmnam as name,c.lvendr FROM tbl_cssmct a left join tbl_cssmst b on a.cctnum=b.ccmnum left join tbl_lcsucn c on c.lcnvdr=b.ccmnum WHERE a.cctnum='.$contractnum);
$prepare->execute();
$row=$prepare->fetchAll();

echo "Store     :     ".$row[0]["store"]." -- ".$row[0]["lvendr"]."        :        ".$row[0]["name"];
?>