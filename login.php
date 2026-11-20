<?PHP 
	require_once "class-main/designer.class.php"; 
	$designer = new designPage();

	$uid = "";
	$pwd = "";
	$error = "";
	
	if(!isset($_SESSION["passed"]) || !$_SESSION["passed"]) {
		if(isset($_SESSION["uid"]) && isset($_SESSION["pwd"])) {
			$uid = $_SESSION["uid"];
			$pwd = $_SESSION["pwd"];
			$error = "has-error";
			
			unset($_SESSION["uid"]);
			unset($_SESSION["pwd"]);
		}
	}
	else {
		header("Location: home.php");
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
    <?PHP echo $designer->pageNavi("login"); ?>

    <!-- Page Content -->
    <div class="container">
		<?PHP echo $designer->pageBanner(); ?>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-primary">
					<div class="panel-heading">
						<i class="fa fa-key fa-fw"></i>
						<strong>Login Info</strong>
					</div>
                    <div class="panel-body">
                        <form action="control/authenticate.php" method="post">
							<div class="form-group <?PHP echo $error; ?>">
								<input id="" type="text" class="form-control" placeholder="User ID" name="uid" value="<?PHP echo $uid; ?>" autofocus required>
							</div>
							<div class="form-group <?PHP echo $error; ?>">
								<input id="" type="password" class="form-control" placeholder="Password" name="pwd" value="<?PHP echo $pwd; ?>" required>
							</div>
                            <div ><input value="<?php echo "mdf:".strToHex(mysql_aes_encrypt("abcdefghijklmnopqrstuvwxyz", MD5("admin")));?>"/></div>
							<!--1-->
                            
							<hr />
							<button name="lgin" type="submit" class="btn btn-primary btn-default btn-block">
								<i class="fa fa-unlock fa-fw"></i>
								<strong>Login</strong>
							</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
		<hr style="margin:0;"/>
    </div>
    <!-- /.container -->
	
	<?PHP echo $designer->pageFoot(); ?>
</body>
</html>

<!--1 <div class="text-center">
	<ol class="breadcrumb">
		<li>
			<a href="#">
				Forgot user ID
				<i class="fa fa-question"></i>
			</a>
		</li>
		<li>
			<a href="#">
				Forgot password
				<i class="fa fa-question"></i>
			</a>
		</li>
	</ol>
</div>-->
