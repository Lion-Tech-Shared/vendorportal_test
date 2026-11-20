<?php
date_default_timezone_set('Asia/Taipei');
require_once "class-main/connection.class.php";
require_once "class-main/designer.class.php"; 
require_once "control/functions.php";
//exit;
unset($_SESSION['reset_uid']);
unset($_SESSION['reset_link']);
	
if(isset($_SESSION['uid'])){
	header("Location:panel/home.php");
	exit;
}
if(isset($_GET['rgn']) && isset($_GET['userid'])){
	$random_num=$_GET['rgn'];
	$userid=$_GET['userid'];
}else{
	header("Location:home.php");
	exit;
}
$designer=new designPage();
$con=new getConnection();
$db=$con->PDO();

$query="SELECT * FROM tbl_send_link WHERE userid=? AND link=?";
$prepare=$db->prepare($query);
$prepare->execute(array($userid,$random_num));
$fetch=$prepare->fetchAll();

 
if(count($fetch)<1){
	//header("Location:home.php");
	//exit;
	unset($_SESSION['reset_uid']);
	unset($_SESSION['reset_link']);
	header("Location:home.php");
	exit;
}else{
	$sent_date=$fetch[0]['date'];
	$isexpired=0;
	$_SESSION['reset_uid']=$userid;
	$_SESSION['reset_link']=$random_num;	
	
	$query="SELECT exp_duration,now() FROM tbl_email_link_exp";
	$prepare=$db->prepare($query);
	$prepare->execute();
	$fetch=$prepare->fetchAll();
	$duration=$fetch[0]['exp_duration'];
	
	//$current_date=date('Y-m-d H:i:s');
	$current_date=$fetch[0][1];
	$sent_datetime=new DateTime($sent_date);
	//$current_datetime=new DateTime(date("Y-m-d H:i:s"));
	$current_datetime=new DateTime($current_date);
 
	$day_diff=$current_datetime->diff($sent_datetime)->d; 
	$minutes_diff=$current_datetime->diff($sent_datetime)->i; 
	$seconds_diff=$current_datetime->diff($sent_datetime)->s; 
	if($day_diff > 0 || ($minutes_diff >= $duration && $seconds_diff > 0)){
		$query="DELETE FROM tbl_send_link WHERE userid=? AND link=?";
		$prepare=$db->prepare($query);
		if($prepare->execute(array($userid,$random_num)))		 
			$isexpired=1;
	}
}
//echo "current:".$current_date."; minutes_diff:".$minutes_diff.";duration:".$duration."; sent_date:".$sent_date;
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
    	<?php if($isexpired){
				echo '<p>&nbsp;</p><p>&nbsp;</p>';
				echo '<div class="alert alert-danger col-sm-4 col-md-offset-4" align="center">
                     <i class="fa fa-warning fa-fw"></i><strong>Link expires</strong></div>';
				echo "<a href='home.php'><i class='fa fa-home fa-fw'></i></a>";	 
		  }else{
	    ?>
		<?PHP echo $designer->pageBanner(); ?>
        <!-- /.row -->
		<div class="row">
				 	
				<div class="col-sm-6 col-md-offset-3">
					<div class="login-panel panel panel-default shadow-cont">
						<div class="panel-heading">
							<i class="fa fa-key fa-fw"></i>
							<strong>Reset Password</strong>
						</div>
						<div class="panel-body">
								
                            	<p></p>				                                                                 
                                <p></p>
                                <div class="form-group <?PHP //echo $error_captcha_msg; ?>">
                                	<label>New Password</label>
									<input type="password" id="new_pwd"  class="form-control" name="new_pwd" value="" required /><span></span>
								</div> 
                                <div class="form-group <?PHP //echo $error_captcha_msg; ?>">
                                	<label>Confirm Password</label>
									<input type="password"  id="confirm" class="form-control" name="confirm" value="" required /><span></span>
								</div>   
                                <br>                       
								<hr />                               
								<button name="reset" id="btn_reset"  class="btn btn-default">
									<i class="fa fa-unlock fa-fw"></i>
									<strong>Reset&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
								</button>                                					                                              
                             
						</div>
					</div>
				</div>
		</div>
    </div>
    <!-- /.container -->
	
    <?PHP echo $designer->pageFoot(); ?>
    <?php echo $designer->private_js();?>
    
	<?php echo $designer->modal();?>
    <?php }?>
</body>

</html>
