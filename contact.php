<?PHP 
	require_once "class-main/designer.class.php"; 
	$designer = new designPage();
	
	if(isset($_SESSION["uid"])) {
		header("Location: panel/home.php");
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
    <?PHP echo $designer->pageNavi("contact"); ?>

    <!-- Page Content -->
    <div class="container-fluid">
		<?PHP echo $designer->pageBanner(); ?>
        <!-- /.row -->
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-envelope fa-fw"></i>
						<strong>Message Us</strong>
					</div>
					<div class="panel-body">
						<p class="text-muted">Kindly fill-up all fields and click "Send".</p>
						<hr/>
						<form class="form-horizontal" method="post" action="" role="form">
							<div class="form-group">
								<div class="col-md-8 col-md-offset-2">
									<input id="memail" name="memail" type="text" class="form-control input-sm" value="<?PHP if(isset($_SESSION["eml"]) && $_SESSION["eml"]) { echo $_SESSION["eml"]; } ?>" placeholder="Email (Sender)" <?PHP if(!isset($_SESSION["eml"])) { echo "autofocus"; } ?> required/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-8 col-md-offset-2">
									<input id="mtitle" name="mtitle" type="text" class="form-control input-sm" placeholder="Title" <?PHP if(isset($_SESSION["eml"]) && $_SESSION["eml"]) { echo "autofocus"; } ?> required/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-8 col-md-offset-2">
									<textarea id="mcontent" name="mcontent" class="form-control input-sm" placeholder="Message" style="resize:none;" rows="5" required></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-8 col-md-offset-2">
									<button class="btn btn-primary btn-default">
										<strong>
											<i class="fa fa-send fa-fw"></i>
											Send
										</strong>
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-phone fa-fw"></i>
						<strong>Contact Information</strong>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-4">
								<address>
									<strong>Company Name</strong>
									<br>Address Line 1
									<br>Address Line 2
									<br>
									<abbr title="Phone">
										<i class="fa fa-phone fa-fw"></i>:
									</abbr>
									(123) 456-7890
								</address>
							</div>
							<div class="col-sm-4">
								<address>
									<strong>Contact Name 1</strong>
									<br>
									<a href="mailto:first.last@example.com">first.last@example.com</a>
									<address>
									<strong>Contact Name 2</strong>
									<br>
									<a href="mailto:#">first.last@example.com</a>
								</address>
								</address>
							</div>
							<div class="col-sm-4">
								<address>
									<strong>Contact Name 3</strong>
									<br>
									<a href="mailto:#">first.last@example.com</a>
								</address>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="col-sm-4">
								<address>
									<strong>Company Name</strong>
									<br>Address Line 1
									<br>Address Line 2
									<br>
									<abbr title="Phone">
										<i class="fa fa-phone fa-fw"></i>:
									</abbr>
									(123) 456-7890
								</address>
							</div>
							<div class="col-sm-4">
								<address>
									<strong>Contact Name 1</strong>
									<br>
									<a href="mailto:first.last@example.com">first.last@example.com</a>
									<address>
									<strong>Contact Name 2</strong>
									<br>
									<a href="mailto:#">first.last@example.com</a>
								</address>
								</address>
							</div>
							<div class="col-sm-4">
								<address>
									<strong>Contact Name 3</strong>
									<br>
									<a href="mailto:#">first.last@example.com</a>
								</address>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<hr/>
			<blockquote>
				<p>This menu enables all browsers, registered or not, to send some queries thru message. CCN portal aims to be closer to all competitive suppliers nationwide.</p>
				<small>
					Administrator 2014
				</small>
			</blockquote>
		</div>
    </div>
    <!-- /.container -->

	<?PHP echo $designer->pageFoot(); ?>
</body>

</html>
