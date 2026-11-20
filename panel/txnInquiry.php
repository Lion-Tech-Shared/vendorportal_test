<?PHP   
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "control/redirect.php";
	 
	$newFunction=new link_2_change_pwd();
	$newFunction->toChangePwd();
	
	$designer = new designPage(); 
	/* Redirect if not set session */
	$newFunction->checkSession();

	if($_SESSION["gid"]!=2 || $_SESSION["gid"]==3) {
		//header("Location: ../home.php"); 
	}	
	 
	$conn = new getConnection();	
	$db=$conn->PDO(); 

	$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
	$canReceive=$fetch[0]['canReceive'];
 
	$page="inquiry";
	 
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
<Style>
	.grnd_my_amt2{text-align:center;padding-lefts:5px;}
	.grnd_my_qty2{text-align:center;padding-rights:25px;}

</Style>
<body>
	<?PHP echo $designer->pageNavi(); ?>
	
	<div class="container-fluid">
		<?PHP echo $designer->pageBelowHeader(); ?>
		<?PHP echo $designer->pageSideBar(); ?>
		
			  <div class="col-sm-9">
				<div class="panel panel-default">
					<div class="panel-heading">
					  	<div class="row">
							<div class="col-lg-7">
								<h4 style="padding-left:0px;"><!--<span class="glyphicon glyphicon-minus-sign"> </span>&nbsp;--> <strong><svg class="glyph stroked clipboard with paper"><use xlink:href="#stroked-clipboard-with-paper"/></svg>Transaction Inquiry </strong></h4>
							</div>
 
						</div>
					</div>
					<div class="panel-body">	
					  				 
					</div>

					<div class="panel-footer">
	                            
	                </div>
	                <div class="row">
							<div class="col-lg-12">
								<!--<div class="disclaimer-cont">
									<i class="fa fa-warning fa-fw"></i>
									<i><strong>NOTE:</strong>&nbsp;This portal is under enhancement and testing. Contents of this site are not yet final and subject for changes.</i>
								</div>-->
							</div>
						</div>			</div>
			</div>
	</div>
    
    <?PHP echo $designer->pageFoot($page); ?>
    
</body>

</html>
