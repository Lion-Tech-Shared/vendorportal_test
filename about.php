<?PHP 
	require_once "class-main/designer.class.php"; 
	$designer = new designPage();
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
    <?PHP echo $designer->pageNavi("about"); ?>

    <!-- Page Content -->
    <div class="container">
        <?PHP echo $designer->pageBanner(); ?>
        <!-- /.row -->
		<div class="row">
			<div class="panel panel-primary">
				<div class="panel-body">
					<h4>About CCN Portal</h4>
					
				</div>
			</div>
		</div>
    </div>
    <!-- /.container -->

    <?PHP echo $designer->pageFoot(); ?>
</body>

</html>
