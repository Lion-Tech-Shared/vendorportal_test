<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	$designer = new designPage();

	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}
		
	$page = "menu";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

	<?PHP echo $designer->pageHead(); ?>
</head>

<body>
	<?PHP echo $designer->pageNavi(); ?>
	
	<div class="container-fluid">
		
		<?PHP echo $designer->pageSideBar(); ?>
        
        <div class="col-sm-9">
			<div class="row-cont">
				<div class="row">
					<div class="col-lg-12">
						<h4 style="padding-left:20px;">Panel Menu Setup</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12" >
						<div class="panel panel-default">
							<div class="panel-body">
								<form class="form-horizontal" action="" method="post">
									<div class="form-group">
										<div class="col-xs-8 input-sm">
                                        	<div class="col-md-2">
                                        		<label class="control-label input-sm" for="mmenu">Menu:</label>
                                            </div>
											<div class="col-md-4">
												<select id="mmenu" class="form-control input-sm">
                                                	<?PHP
                                                    	$conn = new getConnection();
														$db = $conn->PDO();
														
														$qry = "SELECT * FROM tbl_app_menu ORDER BY menu_id";
																	
														$res = $db->prepare($qry);
														$res->execute();
														$menu = $res->fetchAll();
														
														if(count($menu) == 0) {
															echo '<option selected="selected" value="0">--No Menu--</option>';
														}
														
														for($i = 0; $i < count($menu); $i ++) {
															echo '<option '.($i == 0 ? 'selected="selected"' : '').' value="'.$menu[$i]["menu_id"].'">'.$menu[$i]["menu_dsc"].'</option>';
														}
													?>
												</select>
											</div>
                                            <div class="col-md-4">
												<button id="btnEdit" type="button" class="btn btn-primary btn-default btn-sm">
                                                	<i class="fa fa-pencil fa-fw"></i>
                                                </button>
											</div>
										</div>
									</div>
									<div class="panel panel-default">
                                        <div class="panel-heading">
                                            <i class="fa fa-check fa-fw"></i>
                                            Sub-menu
                                        </div>
                                        <div class="panel-body cont-smenu">
                                        </div>
                                    </div>
								</form>
							</div>
						</div>
					</div>
				</div>
                <?PHP echo $designer->pageDisclaimer(); ?>
			</div>
		</div>
	</div>
	
	<?PHP echo $designer->pageFoot($page); ?>
</body>

</html>