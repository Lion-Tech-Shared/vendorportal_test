<?PHP
	require "../../class-main/connection.class.php";
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$qry = "SELECT
				*
			FROM
				tbl_app_menu_sub
			WHERE
				menu_id = ".$_GET["mmenu"]."
			ORDER BY
				menu_arrange_sub";
	
	$res = $db->prepare($qry);
	$res->execute();
	$smenu = $res->fetchAll();
	
	$out = '';
	
	if(count($smenu) == 0) {
		$qry = "SELECT
					*
				FROM
					tbl_app_menu
				WHERE
					menu_id = ".$_GET["mmenu"]." ";
		
		$res = $db->prepare($qry);
		$res->execute();
		$mmenu = $res->fetchAll();
		
		if($mmenu[0]["menu_has_sub"]) {
			echo json_encode('<center><strong>Menu does not have sub-menu.</strong></center>
							 <ul class="ul-smenu">
								<li>
									<div class="col col-md-12">
										<br/>
										<button type="button" onclick="btnAddSubClicked()" class="btn btn-primary btn-block btn-sm">
											<i class="fa fa-plus fa-fw"></i>
											Add Sub-menu
										</button>
									</div>
								</li>
							</ul>');
		}
		else {
			echo json_encode("<center><strong>Menu does not have sub-menu.</strong></center>");
		}
		exit;
	}
	
	$out .= 	'<ul class="ul-smenu">';
	
	for($i = 0; $i < count($smenu); $i ++) {
		
		$out .= 	'<li>
						<div class="col col-md-2">
							<select id="sub-arrange-'.$smenu[$i]["menu_id_sub"].'">';
		
		for($j = 0; $j < count($smenu); $j ++) {
			$out .= 			'<option value="'.($j + 1).'" '.(($j + 1) == $smenu[$i]["menu_arrange_sub"] ? 'selected="selected"' : '').'>'.($j + 1).'</option>';
		}
		
		$out .= 			'</select>
						</div>
						<div class="col col-md-6">
							<strong>'.$smenu[$i]["menu_dsc_sub"].'</strong>
						</div>
						<div class="col col-md-4">
							<div id="del-sub-'.$smenu[$i]["menu_id_sub"].'" style="display:inline; float:right; margin-left:10px;">
								<a href="#" >
									<i class="fa fa-trash fa-fw"></i>
									Delete
								</a>
							</div>
							<div id="edit-sub-'.$smenu[$i]["menu_id_sub"].'" style="display:inline; float:right; margin-left:10px;">
								<a href="#" >
									<i class="fa fa-pencil fa-fw"></i>
									Edit
								</a>
							</div>
						</div>
					</li>';
	}
	
	$out .= 		'<li>
						<div class="col col-md-6">
							<br/>
							<button type="button" class="btn btn-primary btn-block btn-sm">
								<i class="fa fa-save fa-fw"></i>
								Save
							</button>
						</div>
						<div class="col col-md-6">
							<br/>
							<button type="button" onclick="btnAddSubClicked()" class="btn btn-primary btn-block btn-sm">
								<i class="fa fa-plus fa-fw"></i>
								Add Sub-menu
							</button>
						</div>
					</li>
				</ul>';
	
	echo json_encode($out);
?>