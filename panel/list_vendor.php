<?PHP 
	require_once "class/panel-designer.class.php"; 
	$designer = new designPage();

	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}
	
	$page = "list_vendor";
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
						<h4 style="padding-left:20px;">Vendor Record</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12" >
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-hover" id="dataTables" style="font-size:11px;">
										<thead>
											<tr>
												<th>User ID</th>
												<th>Password</th>
												<th>Name</th>
												<th>Principal</th>
												<th>Contact Person</th>
												<th>Phone #</th>
												<th>Fax #</th>
												<th>Email</th>
												<th>Status</th>
												<th>Reg. Date</th>
												<th>Exp. Date</th>
												<th>Last Login</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <?PHP echo $designer->pageFoot($page); ?>
</body>

</html>