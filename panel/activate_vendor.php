<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "checkPowerUser.php"; 
	require_once "control/redirect.php";
	$designer = new designPage();
	$toHome = new link_2_change_pwd();
	$toHome->checkSession(); 

	$page = "activate_vendor";

	if(!isset($_SESSION['uid'])){
		header("Location:../home.php");
	}
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
						<h4 style="padding-left:20px;">Activate Vendor Account</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12" >
						<div class="panel panel-default">
							<div class="panel-body">
								<form class="form-horizontal" action="control/activate_vendor.php" method="post">
									<p class="text-muted">Kindly fill-up all required fields (<font style="color:red;">*</font>) or click lookup button to choose from the list and click "Activate" to generate username and password.</p>
									<hr />
									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="sbu">Business Unit:</label>
											<div class="col-md-7 input-sm">
												<div class="col-xs-10 input-group">
													<select id="sbu" name="sbu" class="form-control input-sm" input-group required>
																										
														<option value="0" Selected="Selected" ></option>
														<option value="DS" >DS [Department Store]</option>
														<option value="SMR" >SMR [Supermarket]</option>
													</select>
												</div> 
											</div>
									</div>

									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="vcode">Vendor Code:<font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="vcode" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onChange="" autofocus required>
												<span class="input-group-btn">
													<button id="btnlu" name="btnlu" class="btn btn-primary input-sm" type="button" title="Lookup">
														<i class="fa fa-search fa-fw"></i>
													</button>
												</span>
											</div> 
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="email">Vendor Email:</label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="email" type="text" class="form-control input-sm" placeholder="optional" name="email" value="" onChange="">
											</div>
										</div>
									</div>
									<hr />
									<div class="text-right">
										<button name="" type="submit" class="btn btn-primary btn-default">
											<i class="fa fa-check fa-fw"></i>
											<strong>Activate</strong>
										</button>
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