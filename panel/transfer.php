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

		$_SESSION['trf_num']=date('ymd-his',strtotime($fetch[0][0]))."".mt_rand(1,1000);		 
	}else{
		//header("Location:home.php");
	}
	$page="transfer";
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
	.grnd_my_qty2{text-align:center;padding-rights:25	px;}

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
							<h4 style="padding-left:0px;"><span class="glyphicon glyphicon-transfer"></span>&nbsp;<Strong>Transfer</strong></h4>
						</div>

						<div class="col-lg-5 input-sm">
							<div class="col-xs-12 input-group"> 							 
							 <input id="txt_trf" class="form-control input-sm" placeholder="Choose Trf #" name="vcode" disabled="disabled" value="" onchange="" required="" type="text">												
							 <span class="input-group-btn">
								<button id="btn_find_trf" class="btn btn-default input-sm" type="button" title="Search Trf">
									<i class="fa fa-search fa-fw"></i>
								</button>
								<button id="btn_print_trf" class="btn btn-default input-sm" type="button" title="Print Trf">
									<i class="fa fa-print fa-fw"></i>
								</button>
								 <?php if($canReceive==1){//if($_SESSION['gid']==2 || $_SESSION['gid']==1){?>
									<button id="btn_update" class="btn btn-default input-sm" type="button"  title="Update Inventory">
										 
										<p class="fa fa-edit fa-fw"></p>Update Inventory
									</button>
								<?php }?>
							 </span>							
							</div> 
						</div>
					</div>
				</div>
				<div class="panel-body">	
				  <?php if($canReceive==1){?>
					<div id="sku-lookup" class="col-lg-12">		
					<div class="row container-fluid">
							<div class="alert alert-info" align="center" style="font-weight:bolder;">
                                 <p class="text-info"><!--<span class="glyphicon glyphicon-asterisk"></span>-->VP-Transfer # &nbsp;&nbsp; : &nbsp;&nbsp; <span class="dr_num"></span><?php echo $_SESSION['trf_num']; ?><!--<span class="glyphicon glyphicon-asterisk">&nbsp;</span>--></p>
                            </div>
									<div class="well col-sm-12 input-sm" align="center" style="font-weight:bolder;padding:5px;" hidden>
		                               <!--<p class="text-info"><span class="glyphicon glyphicon-asterisk"></span> DR # : <span class="dr_num">1231239120</span></p>-->
		                            </div>  
									 
								</div>	
					      <div class="well col-lg-12" align="center">						 						 
							  <div class="row">
								<!--<div class="col-sm-2" style="border:0px solid;background:url('../css-main/images/images3.png') no-repeat;background-size:contain;width:120px;height:60px;" />								
								</div>-->
								
							 
								<div class="col-lg-12" style="border:0px solid;">	

										<div class="row">
											<div class="col-sm-1 input-sm" align="left"><strong>Vendor</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="str_store" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
													<span class="input-group-btn">
															<button id="btn_search_store" class="btn btn-default input-sm" type="button" title="Clear">
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
											<div class="col-sm-1 input-sm" align="left"><strong>CCN&nbsp;MF&nbsp;#</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="ccn_mf" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" required="">
													 <span class="input-group-btn">
													 <button id="btn_clear_ccnmf" class="btn btn-default input-sm" type="button" title="Lookup">
														<i class="fa fa-eraser fa-fw"></i>
													 </button>
												</span>
												</div>
											</div>											
											
											
											<!--<div class="col-sm-1 input-sm"><strong>Store</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="contract_num" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" required="">
													<span class="input-group-btn">
															<button id="btnvclear2" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-eraser fa-fw"></i>
															</button>
														</span>
												</div>
											</div>-->
											<!--<label id="my_label" class="col-sm-5 input-sm" style="text-align:left;"> </label>-->
													<!--<span class="input-group-btn">
														<!--<button id="btnvclear" name="btnvclear" class="btn btn-default input-sm" type="button" title="Clear">
															<i class="fa fa-eraser fa-fw"></i>
														</button>-->
														<!--<button id="btn_find_contract" name="btnlu" class="btn btn-default input-sm" type="button" title="Lookup">
															<i class="fa fa-search fa-fw"></i>
														</button>-->
														<!--<button id="btnvclear2" name="btnvclear" class="btn btn-default input-sm" type="button" title="Clear">
															<i class="fa fa-search fa-fw"></i>
														</button>-->
													<!--</span>-->
											
													
									 	</div>											 	
									 	<p></p>
									 	<div class="row">
									 		<div class="col-sm-1 input-sm" align="left"><strong>To</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="txtToLoc" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
													<span class="input-group-btn">
															<button id="btn_search_to" class="btn btn-default input-sm" type="button" title="Clear">
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
									 	 
									 	<div class="row">
									 		
									 	</div>		
										 
										<!--<div class="row">							    
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
									 	</div>	-->
									 	 
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
									 	</div>	-->		
									 	<!--<div id="div_on_hand" class="col-sm-4 small text-muted input-sm" align="center"></div>-->
									 	 
									 	 			 	 								 																								   
								</div>

							</div>	
							 
							 
																 
							 	<hr>  
							 	<div class="row col-lg-5" align="center">    
							 		<!--<div class="col-sm-6" >        
								 		<button id="btnAddToCart" type="button" class="btn btn-primary   col-sm-12"><i class="fa fa-shopping-cart fa-fw"></i>Add to Cart</button>
								 	</div>-->
								 	<div class="col-sm-5">	
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
	                            <strong><i class="fa fa-shopping-cart fa-fw"></i>Your Cart</strong>
	                        </div>
	                        <div class="panel-body">

	                            <table class="table table-striped table-hover " id="dataTables-cart-transfer" style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
													<thead style="font-weight:bolder;font-size:14px;">
														<tr >		
															<th width="30">Sku</th>
															<th width="150">Description</th>
															<th width="190">Vendor</th>
															<th width="180">From</th>
															<th width="180">To</th>
															<th width="30">Qty</th>															 																											
															<th width="100">CCN Mf #</th>		
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
			                            <textarea id="ApprovReason" class="form-control" rows="3" style="resize:none;"></textarea>
				                </div>
								<div class="row col-lg-5" align="center">    
							 		<div class="col-md-6" >        
								 		<button id="btn_accept" type="button" class="btn btn-primary col-lg-12 disabled"><i class="glyphicon glyphicon-thumbs-up fa-fw"></i>Confirm</button>
								 	</div>
								 	<div class="col-md-6">	
								 		<button id="btn_cancel" type="button" class="btn btn-primary col-lg-12 disabled">Cancel</button>
								 	</div>
						 		</div>
	                        </div>

	                        <div class="panel-footer">
	                            
	                        </div>
	                    </div>
	                </div>
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
