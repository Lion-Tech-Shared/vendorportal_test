<?PHP

require "../../class-main/connection.class.php";
session_start();

$conn = new getConnection();
$db = $conn->PDO();

$flag=$_GET['flag'];


$prepare=$db->prepare("SELECT * FROM tbl_user_group_menu_sub WHERE group_id=? AND menu_id_sub=(SELECT menu_id_sub FROM  tbl_app_menu_sub  WHERE menu_dsc_sub=?)");
$prepare->execute(array($_SESSION['gid'],"Inventory"));	
$count=$prepare->fetchAll();
 
if(count($count) < 1){
	header("Location:../home.php");
}
$arr=array("STR","ITD");
if(in_array(substr($_SESSION['uid'],0,3),$arr)){
	$prepare=$db->prepare("SELECT a.*,b.descr FROM tbl_inventory a left join tbl_invmst b on a.sku=b.sku WHERE store=(SELECT store FROM tbl_user_info WHERE user_id=?) ORDER BY store asc");
	$prepare->execute(array($_SESSION['uid']));	
	
}
else{ 
	if($flag==1){
		$qry='SELECT a.store,a.sku,a.on_hand,a.lst_rec_date,b.descr FROM tbl_inventory a left join tbl_invmst b on a.sku=b.sku ORDER BY a.store asc';	
		$prepare=$db->prepare($qry);
		$prepare->execute();
	}
	else{
		$vendor_num=$_GET['vendor_num'];
		$store=$_GET['store'];
		//$qry='SELECT d.lvendr as vendor,a.store,a.sku,b.descr,sum(on_hand) as total,e.strnam FROM `tbl_inventory` a LEFT JOIN tbl_invmst b on a.sku=b.sku left join tbl_cssmst c on b.sub_dept=c.ccmnum left join tbl_lcsucn d on d.lcnvdr=c.ccmnum left join tbl_store_master e on e.strnum=a.store WHERE d.lvendr=? group by d.lvendr,a.store,a.sku';
		$qry='SELECT a.lvendr,e.strnam,D.store,D.sku,F.DESCR,D.ON_HAND AS total FROM TBL_LCSUCN A LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS LEFT JOIN TBL_INVENTORY D ON C.CCTLOC=D.STORE LEFT JOIN TBL_STORE_MASTER E ON E.STRNUM=D.STORE LEFT JOIN TBL_INVMST F ON F.SKU=D.SKU WHERE A.LVENDR=? AND D.store=? AND D.STORE is not null GROUP BY A.lvendr,D.store,D.sku';
		//D.STORE AS STORE,E.STRNAM AS STORE_NAME
		$prepare=$db->prepare($qry);
		$prepare->execute(array($vendor_num,$store));
	}
	 
}
$row=$prepare->fetchAll();
echo json_encode($row);
 

?>