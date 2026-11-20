<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	$designer = new designPage();
	
	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}	
	$page = "change_password";
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
        	<!--<div class="row-cont">-->
			<div class="panel panel-default" >            
				<div class="panel-heading">
                	<!---<div class="col-lg-12">--->
					<div>
						<h4 style="padding-left:20px;">Change Email Address</h4>
					</div>
				</div>
                <div class="panel-body" style="border:1px solid #FFF;">
					<div class="col-lg-12" >
						<div class="panel panel-primary" style="border:1px solid #FFF;">
							<div class="panel-body" style="border:1px solid #FFF;">
                            	
                            	 
                                 <?php 
								  
									$con=new getConnection();
									$db=$con->PDO();
									$col="";
									if($_SESSION['gid']==2){
										$query="SELECT v_email_address FROM tbl_vendor_info WHERE v_code=? ";												
										$col="v_email_address";
									}
									else{
										$query="SELECT ee_email_address FROM tbl_user_info WHERE user_id=? ";													
										$col="ee_email_address";
									}
									$prepare=$db->prepare($query);	
									$prepare->execute(array($_SESSION['uid']));
									$fetch=$prepare->fetchAll();
									//if(count($fetch)>0)
									$email_val=$fetch[0]["".$col.""];
										
								?>
                            	<form class="form-horizontal" action="control/change_email.php" method="post">
									 
									<div id="div_err" class="form-group">
										<label class="control-label col-md-3 input-sm" for="email">Email Address:</label>
										<div class="col-md-7 input-sm">
											<div class="col-xs-10 input-group">
												<input id="npass" type="text" class="form-control input-sm" placeholder="" name="email" value="<?php echo $email_val;?>" onChange="" required> 
											</div>
										</div>
									</div>
                       				  
									<div class="text-right">
										<button name="" type="submit" id="btn_save" class="btn btn-primary btn-default">
											<i class="fa fa-save fa-fw"></i>
											<strong>Save</strong>
										</button>
									</div>                                   
								</form>
                                
                                
                            </div>
                            <?php if(isset($_SESSION['isemailvalid']) && $_SESSION['isemailvalid']==0){?>                                                              
                                     <div id="hide_me" class="alert alert-danger" align="center" style="padding:10px;">
                                        <div ><i class="fa fa-warning fa-fw"></i><strong>Email is invalid</strong></div>
                                     </div>
                            <?php 	 							
							      }else if(isset($_SESSION['isemailvalid']) && $_SESSION['isemailvalid']==1){?>
                                     <div id="hide_me" class="alert alert-info" align="center" style="padding:10px;">
                                        <div ><i class="fa fa-warning fa-fw"></i><strong>Email is Updated</strong></div>
                                     </div>
                            <?php }
								  unset($_SESSION['isemailvalid']);	
							?>         
                            
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