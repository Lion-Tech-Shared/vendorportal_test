<?PHP
	require "../../class-main/connection.class.php";
	session_start();
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
	$canReceive=$fetch[0]['canReceive'];

	/* Create Transfer Number */ 
	if($canReceive==1){
		$qry = "SELECT 
						a.lvendr as v_code, b.v_name
					FROM
						tbl_pa_vendor a
					LEFT JOIN 
						tbl_vendor_master b 
					ON
						a.lvendr=b.v_code	
					WHERE
						a.lcuseid=?	
				";
						
		$res = $db->prepare($qry);
		$res->execute(array($_SESSION['uid']));
		$row = $res->fetchAll();
		
		echo json_encode($row);
		exit;

	}

	switch($_GET["lctr"]) {
		case 1:
			$qry = "SELECT 
						v_code, v_name
					FROM
						tbl_vendor_master".($_GET["sbu"]=="SMR"?"Stage":"")."   
					WHERE
						v_code not in (SELECT 
											user_id
										FROM
											tbl_user_account)
					AND
						v_code not in (SELECT 
											v_code
										FROM
											tbl_vendor_info)";
						
			$res = $db->prepare($qry);
			$res->execute();
			$row = $res->fetchAll();
			
			echo json_encode($row);
			break;
		case 2:
			$qry = "SELECT
							a.user_id, a.user_pass, a.last_log_date, a.status,
							b.v_name, c.v_name as principal, b.v_contact_name, b.v_land_line, b.v_fax_no,
							b.v_email_address, b.v_reg_date, b.v_exp_date
					FROM
						tbl_user_account a
					LEFT OUTER JOIN
						tbl_vendor_info b
					ON
						a.user_id = b.v_code
					LEFT OUTER JOIN
						tbl_vendor_info c
					ON
						b.v_principal_code = c.v_code
					WHERE
						a.group_id = 2
					ORDER BY
						a.user_id";
						
			$res = $db->prepare($qry);
			$res->execute();
			$row = $res->fetchAll();
			
			echo json_encode($row);
			break;
		case 3:
			$fromArr = explode("/", $_GET["from"]);
			$toArr = explode("/", $_GET["to"]);
			
			$from = (substr($fromArr[2], 2, 2).$fromArr[0].$fromArr[1]);
			$to = (substr($toArr[2], 2, 2).$toArr[0].$toArr[1]);
			//echo "g_id:".$_SESSION["gid"]."-uid:".$_SESSION["uid"]."-sbu:".$_GET["sbu"];
			//exit;			
			//mysql_query(" SET CHARACTER SET utf8  ");
			$qry = "SELECT
						distinct d.v_code, d.v_name
					FROM
						tbl_ccn_sales_dtl a ";
						
			if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) {
				$qry .= "LEFT JOIN
							(select lcstor from tbl_lcsecloc where lcuseid = '".$_SESSION["uid"]."') b on a.cslloc = b.lcstor ";
			}		

			$qry .= "LEFT JOIN
						tbl_lcsucn c on a.cslcss = c.lcnvdr
					LEFT JOIN
						tbl_vendor_master d on c.lvendr = d.v_code
					LEFT JOIN
						tbl_store_master e on a.cslloc = e.strnum
					WHERE
						a.csldat BETWEEN ".$from." AND ".$to." 
					AND
						d.v_code is not NULL ";
			
			if($_GET["sbu"]) {
				$qry .= "AND e.strsbu = '".$_GET["sbu"]."' ";
			}
			
			if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) {
				//$qry .= "AND b.lcstor <> '' ";
			}
			$qry = "SELECT
						distinct v_code, v_name
					FROM tbl_vendor_master".($_GET["sbu"]=="SMR"?"Stage":"")."  ";
			$qry .=	"ORDER BY
						v_code ";
			
			$res = $db->prepare($qry);
			$res->execute();
			$row = $res->fetchAll();			
			//$row=utf8_encode($row)
			echo json_encode($row);
			break;
	}
?>