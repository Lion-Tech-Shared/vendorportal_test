<?PHP 
	require "../class-main/connection.class.php";
	require "class/panel-designer.class.php";
	$designer = new designPage();

	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}
	
	if(!isset($_SESSION["result"])) {
		header("Location: home.php");
	}
	else {
		unset($_SESSION["result"]);
	}
	
	$page = "";
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
			<div class="row">
				<div class="col-lg-12" >
					<div class="panel panel-primary">
						<div class="panel-body">
							<i class="fa fa-thumbs-up fa-fw"></i>
							Account activated.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <?PHP echo $designer->pageFoot($page); ?>
</body>

</html>