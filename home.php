<?PHP 
    //header("Location:redirect.php");
	 
	require_once "class-main/designer.class.php"; 	
	require_once "securimage/securimage.php";
	require "control/functions.php";
	$designer = new designPage();
 
	$securimage=new Securimage();
	$securimage->code_length=8;
 
	$uid = "";
	$pwd = "";
	$error = "";
 
	$msg=""; 
	$error_captcha=''; 
 
	$error_captcha_msg=''; 
	$captchaId='';
	date_default_timezone_set('Asia/Taipei');
 
	if(isset($_SESSION['msg'])){
		$lock_msg='Your login is locked for '.(60-$_SESSION['msg']).' minutes';
		$msg='<p></p><div class="alert alert-danger" style="padding:10px;" align="center"><strong><i class="fa fa-warning fa-fw"></i>'.$lock_msg.'</strong><div>';
		unset($_SESSION['msg']);
	}else if(isset($_SESSION['status'])){		
		$err_msg='Your Account is not Enabled';
		$msg='<p></p><div class="alert alert-danger" style="padding:10px;" align="center"><strong><i class="fa fa-warning fa-fw"></i>'.$err_msg.'</strong><div>';
		unset($_SESSION['status']);
	}
	if(!isset($_SESSION["passed"]) || !$_SESSION["passed"]) {
		if(isset($_SESSION["uid"]) && isset($_SESSION["pwd"])) {
			$uid = $_SESSION["uid"];
			$pwd = $_SESSION["pwd"];
			$error = "has-error";
			
			unset($_SESSION["uid"]);
			unset($_SESSION["pwd"]);
			$msg='<p></p><div class="alert alert-danger" style="padding:10px;" align="center"><strong><i class="fa fa-warning fa-fw"></i> Invalid Login</strong><div>';//uls
		}
		if(isset($_SESSION['error_captcha'])){
			unset($_SESSION['error_captcha']);
			$error_captcha='<i class="fa fa-warning fa-fw"></i>Invalid Code!';
			$error_captcha_msg='has-error';
		}
		
	}
	else {
		header("Location: panel/home.php");
	}	 
	//echo strToHex(mysql_aes_encrypt("MPC00484TA",MD5(strtoupper("bertjulz"))));	 
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
	
    <?PHP echo $designer->pageNavi("home"); ?>
	
    <!-- Page Content -->

    <div class="container-fluid">
		<?PHP echo $designer->pageBanner(); ?>
        <!-- /.row -->
		<div class="row">
				<div class="col-sm-8">
					<div class="info-cont">						           
						<div class="panel panel-default">
                        	<div class="panel-heading">
                                <h3 class="panel-title"><div class="panel-heading">
									<i class="fa fa-info-circle fa-fw"></i>
									<strong>Vendor Portal Information</strong>								           
                        		</h3>
                            </div>
                            <div class="panel-body">                       
                                <blockquote>
                                    <p>Welcome to LCC Vendor Portal, our response to the business needs of our valued suppliers.</p>
                                    <p>At LCC, we recognize and value strong relationship with business partners, who play a vital role in helping us sell quality products to our customers</p>
                                    <p>This site gives you instant access to your inventory and allows you to do suggestive request orders for Merchandising review and purchase ordering.</p>
                                    <p>If you want to register, please contact our  Merchandising Office for more information.</p>
                                    <small>
                                        Administrator 2014  
                                    </small>
                                </blockquote>
                             </div>                                                             
						</div>                        
					</div>
				</div>

				<div class="col-sm-4">
					<div class="login-panel panel panel-default shadow-cont">
						<div class="panel-heading">
							<i class="fa fa-key fa-fw"></i>
							<strong>Login</strong>
						</div>
						<div class="panel-body">
							<form action="control/authenticate.php" method="POST">
								<div class="form-group <?PHP echo $error; ?>">
									<input id="" type="text" class="form-control" placeholder="User ID" name="uid" value="<?PHP echo $uid; ?>" autofocus required>
								</div>
								<div class="form-group <?PHP echo $error; ?>">
									<input id="" type="password" class="form-control" placeholder="Password" name="pwd" value="<?PHP echo $pwd; ?>" required>
								</div>
                                
                                <!--<div class="form-group <?PHP echo $error; ?>" align="center">
									<input id="captchaId" type="hidden" class="form-control" placeholder="Password" name="captchaId" value="<?PHP echo $captchaId; ?>" required>
								</div>-->

                                <div class="row" align="center">
									<div class="col-sm-12" ><img id="captcha"  src="securimage/securimage_show.php" alt="captcha_image" width="100%"/></div><br>
                                    <div class="col-sm-12" ><a href="#" onClick="document.getElementById('captcha').src='securimage/securimage_show.php?' + Math.random();return false"><img height="20" src="securimage/images/refresh.png" />Try New Code</a></div>
								</div>
                                 
                                <div class="form-group <?PHP echo $error_captcha_msg; ?>">
                                	<label>Enter Code:</label>
									<input type="text"  class="form-control" name="captcha_code" value="" required /><span><?php echo "<strong><font color='red'>".$error_captcha ."</font></strong>"; ?></span>
								</div>  
                                                              
								<hr />                               
								<button name="lgin" type="submit" class="btn btn-default">
									<i class="fa fa-unlock fa-fw"></i>
									<strong>Login&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
								</button>
                                <div class="row">                                	
                                	<div class="col-sm-12" align="right"><a id="forgot_pwd" href="#"><i class="fa fa-lightbulb-o fa-fw"></i>Forgot Password?</a></div>
                                </div>    


							</form>
							
                            <?php echo $msg;?>
						</div>
					</div>
				</div>
		</div>
    </div>
    <!-- /.container -->
	
    <?PHP echo $designer->pageFoot(); ?>
    
	<?php echo $designer->modal();?>
</body>

</html>
