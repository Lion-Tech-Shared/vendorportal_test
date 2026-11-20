<?PHP
	require "../../class-main/connection.class.php";
	session_start();
	
	$conn = new getConnection();
	$db = $conn->PDO();
	$vendor=$_GET['vendor'];
	$sku=$_GET['sku'];
	//$qry=" SELECT * FROM TBL_INVMST WHERE SKU IN(";
	$qry='SELECT a.descr,b.cost FROM tbl_invmst a';
	$qry.=' left join tbl_sku_cost b on a.sku=b.sku ';
	$qry.='     WHERE a.dept=990 AND a.sku=? AND a.sub_dept=(SELECT LCNVDR FROM tbl_lcsucn WHERE lvendr=?)';
	$qry.="          AND a.class>0 AND a.sub_class>0 ";
	//$qry.=" )";	
	$prepare=$db->prepare($qry);
	$prepare->execute(array($sku,$vendor));
	$fetch=$prepare->fetchAll();

	$array=array();
	$arr['count']=count($fetch);
	unset($_SESSION['sku_found']);
	unset($_SESSION['vendor']);
	unset($_SESSION['on_hand']);
	if(count($fetch)>0){
		//check on hand
		$qry='  SELECT * FROM tbl_inventory ';		
		$qry.=' WHERE vendor=? AND sku=? AND  store IN(SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid=?)';
		$qry.="   ";
		//$qry.=" )";	
		$prepare=$db->prepare($qry);
		$prepare->execute(array($vendor,$sku,$_SESSION['uid']));
		$fetchInv=$prepare->fetchAll();
		//end check onhand
		if(count($fetchInv) > 0){
			$arr['descr']=$fetch[0][0];
			$arr['cost']=$fetch[0][1];
			if($fetchInv[0]["on_hand"] > 0){				
				$arr['on_hand']=$fetchInv[0]["on_hand"];
				$_SESSION['sku_found']=$sku;
				$_SESSION['vendor']=$vendor;
				$_SESSION['on_hand']=$fetchInv[0]["on_hand"];
			}else{
				$arr['on_hand']=0;
			}
		}else{
			//$arr['count']=-2;
			$_SESSION['on_hand']=0;
			$arr['on_hand']=0;
		}		
	}
	
	array_push($array, $arr);

	echo json_encode($array);
?>