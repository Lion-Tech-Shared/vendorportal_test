<?PHP 
	

	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "control/redirect.php";
	
	//if($_SESSION['uid']!='ADMIN')
	//header("Location:home.php");
	$conn = new getConnection();	
	$db=$conn->PDO();

	$change_pwd=new link_2_change_pwd();
	$change_pwd->toChangePwd();

	$query=' SELECT * FROM tbl_user_group_menu_sub ';
	$query.=" WHERE group_id=? AND menu_id_sub=(SELECT menu_id_sub FROM tbl_app_menu_sub WHERE menu_dsc_sub=?)";	
	$prepare=$db->prepare($query);
	$prepare->execute(array($_SESSION['gid'],"Sales"));
	$count_fetch=$prepare->fetchAll();
	if(count($count_fetch)<1){
		header("Location: ../home.php");
	}
	
	$designer = new designPage();

	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}	
	$page = "sales_report";
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
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="scol-lg-12">
						<h4 style="padding-left:20px;">CCN Sales Generator</h4>
					</div>
				</div>
				<div class="panel-body">
					<div class="col-lg-12" >
						<div class="panel panel-primarys">
							<div class="panel-body">
								<form class="form-horizontal" action="" method="post">
									<p class="text-muted">
										Kindly fill-up all required fields (<font style="color:red;">*</font>).
										<?PHP if($_SESSION["gid"] != 2) { ?>
										<br/>
										Please select date range first before selecting vendor code.
										<br /><br hidden />
										<i hidden>(Note: Generating sales using <b>Store Code</b> is slower than <b>Vendor Code</b>)</i>
										<?PHP } ?>
									</p>
									<hr />
									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="from">Date From:<font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="from" type="text" class="form-control input-sm dp" placeholder="" name="from" value="" onChange="" autofocus required>
												<span class="input-group-btn">
													<button id="btn-from" class="btn btn-default input-sm" type="button" title="Picker">
														<i class="fa fa-calendar fa-fw"></i>
													</button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="to">Date To:<font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="to" type="text" class="form-control input-sm dp" placeholder="" name="to" value="" onChange="" required>
												<span class="input-group-btn">
													<button id="btn-to" class="btn btn-default input-sm" type="button" title="Picker">
														<i class="fa fa-calendar fa-fw"></i>
													</button>
												</span>
											</div>
										</div>
									</div>
									<?PHP 
									 
									//if($_SESSION["gid"] != 2) { echo '<hr/>';} echo ?>
									<div class="form-group" <?PHP  if($_SESSION["gid"] == 2) { /*echo "hidden";*/ }//if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) { echo "hidden"; }; ?>>
										<label class="control-label col-md-3 input-sm" for="sbu">Business Unit:</label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<select id="sbu" class="form-control input-sm">
													<?php if($_SESSION['gid']!=2){ ?>
														<!--<option value="0">All</option>-->
														<!--<option value="DS" Selected="Selected" >DS [Department Store]</option>-->
													<?php }else{ ?>
														<!--<option value="0">All</option>-->
														<!--<option value="DS" selected="selected">DS [Department Store]</option>-->
													<?php } ?>													
													<option value="0" Selected="Selected"></option>
													<option value="DS" >DS [Department Store]</option>
													<option value="SMR">SMR [Supermarket]</option>
												</select>
											</div>
										</div>
									</div>

									<hr <?PHP if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) { echo "hidden"; }; ?>/>
									<div class="form-group" <?PHP if($_SESSION["gid"] != 2) { echo ""; }; ?>>
										<label class="control-label col-md-3 input-sm">Sales Format:</label>
										<div class="col-md-5">
											<label class="radio-inline col-md-7" style="margin-left:5px;"><input type="radio" checked="checked" class="optradio_format_type" name="optradio_format_type" id="d">Detailed</label>
											<label class="radio-inline col-md-3"><input type="radio" class="optradio_format_type" name="optradio_format_type" id="t">Total</label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 input-sm">Per:</label>                                        
										<div class="col-md-3 col-md-offset-1">                                         
											<label class="radio-inline col-md-4" style="margin-left:5px;"><input type="radio" checked="checked" class="optradio_format" name="optradio_format" id="s">SKU</label>
											<label class="radio-inline col-md-2"><input type="radio" class="optradio_format" name="optradio_format" id="dt">Date</label>
										</div>
									</div>
									<hr />
									<div class="form-group" <?PHP if($_SESSION["gid"] == 2) { echo ""; }; ?> hidden>
										<label class="control-label col-md-3 input-sm">Lookup Option:</label>
										<div class="col-md-9">
											<label class="radio-inline col-md-4" style="margin-left:5px;"><input type="radio" checked="checked" class="optradio" name="optradio" id="Vendor">Vendor Code</label>
											<label class="radio-inline col-md-3"><input type="radio" class="optradio" name="optradio" id="Store">Store Code</label>
										</div>
									</div>
									<div class="form-group" <?PHP if($_SESSION["gid"] == 2) { echo "hidden"; }; ?>>
										<label class="control-label col-md-3 input-sm" for="vcode" id="luplbl">Vendor Code:<font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="vcode" type="text" class="form-control input-sm" placeholder="" name="vcode" value="<?PHP if($_SESSION["gid"] == 2) { echo $_SESSION["uid"]; } ?>" onChange="" disabled="disabled" required>
												<span class="input-group-btn">
													<button id="btnvclear" name="btnvclear" class="btn btn-default input-sm" type="button" title="Clear">
														<i class="fa fa-eraser fa-fw"></i>
													</button>
													<button id="btnlu" name="btnlu" class="btn btn-default input-sm" type="button" title="Lookup">
														<i class="fa fa-search fa-fw"></i>
													</button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group" <?PHP if($_SESSION["gid"] != 2) { echo ""; }; ?>>
										<label class="control-label col-md-3 input-sm" for="store" id="">Store Code:</label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="store" type="text" class="form-control input-sm" placeholder="for specific store/s (coma delimited)" name="store" value="" onChange="" disabled="disabled">
												<span class="input-group-btn">
													<button id="btnsclear" name="btnsclear" class="btn btn-default input-sm" type="button" title="Clear">
														<i class="fa fa-eraser fa-fw"></i>
													</button>
													<button id="btnlu_2" name="btnlu_2" class="btn btn-default input-sm" type="button" title="Lookup">
														<i class="fa fa-search fa-fw"></i>
													</button>
												</span>
											</div>
										</div>
									</div>
									<hr />
                                    <div id="checks">
                                    	<!--<input class="1" type="CheckBox" >
                                        <input class="2" type="checkbox" >-->
                                    </div>
									<div class="text-right">
										<button id="vsales" name="vsales" type="button" class="btn btn-primary btn-default">
											<i class="fa fa-search fa-fw"></i>
											<strong>View</strong>
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