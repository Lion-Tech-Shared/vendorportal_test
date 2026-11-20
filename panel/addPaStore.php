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
	//$_SESSION['delType']='store';
	//$page = "store_delivery";
	 
	$conn = new getConnection();	
	$db=$conn->PDO(); 

	$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
	$canReceive=$fetch[0]['IsPA'];

	/* Create Transfer Number */
	if($canReceive==1){
		$qry=" SELECT now()";
		$prepare=$db->prepare($qry);
		$prepare->execute();
		$fetch=$prepare->fetchAll();

		$_SESSION['trf_num']=date('ymd-his',strtotime($fetch[0][0]))."".mt_rand(1,1000);		 
	}
	$page="addpastore";
	//echo $_SESSION['isWhse'];
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
						<div class="col-lg-8"> 
							<h4 style="padding-left:0px;"><span class="fa fa-cog"></span>&nbsp;<Strong>Promo Assistant's Store Maintenance</strong></h4>
						</div>
 
					</div>
				</div>
				<div class="panel-body">	
				   
					<div id="sku-lookup" class="col-lg-12">		
					 					 							  						
								<div class="col-sm-12" style="border:0px solid;">	

											<div class="row">
												<p>&nbsp;</p>
												<div class="col-sm-5" ><strong>Promo Assistant</strong></div>		
											 	<p>&nbsp;</p>								 											
												<div class="col-sm-12">
														<div class="col-xs-12 input-group"><input id="txt_pa" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
																<span class="input-group-btn">
																	<button id="btnClearPa" class="btn btn-default input-sm" type="button" title="Clear">
																		<i class="fa fa-eraser fa-fw"></i>
																	</button>
																	<button id="btn_search_pa" class="btn btn-default input-sm" type="button" title="Search">
																		<i class="fa fa-search fa-fw"></i>
																	</button>
																</span>
														</div>
												</div>
											</div>
											<p></p>	
											<div class="row">	
												<div class="col-sm-5" ><strong>Vendor</strong></div>		
											 	<p>&nbsp;</p>	
											    <div class="col-sm-12">	 										 	
												 	<div class="col-xs-12 input-group">
												 		<input id="txt_vendor" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
														<span class="input-group-btn">
															<button id="btnClearVendor" name="btnvclear" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-eraser fa-fw"></i>
															</button>
															<button id="btn_search_vendor" class="btn btn-default input-sm" type="button" title="Search">
																<i class="fa fa-search fa-fw"></i>
															</button>
														</span>
													</div>	
												</div>										 
											</div>	
											<p></p>	
											<div class="row">	
												<div class="col-sm-5" ><strong>Store</strong></div>		
											 	<p>&nbsp;</p>	
											    <div class="col-sm-12">	 										 	
												 	<div class="col-xs-12 input-group">
												 		<input id="txt_stores" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
														<span class="input-group-btn">
															<button id="btnClearStore" name="btnvclear" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-eraser fa-fw"></i>
															</button>
															<button id="btn_search_store" class="btn btn-default input-sm" type="button" title="Search">
																<i class="fa fa-search fa-fw"></i>
															</button>
														</span>
													</div>	
												</div>										 
											</div>
										<p></p>	 
										<div class="row">
 									 	
									 		<p></p>
									 	  								 																								   
										</div>

							</div>	
							<hr> 
							<p></p>																 
							 	<hr>  
							 	<div class="row col-sm-12" align="center">    
							 		<div class="col-sm-4" >        
								 		<button id="btnSubmit"  type="button" class="btn btn-primary   col-sm-12">Attach Stores to Promo Assistant</button>
								 	</div>
								 	<div class="col-sm-2">	
								 		<button id="btn_clear" type="button" class="btn btn-primary  col-sm-12">Clear</button>
								 	</div>
								 	<p>&nbsp;</p>
								 	<p>&nbsp;</p>
							 	</div>
		                     </div>

		                     <p></p>
							<hr>
							<p></p>
					 
				 
	             
				</div>

				<div class="panel-footer">
                            
                </div>
                <?PHP echo $designer->pageDisclaimer(); ?>
			</div>
		</div>
	</div>
    
    <?PHP echo $designer->pageFoot($page); ?>
    
</body>

</html>
