<?PHP
require "../../class-main/connection.class.php";
session_start();
$sku=$_GET['sku'];

$qry="SELECT coalesce(cost,0.0000) as cost FROM tbl_sku_cost WHERE sku=?";
$conn = new getConnection();
$db = $conn->PDO();

$prepare=$db->prepare($qry);
$prepare->execute(array($sku));	 
$row=$prepare->fetchAll();
$arr=array();
if(count($row) > 0){
	$cost=number_format($row[0]["cost"],2);
	$costWithNoComma=number_format($row[0]["cost"],2,".","");
	$var['cost']=$cost;
	$var['costWithNoComma']=$costWithNoComma;
}else{
	$cost=number_format(0,2);
	$costWithNoComma=number_format(0,2,".","");
	$var['cost']=$cost;
	$var['costWithNoComma']=$costWithNoComma;
}
array_push($arr, $var);

echo json_encode($arr);

?>