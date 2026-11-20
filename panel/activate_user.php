<?PHP
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "checkPowerUser.php"; 
	require_once "control/redirect.php";
	$designer = new designPage();
	$toHome=new link_2_change_pwd();
	$toHome->checkSession(); 

	 	
	$page = "activate_employee";
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
		<?PHP echo $designer->pageBelowHeader(); ?>
		<?PHP echo $designer->pageSideBar(); ?>
		
		<div class="col-sm-9">
			<div class="row-cont">
				<div class="row">
					<div class="col-lg-12">
						<h4 style="padding-left:20px;">Activate User Account</h4>
					</div>
				</div>	
				<div class="row">
					<div class="col-lg-12" >
						<div class="panel panel-default">
							<div class="panel-body">
								<form id="frm_actvEmp" class="form-horizontal" action="control/activate_employee.php" method="post">
									<p class="text-muted">Kindly fill-up all required fields (<font style="color:red;">*</font>) or click lookup button to choose from the list and click "Activate" to generate username and password.</p>
									<hr />
									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="uid">User ID:<font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="uid" type="text" class="form-control input-sm" placeholder="" name="uid" value="" autofocus required>
												<span class="input-group-btn">
													<button id="btnlu" name="btnlu" class="btn btn-primary input-sm" type="button" title="Lookup">
														<i class="fa fa-search fa-fw"></i>
													</button>
												</span>
											</div>
										</div>
									</div>
                                    <div class="form-group">
										<label class="control-label col-md-3 input-sm" for="group">User Group:</label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<select id="group" name="group" class="form-control input-sm">
													<?PHP
                                                    	$conn = new getConnection();
														$db = $conn->PDO();
														
														$qry = "SELECT a.group_id, a.group_code FROM tbl_user_group a left join  tbl_privileges b on a.group_id=b.gid ";
														$qry.=" WHERE a.group_id not in (".($_SESSION["gid"] == 1 ? "" : "1,")."2) ";
														$qry.=" AND b.isLocked=?";
														
														$res = $db->prepare($qry);
														$res->execute(array(0));
														$row = $res->fetchAll();
														echo '<option selected="selected"></option>';
														for($i = 0; $i < count($row); $i ++) {
															echo '<option value="'.$row[$i]["group_id"].'">'.$row[$i]["group_code"].'</option>';
														}
														
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="email">User Email:</label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="email" type="text" class="form-control input-sm" placeholder="optional" name="email" value="" onChange="">
											</div>
										</div>
									</div>
									<hr />
									<div class="text-right">
										<button id="btn_activate" name="" type="button" class="btn btn-primary btn-default">
											<i class="fa fa-check fa-fw"></i>
											<strong>Activate</strong>
										</button>
									</div>
								</form>
								<div id="auto"></div>
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