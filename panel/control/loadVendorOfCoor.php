<?php
require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

$store=$_POST['store'];
 
//$qry=" SELECT v_code,v_name FROM  tbl_vendor_master";

$qry2=" SELECT distinct a.lvendr as v_code,d.v_name FROM  tbl_lcsucn a ";
$qry2.=" left join tbl_cssmst b on a.lcnvdr=b.ccmnum ";
$qry2.=" left join tbl_cssmct c on b.ccmnum=c.cctcss ";
$qry2.=" left join tbl_vendor_master d on d.v_code=a.lvendr ";
//$qry2.=" left join tbl_store_master e on e.strnum=c.cctloc.lvendr ";
$qry2.=" WHERE c.cctloc=?";
//$qry2.=" AND date_format(curdate(),'%y%m%d') BETWEEN date(c.cctlsd) AND date(c.cctled)";
 

//$qry2=" SELECT v_code,v_name FROM  tbl_vendor_master WHERE v_code IN( SELECT lvendr FROM tbl_pa_vendor WHERE lcuseid=?) ";
$prepare=$db->prepare($qry2);
$prepare->execute(array($store));

$fetch=$prepare->fetchAll();

echo json_encode($fetch);
  