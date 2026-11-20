<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "control/redirect.php";
	require_once "../panel/check_effect.php";
	$designer = new designPage();
	
	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}
				
	//update if expiry date is null
	
	$check_expir_if_null=new check_if_expire();
	//$check_expir_if_null->update_expiry();	
		
	//check if expire
	$getClass=new check_if_expire();
	$getClass->isExpire();	
	
	$change_pwd=new link_2_change_pwd();
	$change_pwd->toChangePwd();
	
	$page = "home";
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
        	
			<div class="panel panel-default">
				<div class="panel-heading">
					<div >
						<h4 style="padding-left:20px;">Dashboard</h4>
					</div>
				</div>
                <div class="panel-body">
					<div class="col-lg-12" >
						<div class="panel panel-default">
							<div class="panel-body">
                            	Dashboard Content
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