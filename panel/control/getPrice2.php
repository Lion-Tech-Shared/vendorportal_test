<?PHP
require "../../class-main/connection.class.php";
session_start();
//$sku=$_GET['sku'];


$conn = new getConnection();
$db = $conn->PDO();


$arr=array();

foreach ($_GET['multArrSku'] as $key => $value) {
 	$qry="SELECT cost FROM tbl_sku_cost WHERE sku=?";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($value[0]));	 
	$row=$prepare->fetchAll();

	echo $row;
	exit;
	$var['sku']=$value[0];	
	$var['skuDesc']=$value[1];	
	$var['stockNum']=$value[2];
	$var['skuQty']=$value[3];
	if(count($row) > 0){
		$cost=$row[0]["cost"];
		$costWithNoComma=$row[0]["cost"];
		$var['cost']=$cost;
		$var['costWithNoComma']=$costWithNoComma;
	}else{
		$cost=0;
		$costWithNoComma=0;
		$var['cost']=$cost;
		$var['costWithNoComma']=$costWithNoComma;
	}
	array_push($arr, $var);
}


echo json_encode($arr);

?>