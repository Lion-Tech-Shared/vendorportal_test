<?PHP
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set */
	
	if( !isset($_SESSION["uid"]) || !isset($_GET['flag']) ) {
    	header("Location:../home.php");
	} 
	 
	$conn = new getConnection();
	$db = $conn->PDO();
	$vendor=$_GET['vendor'];
	$sku=$_GET['sku'];
	 
	$qry='  SELECT * FROM tbl_inventory ';		
	$qry.=' WHERE vendor=? AND sku=? AND  store IN(SELECT lcstor FROM tbl_lcseclocpa WHERE lcuseid=?)';
	$qry.="   ";
	//$qry.=" )";	
	$prepare=$db->prepare($qry);
	$prepare->execute(array($vendor,$sku,$_SESSION['uid']));
	$fetch=$prepare->fetchAll();
	$on_hand=0;
	if(count($fetch) > 0){
		$on_hand=$fetch[0]['on_hand'];
	}

	echo $on_hand;
?>