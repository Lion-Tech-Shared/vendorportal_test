<?PHP 
	require "../../class-main/connection.class.php";
	session_start();
	
	$conn = new getConnection();
	$db = $conn->PDO();

	$sbu = strtoupper($_GET['sbu']);
    $tbl = ( $sbu == 'DS'?"":"Stage");
	
	switch($_GET["lctr"]) {
		case 1: 
			$fromArr = explode("/", $_GET["from"]);
			$toArr = explode("/", $_GET["to"]);
			
			$from = (substr($fromArr[2], 2, 2).$fromArr[0].$fromArr[1]);
			$to = (substr($toArr[2], 2, 2).$toArr[0].$toArr[1]);
						
			$qry = "SELECT
						distinct c.strnum, c.strnam
					FROM
						tbl_ccn_sales_dtl a ";
						
			if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) {
				$qry .= "LEFT JOIN
							(select lcstor from tbl_lcsecloc".$tbl." where lcuseid = '".$_SESSION["uid"]."') b on a.cslloc = b.lcstor ";
			}
					
			$qry .= "LEFT JOIN
						tbl_store_master".$tbl." c on a.cslloc = c.strnum
					WHERE
						a.csldat BETWEEN ".$from." AND ".$to." ";
			
			if($_GET["sbu"]) {
				$qry .= "AND c.strsbu = '".$_GET["sbu"]."' ";
			}
			if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) {
				$qry .= "AND b.lcstor <> '' ";
			}
					
			$qry .=	"ORDER BY
						c.strnum";
			
			$res = $db->prepare($qry);
			$res->execute();
			$row = $res->fetchAll();
			
			echo json_encode($row);
			break;
		case 2:
			$fromArr = explode("/", $_GET["from"]);
			$toArr = explode("/", $_GET["to"]);
			
			$from = (substr($fromArr[2], 2, 2).$fromArr[0].$fromArr[1]);
			$to = (substr($toArr[2], 2, 2).$toArr[0].$toArr[1]);
						
			$qry = "SELECT
						distinct c.strnum, c.strnam
					FROM
						tbl_ccn_sales_dtl a 
					LEFT JOIN
						tbl_store_master".$tbl." c on a.cslloc = c.strnum
					LEFT JOIN
						tbl_lcsucn".$tbl." d on a.cslcss = d.lcnvdr
					WHERE
						a.csldat BETWEEN ".$from." AND ".$to."
					AND
						d.lvendr = ".$_GET["vcode"]." ";
			
			if($_GET["sbu"]) {
				$qry .= "AND c.strsbu IN('".$_GET["sbu"]."','HG') ";
			}
					
			$qry .=	"ORDER BY
						c.strnum";
			
			$res = $db->prepare($qry);
			$res->execute();
			$row = $res->fetchAll();
			
			echo json_encode($row);
			break;
	}
?>