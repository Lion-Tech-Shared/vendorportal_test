<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	$designer = new designPage();
	
	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}	
	$page = "change_password";
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
		<?PHP //echo $designer->pageSideBar(); ?>
        
        <div class="col-sm-12">
        	<!--<div class="row-cont">-->
			<div class="panel panel-default" >            
				<div class="panel-heading">
                	<!---<div class="col-lg-12">---->
					<div>
						<h4 style="padding-left:20px;">Change Password</h4>
					</div>
				</div>
                <div class="panel-body">
					<div class="col-lg-12" >
						<div class="panel panel-primarys">
							<div class="panel-body">
                            	<?php if(isset($_SESSION['change_pwd'])){?>
                            	<div class="alert alert-info" align="center">
                                    <strong>Change Your Password!</strong> This is for security purposes.
                               
                                <?php } ?>
								<?php if(isset($_SESSION['id_expired'])){ 
										 echo "<p></p><strong>".$_SESSION['id_expired']."!</strong>  ";											 							  
							 	 } ?>
                                 </div>
                            	<form class="form-horizontal" action="control/change_password.php" method="post">
									<p class="text-muted">Kindly put current password and new password.</p>
									<hr />
									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="vcode">Current Password:<font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="cpass" type="password" class="form-control input-sm" placeholder="" name="cpass" value="" onChange="" autofocus required>
											</div>
										</div>
									</div>
                                    <br />
									<div class="form-group">
										<label class="control-label col-md-3 input-sm" for="email">New Password:<font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="npass" type="password" class="form-control input-sm" placeholder="" name="npass" value="" onChange="" required><p class="small text-muted">Minumum of 6 Characters</p>
											</div>
										</div>
									</div>
                                    <div class="form-group">
										<label class="control-label col-md-3 input-sm" for="email">Re-type Password:<font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="rnpass" type="password" class="form-control input-sm" placeholder="" name="rnpass" value="" onChange="" required>
											</div>
										</div>
									</div>
                                    <hr>
                                    <div class="form-group">
                                    	<p class="container-fluid text-muted">Include Email Address In case you forgot password</p>
										<label class="control-label col-md-3 input-sm" for="email">Email Address <?php $_SESSION['gid'];?><font style="color:red;"> *</font></label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
                                            	<?php 
												$con=new getConnection();
												$db=$con->PDO();
												if($_SESSION['gid']==2){
													$query="SELECT v_email_address FROM tbl_vendor_info WHERE v_code=? ";												
													$col="v_email_address";
												}
												else{
													$query="SELECT ee_email_address FROM tbl_user_info WHERE user_id=? ";													
													$col="ee_email_address";
												}
												$prepare=$db->prepare($query);	
												$prepare->execute(array($_SESSION['uid']));
												$fetch=$prepare->fetchAll();
												$email_val='';
												if(count($fetch)>0)
													$email_val=$fetch[0]["".$col];
													
												?>
												<input id="email" type="text" class="form-control input-sm" placeholder="" name="email" value="<?php echo $email_val; ?>" onChange="" required>
											</div>
										</div>
									</div>
                                    <?PHP
										echo '<br>';
										
                                    	if(isset($_SESSION["change_failed"]) && $_SESSION["change_failed"] == 1) {
											echo '<div class="alert alert-danger" style="padding:5px;"><center><i class="fa fa-warning fa-fw"></i><font style="  font-weight:bold;">New password did not match.</font></center></div>';
											$myMsg = 'New password did not match.';
										}
										else if(isset($_SESSION["change_failed"]) && $_SESSION["change_failed"] == 2) {
											echo '<div class="alert alert-danger" style="padding:5px;"><center><i class="fa fa-warning fa-fw"></i><font style=" font-weight:bold;">Current password incorrect.</font></center></div>';
											$myMsg = 'Current password incorrect.';
										}
										else if(isset($_SESSION["change_failed"]) && $_SESSION["change_failed"] == 3) {
											echo '<div class="alert alert-info" style="padding:5px;"> <center><i class="fa fa-thumbs-o-up fa-fw"></i><font style=" font-weight:bold;">Password successfully changed.</font></center></div>';
											$myMsg = 'Password successfully changed.';
										}
										else if(isset($_SESSION["change_failed"]) && $_SESSION["change_failed"] == 4) {
											echo '<div class="alert alert-danger" style="padding:5px;"><center><i class="fa fa-warning fa-fw"></i><strong>Passwords are similar</strong></center></div>';
											$myMsg = 'Password successfully changed.';
										}
										else if(isset($_SESSION["change_failed"]) && $_SESSION["change_failed"] == 5) {
											echo '<div class="alert alert-danger" style="padding:5px;"><center><i class="fa fa-warning fa-fw"></i><strong>Try Again.</div>';
											$myMsg = 'Try Again.';
										}
										else if(isset($_SESSION["change_failed"]) && $_SESSION["change_failed"] == 6) {
											echo '<div class="alert alert-danger" style="padding:5px;"><center><i class="fa fa-warning fa-fw"></i><strong>Invalid Email Address</div>';
											$myMsg = 'Invalid Email Address';
										}
										else if(isset($_SESSION["change_failed"]) && $_SESSION["change_failed"] == 7) {
											echo '<div class="alert alert-danger" style="padding:5px;"><center><i class="fa fa-warning fa-fw"></i><strong>Manimum of 6 Characters only is allowed</strong></div>';
											$myMsg = 'Minimum of 6 Characters only is allowed';
										}
										else if(isset($_SESSION["change_failed"]) && $_SESSION["change_failed"] == 8) {
											echo '<div class="alert alert-danger" style="padding:5px;"><center><i class="fa fa-warning fa-fw"></i><strong>Please use letters & digits & alpha characters in your password</strong></div>';
											$myMsg = 'Please use letters & digits & alpha characters in your password';
										}
										if(isset($_SESSION['email_msg'])){
											echo '<div class="alert alert-danger" style="padding:5px;"><center><i class="fa fa-warning fa-fw"></i><strong>'.$_SESSION['email_msg'].'</div>';
											unset($_SESSION['email_msg']);
											$myMsg = $_SESSION['email_msg'];
										}
																					
										
									?>
									<hr />
									<div class="text-right">
										<button id="save_pwd" name=""  class="btn btn-primary btn-default">
											<i class="fa fa-save fa-fw"></i>
											<strong>Save</strong>
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
	<?php 
			if(isset($_SESSION["change_failed"])){
				echo '<script>
						alert("'.$myMsg.'");
					 </script>';
				unset($_SESSION["change_failed"]);	 
			}
	?>
</body>
</html>