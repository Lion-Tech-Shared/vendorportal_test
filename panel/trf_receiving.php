<?PHP   
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "control/redirect.php";
	 
	$newFunction=new link_2_change_pwd();  
	$newFunction->toChangePwd();
	
	$designer = new designPage();
	/* Redirect if not set session */
	$newFunction->checkSession();

	$conn = new getConnection();	
	$db=$conn->PDO(); 

	$qry=" SELECT * FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
	$canReceive=$fetch[0]['canReceive'];

	/* Create Transfer Number */ 
	if($canReceive==1){
		$qry=" SELECT now()";
		$prepare=$db->prepare($qry);
		$prepare->execute();
		$fetch=$prepare->fetchAll();

		$_SESSION['trf_num']=date('ymd-his',strtotime($fetch[0][0]))."".mt_rand(1,1000);		 
	}else{
		header("Location:home.php");
	} 
	$page="transfer_receiving";
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
						<div class="col-lg-7">
							<h4 style="padding-left:0px;"><span class="glyphicon glyphicon-transfer"></span>&nbsp;<Strong>Transfer Receiving</strong></h4>
						</div>				
					</div>
				</div>
				<div class="panel-body">	
				  <?php if($canReceive==1){?>
					<div id="sku-lookup" class="col-lg-12">		
					<div class="row container-fluid">
						 
					</div>	
					<div class="row col-sm-6" align="center">    
							<div class="row" align="left"><strong>Transfer Number</strong></div>		
							<p> </p>
							<div class="row">								 			 						 	
								<div class=" ">
											<div class="col-xs-12 input-group"> 							 
											 	 <input id="txt_trf" class="form-control input-sm" placeholder="Choose Trf #" name="vcode" disabled="disabled" value="" onchange="" required="" type="text">												
												 <span class="input-group-btn">
													<button id="btn_find_trf" class="btn btn-default input-sm" type="button" title="Search Trf">
														<i class="fa fa-search fa-fw"></i>
													</button>
													<button id="btn_clear" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-eraser fa-fw"></i>
													</button>
													<button id="btn_print_trf" class="btn btn-default input-sm" type="button" title="Print Trf">
														<i class="fa fa-print fa-fw"></i>
													</button>
													 
												 </span>							
											</div> 
								</div>		 
							</div> 		
							 			
							<div class="row">	
								 <div class="row col-sm-12 input-sm" align="center">    
								 		<div class="col-sm-6" >        
									 			 <!--<button id="btn_accept" type="button" class="btn btn-primary   col-sm-6"></i>Accept</button>    -->
                       						   
									 	</div>
									 	 
								 	</div>		            
								 </div>
						    </div>
					 </div>
					 <!--start of Datatable-->  
					 <div class="row">
								<div class="col-lg-12">
				                    <div class="panel panel-primary">
				                        <div class="panel-heading">
				                            <strong><i class="fa fa-shopping-cart fa-fw"></i>Details</strong>
				                        </div>
				                        <div class="panel-body">

				                            <table class="table table-striped table-hover " id="dataTables-transfer" style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
																<thead style="font-weight:bolder;font-size:14px;text-align:center;">
																	<tr >		
																		<th width="100" style="text-align:center;">Sku</th>
																		<th width="150" style="text-align:center;">Description</th>
																		 
																		<th width="50" style="text-align:center;">From</th>
																		<th width="50" style="text-align:center;">To</th>
																		<th width="150" style="text-align:center;">Qty</th>															 																											
																		 		
																	</tr>
																</thead>
																<tbody style="font-size:14px">
																	 
																</tbody>
											</table>
											<!--<table class="table table-striped table-hover" id="grand_total"   style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
												<thead>
													<tr align="right">
														<th width="110" style="font-weight:bolder;font-size:11px;">Grand Total</th>
														<th width="130" style="text-align:right;">SRP : </th>
														<th width="100" class="grnd_my_amt" style="text-align:left;"></th>
														<th width="50"  style="text-align:right;">Qty :</th>
														<th width="150" class="grnd_my_qty" style="text-align:center;padding-right:20px;"> </th>
													</tr>
												</thead>
											</table>-->
											<div class="form-group">
					                            <label>Remarks</label>
					                            <textarea disabled="disabled" id="ApprovReason" class="form-control" rows="3" style="resize:none;"></textarea>
						                    </div>
											<div class="row col-sm-5" align="center">    
										 		<div class="col-sm-6">  
										 			<button id="btn_accept" type="button" class="btn btn-primary col-sm-12 disabled">Accept</button>          
											 		<!--<button id="btn_accept" type="button" class="btn btn-primary col-sm-12 disabled"><i class="glyphicon glyphicon-thumbs-up fa-fw"></i>Confirm</button>-->
											 	</div>
											 	<div class="col-sm-6">	
											 		<button id="btn_cancel" type="button" class="btn btn-primary col-sm-12 disabled">Cancel</button>
											 	</div>
									 		</div>
				                        </div>

				                        <div class="panel-footer">
				                            
				                        </div>
				                    </div>
				                </div>
							</div><!--End of Datatable-->  

	              <?php }?>
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
