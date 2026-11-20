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
	$canReceive=$fetch[0]['canReceive'];

	/* Create Transfer Number */
	if($canReceive==1){
		$qry=" SELECT now()";
		$prepare=$db->prepare($qry);
		$prepare->execute();
		$fetch=$prepare->fetchAll();

		//$_SESSION['regular']=date('ymd-his',strtotime($fetch[0][0]))."".mt_rand(1,1000);		 
		$microtime=substr(microtime(),3,4);
		$_SESSION['regular']=date('ymd-his',strtotime($fetch[0][0]))."".$microtime.mt_rand(1,1000);		 
	}else{
		//header("Location:home.php");
	}

	$page="regular";
	 
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
							<h4 style="padding-left:0px;"><span class="glyphicon glyphicon-minus-sign">  </span>&nbsp;<Strong>Regular Pull-Out <?php // Randomize sleeping time
 							if($_SESSION['gid']==2) echo 'Approval'; ?></strong></h4>
						</div>

						<div class="col-lg-5 input-sm">
							<div class="col-xs-12 input-group"> 							 
							 <input id="txt_pullout" class="form-control input-sm" placeholder="Choose pullout #" name="vcode" disabled="disabled" value="" onchange="" required="" type="text">												
							 <span class="input-group-btn">
								<button id="btn_find_pullout" class="btn btn-default input-sm" type="button" title="Search Trf">
									<i class="fa fa-search fa-fw"></i>
								</button>
								<button id="btn_clear_pullout" class="btn btn-default input-sm" type="button" title="Clear Pullout">
									<i class="fa fa-eraser fa-fw"></i>
								</button>
								<button id="btn_print_pullout" class="btn btn-default input-sm" type="button" title="Print Trf">
									<i class="fa fa-print fa-fw"></i>
								</button>
								<?php if($canReceive==1){ ?>
									<button id="btn_picked" class="btn btn-default input-sm hidden" type="button" title="Items Picked Up">
										 Picked Up<p class="fa fa-question fa-fw"></p>
									</button>  
								<?php } ?>
							 </span>							
							</div> 
							</div>
						</div>
				</div>
				<div class="panel-body">	
				  <?php if($canReceive==1){/****start of can recieve*****/?>
					<div id="sku-lookup" class="col-lg-12">		
					<div class="row container-fluid">
							<div class="alert alert-info" align="center" style="font-weight:bolder;">
                                 <p class="text-info"><!--<span class="glyphicon glyphicon-asterisk"></span>-->VP-Pull-Out # &nbsp;&nbsp; : &nbsp;&nbsp; <span class="dr_num"></span><?php echo $_SESSION['regular']; ?><!--<span class="glyphicon glyphicon-asterisk">&nbsp;</span>--></p>
                            </div>
									<div class="well col-sm-12 input-sm" align="center" style="font-weight:bolder;padding:5px;" hidden>
		                               <!--<p class="text-info"><span class="glyphicon glyphicon-asterisk"></span> DR # : <span class="dr_num">1231239120</span></p>-->
		                            </div>
									 
								</div>	
					      <div class="well col-lg-12" align="center">						 						 
							  <div class="row">
								<!--<div class="col-sm-2" style="border:0px solid;background:url('../css-main/images/images3.png') no-repeat;background-size:contain;width:120px;height:60px;" />								
								</div>-->
								
							 
								<div class="col-sm-12" style="border:0px solid;">	
										<p></p>			
									 	 					  
									 	<div class="row">
									 			
									 	</div>
									 	<p></p>	
									 	<div class="row">
											<div class="col-sm-1 input-sm" align="left"><strong>Vendor</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="str_store" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
													<span class="input-group-btn">
															<button id="btn_search_vendor" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-search fa-fw"></i>
															</button>
														</span>
												</div>
											</div>
											<div class="col-sm-1 input-sm" align="left"><strong>Department</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="txtDept" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
													<span class="input-group-btn">
															<button id="btn_search_dept" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-search fa-fw"></i>
															</button>
														</span>
												</div>
											</div>	
										</div>
										<p></p>	 
					 					 
										  
										<div class="row">
											<div class="col-sm-1 input-sm" align="left"><strong>From</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="idFrom" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
													<span class="input-group-btn">
															<button id="btn_search_from" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-search fa-fw"></i>
															</button>
														</span>
												</div>
											</div>	 	
											<div class="col-sm-1 input-sm" align="left"><strong>&nbsp;Sku</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group">
													<input id="txt_sku" type="text" class="form-control input-sm" placeholder="" disabled="disabled" name="vcode" value="" onchange="" required="">
													<span class="input-group-btn">
														<button id="btn_search_sku2" class="btn btn-default input-sm" type="button" title="Lookup">
															<i class="fa fa-search fa-fw"></i>
														</button>														
													</span>
												</div>											 																									
											</div> 																					

									 	</div>	
									  
									 	<p></p>
									  
									 	<!--<div class="row">							    
											<div class="col-sm-1 input-sm" align="left"><strong>&nbsp;Qty</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group">
													<input id="txt_qty" type="text" class="form-control input-sm" placeholder="" disabled="disabled"  name="vcode" value="" onchange="" required="">
													<span class="input-group-btn">
														<button id="btn_clear_qty" class="btn btn-default input-sm" type="button" title="Lookup">
															<i class="fa fa-eraser fa-fw"></i>
														</button>
													</span>
												</div>
											</div>										 											 					
									 	</div>-->			
									 	<!--div id="div_on_hand" class="col-sm-4 small text-muted input-sm" align="center"></div>	-->
									 	 									 	 			 	 								 																								 
								</div>

							</div>	
							<hr> 							  
							 	<div class="row col-lg-5" align="center">    
							 		<!--<div class="col-sm-6" >        
								 		<button id="btnAddToCart" type="button" class="btn btn-primary   col-sm-12"><i class="fa fa-shopping-cart fa-fw"></i>Add to Cart</button>
								 	</div>-->
								 	<div class="col-sm-6">	
								 		<button id="btn_clear" type="button" class="btn btn-primary  col-sm-12"><i class="fa fa-eraser fa-fw"></i> Clear</button>
								 	</div>
							 	</div>
		                     </div>

		                     <p></p>
							<hr>
							<p></p>
					</div>
					
					<div class="col-lg-12" >
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            Your Cart 
	                        </div>
	                        <div class="panel-body">

	                            <table class="table table-striped table-hover " id="dataTables-regular-pullout" style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
													<thead style="font-weight:bolder;font-size:14px;">
														<tr >		
															<th width="30">Sku</th>
															<th width="150">Description</th>
															<th width="150">Vendor</th>
															<th width="150">From</th>															 
															<th width="30">Qty</th>															 																											
															<!--<th width="100">Pick-up Date</th>		-->
														</tr>
													</thead>
													<tbody>
														<!--<td>3080235</td>
														<td>Burlington Mens Socks</td>
														<td>570009 : Burlington Industries</td>
														<td>100 : Tabaco Department Store</td>
														<td>307 : TCL Central- Legazpi- Whse</td>
														<td><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>-->
													</tbody>
								</table>
								
								<table class="table table-striped table-hover" id="grand_total"   style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
									<thead>
										<tr align="right">
											<th width="80" style="font-weight:bolder;font-size:11px;">Grand Total</th>
											<th width="150" style="text-align:right;">  </th>
											<th width="150" style="text-align:right;">  </th>
											<th width="150" style="text-align:right;">  </th>											
											<th width="120" class="grnd_my_amt" style="text-align:left;"></th>
											<th width="50"  style="text-align:left;" class="grand_qty"></th>
											<th width="100" class="grnd_my_qty" style="text-align:center;padding-right:20px;"> </th>
										</tr>
									</thead>
								</table>
								<div class="form-group">
			                            <label>Remarks</label>
			                            <textarea id="ApprovReason" class="form-control" rows="3" style="resize:none;"></textarea>
				                </div>
				                 
								<div class="row col-lg-5" align="center">    
							 		<div class="col-sm-6" >        
								 		<button id="btn_accept" type="button" class="btn btn-primary col-sm-12 disabled"><i class="glyphicon glyphicon-thumbs-up fa-fw"></i>Confirm</button>
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
	              <?php }/***end of if canReceive***/?>

	              <?php /***For vendor**********/ 
	              		if($_SESSION['gid']==2){

	              ?>
	              	<div class="col-lg-12">
                     
               	    </div>
               	    <div class="form-group">
                            <label>Details</label>
                            <textarea id="txt_dtls" class="form-control" rows="5" style="resize:none;"></textarea>
                            <span class="pckDate small text-muted">&nbsp;</span>
                    </div>
                    <div class="form-group">                    	 
						<label><strong>Pick-Up&nbsp;Date</strong></label>
					 
				 									 		
						<div class="col-xs-6 input-group">
							<input id="delWin_date" type="text" class="form-control input-sm dp" placeholder="Date" name="to" value="" onchange="" required="" disabled="disabled">
							<span class="input-group-btn">
								<button id="btn_date_delWin" class="btn btn-default input-sm" type="button" title="Picker">
									<i class="fa fa-calendar fa-fw"></i>
								</button>
							</span>
						</div>												 
				 					    
                    </div>
                    
               	    
					<p>&nbsp;</p>
               	    <button id="btn_confirm_pick_up" type="button" class="btn btn-primary btn-lg col-lg-12">Confirm Pick Up Schedule</button>
               	    
               	    <p>&nbsp;</p>
               	    <p>&nbsp;</p>
               	    <p>&nbsp;</p>
               	    <p>&nbsp;</p>
               	 	<pre align="center" class="col-lg-12 col-md-offset-0"><a id="a_terms_cons" href="#"><center>Terms and Conditions</center></a></pre>
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
