<?PHP 

	require_once "class-main/designer.class.php"; 
	$designer = new designPage();

	if(!isset($_SESSION["uid"])) {
		//header("Location: ../home.php");
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
    <?PHP echo $designer->pageNavi("home"); ?>	
    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>
    <div class="container-fluid">
        <div class="alert alert-warning col-lg-5 col-md-offset-3" align="center">
            <strong>Loading Data to CCN Portal! </strong>
        </div>
    </div>            
    <?PHP //echo $designer->pageFoot($page); ?>
</body>

</html>