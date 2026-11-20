<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	$designer = new designPage();
	
	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$qry = "SELECT 
				content
			FROM
				tbl_disclaimer";
				
	$res = $db->prepare($qry);
	$res->execute();
	$row = $res->fetchAll();
	
	$page = "disclaimer";
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
						<h4 style="padding-left:20px;">Disclaimer Setup</h4>
					</div>
				</div>
                <div class="row">
					<div class="col-lg-12" >
						<div class="panel panel-primary">
							<div class="panel-body">
                            	<form class="form-horizontal" action="control/save_disclaimer.php" method="post">
									<div class="form-group" style="height: 100px;">
										<label class="control-label col-md-3 input-sm" for="from">Disclaimer Content:</label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-12 input-group">
                                                <textarea name="content" id="content" style="resize: none; height: 80px;" class="form-control"><?PHP echo $row[0]["content"]; ?></textarea>
											</div>
										</div>
                                        <br />
									</div>
                                    <hr />
									<div class="text-right">
										<button name="" type="submit" class="btn btn-primary btn-default">
											<i class="fa fa-save fa-fw"></i>
											<strong>Save</strong>
										</button>
									</div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
                <?PHP echo $designer->pageDisclaimer(); $_SESSION["saved_disc"] = false;?>	
			</div>
		</div>
	</div>
	
    <?PHP echo $designer->pageFoot($page); ?>
</body>
</html>
