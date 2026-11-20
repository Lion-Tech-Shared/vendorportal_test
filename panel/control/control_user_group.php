<?PHP
	require "../../class-main/connection.class.php";

	$conn = new getConnection();
	$db = $conn->PDO();
	
	switch($_GET["lctr"]) {
		case 1:	
			$qry = "SELECT
						*
					FROM
						`tbl_app_menu`
					ORDER BY
						menu_arrange";
			
			$res = $db->prepare($qry);
			$res->execute();
			$mmenu = $res->fetchAll();
			
			$out = '<ul class="ul-mmenu">';
			
			for($i = 0; $i < count($mmenu); $i ++) {
				
				$qry = "SELECT
							count(0) as cnt
						FROM
							`tbl_user_group_menu` 
						WHERE
							group_id = ".$_GET['group']."  
						AND
							menu_id = ".$mmenu[$i]['menu_id']." ";
				
				$res = $db->prepare($qry);
				$res->execute();
				$menu_has = $res->fetchAll();
				
				$out .= 	'<li>
								<label class="checkbox-inline" style=""><input class="mmenu-class" id="menu-'.$mmenu[$i]["menu_id"].'" style="" type="checkbox" '.($menu_has[0]["cnt"] > 0 ? 'checked="checked"' : '').' '.($mmenu[$i]["menu_has_sub"] ? 'disabled="disabled' : '').' "><strong>'.$mmenu[$i]["menu_dsc"].'</strong></label>
							</li>';
						
				if($mmenu[$i]["menu_has_sub"]) {
					$qry = "SELECT
								*
							FROM
								`tbl_app_menu_sub`
							WHERE
								menu_id = ".$mmenu[$i]["menu_id"]."
							ORDER BY
								menu_arrange_sub";
					
					$res = $db->prepare($qry);
					$res->execute();
					$smenu = $res->fetchAll();
					
					$out .= '<li>
								<ul class="ul-smenu">';
					
					if(count($smenu) == 0) {
						$out .= 	'<li>
										No sub menu yet. Go to <strong>"Maintenace > Panel Menu"</strong> to add. 
									</li>';
					}
					else {
						for($j = 0; $j < count($smenu);$j ++) {
							$qry = "SELECT
										count(0) as cnt
									FROM
										`tbl_user_group_menu_sub`
									WHERE
										group_id = ".$_GET["group"]."
									AND
										menu_id_sub = ".$smenu[$j]["menu_id_sub"]." ";
							
							$res = $db->prepare($qry);
							$res->execute();
							$smenu_has = $res->fetchAll();
							
							$out .= '<li>
										<label class="checkbox-inline" style=""><input onchange="cbAction(this.id)" class="smenu" id="menu-'.$mmenu[$i]["menu_id"].'-'.$smenu[$j]["menu_id_sub"].'" style="" type="checkbox" '.($smenu_has[0]["cnt"] > 0 ? 'checked="checked"' : '').'>'.$smenu[$j]["menu_dsc_sub"].'</label>
									</li>';
						}
						
					}
					
					$out .= 	'</ul>
							</li>';
				}
			}
			$out .= '</ul>';
			
			echo json_encode($out);
			break;
		case 2:
			$qry = "SELECT
						*
					FROM
						`tbl_app_menu_sub`
					WHERE
						menu_id = ".$_GET["mmenu"]."
					ORDER BY
						menu_arrange_sub";
			
			$res = $db->prepare($qry);
			$res->execute();
			$mmenu = $res->fetchAll();
			
			echo json_encode($mmenu);
			break;
		case 3:
			$out = "";
			
			$qry = "SELECT
						menu_id
					FROM
						`tbl_app_menu`
					ORDER BY
						menu_arrange";
			
			$res = $db->prepare($qry);
			$res->execute();
			$mmenu = $res->fetchAll();
			
			for($i = 0; $i < count($mmenu); $i ++) {
				if($i > 0) {
					$out .= "|";
				}
				
				$out .= $mmenu[$i]["menu_id"];
				
				$qry = "SELECT
							menu_id_sub
						FROM
							`tbl_app_menu_sub`
						WHERE
							menu_id = ".$mmenu[$i]["menu_id"]."
						ORDER BY
							menu_arrange_sub";
				
				$res = $db->prepare($qry);
				$res->execute();
				$smenu = $res->fetchAll();
				
				for($j = 0; $j < count($smenu); $j ++) {				
					$out .= "|".$mmenu[$i]["menu_id"]."-".$smenu[$j]["menu_id_sub"];
				}
			}
			
			echo json_encode($out);
			break;
		case 4:
			$qry_del = "DELETE FROM
							`tbl_user_group_menu`
						WHERE
							group_id = :group_id ";
			
			try {
				$q = $db->prepare($qry_del);
				
				$db->beginTransaction();
				
				$q->execute(array(':group_id'=>$_GET["gid"]));
				$db->commit();
			}
			catch(PDOExecption $e) {
				$db->rollback();
				
				$res->closeCursor();
				$db = null;
				
				echo json_encode(false);
				exit;
			}
			
			echo json_encode(true);
			break;
		case 5:
			$qry_del = "DELETE FROM
							`tbl_user_group_menu_sub`
						WHERE
							group_id = :group_id ";
			
			try {
				$q = $db->prepare($qry_del);
				
				$db->beginTransaction();
				
				$q->execute(array(':group_id'=>$_GET["gid"]));
				$db->commit();
			}
			catch(PDOExecption $e) {
				$db->rollback();
				
				$res->closeCursor();
				$db = null;
				
				echo json_encode(false);
				exit;
			}
			
			echo json_encode(true);
			break;
		case 6:			
			$qry_ins = "INSERT INTO
							tbl_user_group_menu
						VALUES
							(:group_id, :menu_id)";
			
			try {
				$q = $db->prepare($qry_ins);
		
				$db->beginTransaction();
				$q->execute(array(	':group_id'=>$_GET["gid"],
									':menu_id'=>$_GET["mmenu"]
								));
				$db->commit();
			}
			catch(PDOExecption $e) {
				$db->rollback();
				
				$res->closeCursor();
				$db = null;
				
				echo json_encode(false);
				exit;
			}
			
			echo json_encode(true);
			break;
		case 7:
			$qry_ins = "INSERT INTO
							tbl_user_group_menu_sub
						VALUES
							(:group_id, :menu_id_sub)";
			
			try {
				$q = $db->prepare($qry_ins);
		
				$db->beginTransaction();
				$q->execute(array(	':group_id'=>$_GET["gid"],
									':menu_id_sub'=>$_GET["smenu"]
								));
				$db->commit();
			}
			catch(PDOExecption $e) {
				$db->rollback();
				
				$res->closeCursor();
				$db = null;
				
				echo json_encode(false);
				exit;
			}
			
			echo json_encode($_GET["smenu"]);
			break;
		
		/*wrong*/
		case 8:
			$qry = "SELECT
						menu_id_sub
					FROM
						`tbl_app_menu_sub`
					WHERE
						menu_id = ".$_GET["mmenu"]."
					ORDER BY
						menu_arrange_sub";
			
			$res = $db->prepare($qry);
			$res->execute();
			$smenu = $res->fetchAll();
			
			for($j = 0; $j < count($smenu); $j ++) {				
				$qry_del = "DELETE FROM
								`tbl_user_group_menu_sub`
							WHERE
								menu_id_sub = :menu_id_sub ";
				
				try {
					$q = $db->prepare($qry_del);
					
					$db->beginTransaction();
					
					$q->execute(array(':menu_id_sub'=>$smenu[$j]["menu_id_sub"]));
					$db->commit();
				}
				catch(PDOExecption $e) {
					$db->rollback();
					
					$res->closeCursor();
					$db = null;
					
					echo json_encode("not ok 1");
					exit;
				}
				
				$qry_del = "DELETE FROM
								`tbl_app_menu_sub`
							WHERE
								menu_id_sub = :menu_id_sub ";
				
				try {
					$q = $db->prepare($qry_del);
					
					$db->beginTransaction();
					
					$q->execute(array(':menu_id_sub'=>$smenu[$j]["menu_id_sub"]));
					$db->commit();
				}
				catch(PDOExecption $e) {
					$db->rollback();
					
					$res->closeCursor();
					$db = null;
					
					echo json_encode("not ok 1");
					exit;
				}
			}
			
			$qry_del = "DELETE FROM
							`tbl_app_menu`
						WHERE
							menu_id = :menu_id ";
			
			try {
				$q = $db->prepare($qry_del);
				
				$db->beginTransaction();
				
				$q->execute(array(':menu_id'=>$_GET["mmenu"]));
				$db->commit();
			}
			catch(PDOExecption $e) {
				$db->rollback();
				
				$res->closeCursor();
				$db = null;
				
				echo json_encode("not ok 1");
				exit;
			}
			
			echo json_encode("ok");
			break;
	}
?>