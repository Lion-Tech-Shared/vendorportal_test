<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "control/redirect.php";
	
	$change_pwd=new link_2_change_pwd();
	$change_pwd->toChangePwd();
	
	$designer = new designPage();

	if(!isset($_SESSION["uid"]) ) {
		header("Location: ../home.php");
	}
	if($_SESSION["gid"]!=2 || $_SESSION["gid"]==3) {
		//header("Location: ../home.php");
	}	
	$_SESSION['delType']='store';
	$page = "store_delivery";
	 
	$conn = new getConnection();	
	$db=$conn->PDO(); 

	/* Create DR Number */	
	$qry=" SELECT now()";
	$prepare=$db->prepare($qry);
	$prepare->execute();
	$fetch=$prepare->fetchAll();

	//$_SESSION['regular']=date('ymd-his',strtotime($fetch[0][0]))."".mt_rand(1,1000);		 
	$microtime=substr(microtime(),3,4);
	$_SESSION['dr_num']=date('ymd-his',strtotime($fetch[0][0]))."".$microtime.mt_rand(1,1000);		 

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
							<h4 style="padding-left:0px;"><span class="glyphicon glyphicon-send fa-fw"></span><Strong>Store Delivery</strong></h4>
						</div>

						<div class="col-lg-4 input-sm">
							<div class="col-xs-12 input-group"> 							 
							 <input id="dr_num" class="form-control input-sm" placeholder="Choose DR#" name="vcode" disabled="disabled" value="" onchange="" required="" type="text">												
							 <span class="input-group-btn">
								<button id="btn_find_dr" class="btn btn-default input-sm" type="button" title="Search DR">
									<i class="fa fa-search fa-fw"></i>
								</button>
								<button id="btn_print" class="btn btn-default input-sm" type="button" title="Print DR">
									<i class="fa fa-print fa-fw"></i>
								</button>
								 <?php if($_SESSION['gid']==2 || $_SESSION['gid']==1){?>
									<button id="btn_cancel_dr" class="btn btn-default input-sm" type="button" disabled="disabled" title="Cancel DR Status">
										<i class="glyphicon glyphicon-remove fa-fw"></i>
									</button>
								<?php }?>
							 </span>							
							</div> 
							</div>
						</div>
				</div>
				<div class="panel-body">	
				  <?php if($_SESSION['gid']==2){?>
					<div id="sku-lookup" class="col-lg-12">		
					<div class="row container-fluid">
							<div class="alert alert-info" align="center" style="font-weight:bolder;">
                                 <!--<p class="text-info"><span class="glyphicon glyphicon-asterisk"></span>--> VP-DR # &nbsp;&nbsp;: &nbsp; <span class="dr_num"><?php echo $_SESSION['dr_num'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;<!--<span class="glyphicon glyphicon-asterisk">&nbsp;</span>--></p>
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

										<div class="row">	
											<div class="col-sm-1 input-sm" align="right"><strong>&nbsp;Location</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="str_store" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
													<span class="input-group-btn">
															<button id="btn_search_store" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-search fa-fw"></i>
															</button>
														</span>
												</div>
											</div>	
											<div class="col-sm-1 input-sm" align="right"><strong>Select&nbsp;Sku</strong></div>												 											
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
											<!--<div class="col-sm-6" >
												<div class="col-sm-1 input-sm"><strong>DR&nbsp;#</strong></div>												 											
													<div class="col-sm-8">
														<div class="col-xs-12 input-group"><input id="contract_num" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" required="">
															 
														</div>
													</div>		
												</div>-->
													
									 	</div>											 	
									 	<p></p>
									 	<div class="row">
									 		<div class="col-sm-1 input-sm" align=" "><strong>&nbsp;Department</strong></div>												 											
											<div class="col-sm-5">
												<div class="col-xs-10 input-group"><input id="str_dept" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
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
											
											<label id="my_descr" class="col-sm-4 input-sm hidden" style="text-align:left;"></label>
													<!--<span class="input-group-btn">
														<button id="btnvclear" name="btnvclear" class="btn btn-default input-sm" type="button" title="Clear">
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
									 	<div class="row"><p></p>	 </div>
									  
									 	 			 	 								 																								   
								</div>

							</div>	
							<hr> 
							<p></p>
								<!--1
								<div class="row" align="left">		
									<div class="col-sm-12 ">	
									   <div class="row">
								 	      <div class="col-sm-5">-->	 			 						    											 											 																				 											 
											 <!--<label>Stock #</label>-->
										   <!--</div>	
										   <div class="col-sm-7">	 -->
											 <!--<input id="stock_num" type="text" class="form-control input-sm col-sm-2" placeholder="" disabled="disabled" name="vcode" value="" onchange="" required="">-->
										  <!-- </div>	 
									   </div>-->										 
									 <!--2</div>	
									 <p> </p>
									 <div class="col-sm-12">				 						    											 											 																				 											 
										 <label>Enter Quantity</label>
										 <input id="sku_qty" type="text" class="form-control input-sm col-sm-2" placeholder="" disabled="disabled" name="vcode" value="" onchange="" required="">
									 </div>									 
							 	</div>  -->
							 	<p></p> 
							 	<!--<hr>  -->
							 	<div class="row col-lg-5" align="center">    
							 		<!--<div class="col-sm-6" >        
								 		<button id="btn_post" type="button" class="btn btn-primary disabled  col-sm-12"><i class="fa fa-shopping-cart fa-fw"></i>Add to Cart</button>
								 	</div>-->
								 	<div class="col-sm-6">	
								 		<button id="btn_clear" type="button" class="btn btn-primary disabled col-sm-12"><i class="fa fa-eraser fa-fw"> </i> Clear</button>
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

	                            <table class="table table-striped table-hover " id="dataTables-lookup-branch5" style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
													<thead style="font-weight:bolder;font-size:14px;">
														<tr >		
															<th width="10">Sku</th>
															<th width="220">Desc</th>
															<th width="90" align="center"  style="text-align:center;">SRP</th>
															<th width="20">Stock#</th>
															<th width="20">Qty</th>
															<th width="20"></th>
														</tr>
													</thead>
													<tbody>
													</tbody>
								</table>
								<table class="table table-striped table-hover" id="grand_total"   style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
									<thead>
										<tr align="right">
											<th width="110" style="font-weight:bolder;font-size:11px;">Grand Total</th>
											<th width="130" style="text-align:right;">SRP : </th>
											<th width="100" class="grnd_my_amt" style="text-align:left;"></th>
											<th width="50"  style="text-align:right;">Qty :</th>
											<th width="150" class="grnd_my_qty" style="text-align:center;padding-right:20px;"> </th>
										</tr>
									</thead>
								</table>
								<div class="row col-sm-5" align="center">    
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
	              <?php }else{?>
	              <div class="row container-fluid" style="overflow:">
							<div class="alert alert-info" align="center" style="font-weight:bolder;">
                                 <p class="text-info">[ VP-DR  # : <span class="dr_num2"> </span>&nbsp;]
                                 <!-- [ Contract&nbsp;# : <span class="contract_number2"> </span>&nbsp;]</p>-->
                                <!--<p class="text-info">-->[ Vendor # : <span class="vendor2"> </span>]&nbsp;[&nbsp; Location : <span class="store2"> </span>&nbsp;]</p>
                            </div>
                             
                            <div class="panel panel-primary">
	                        <div class="panel-heading">
	                        	<?php
	                        		$qry="SELECT * FROM tbl_user_group a left join tbl_privileges b on a.group_id=b.gid  WHERE group_id=?";
	                        		$prepare=$db->prepare($qry);
	                        		$prepare->execute(array($_SESSION['gid']));
	                        		$fetch=$prepare->fetchAll();
	                        		$canReceive=$fetch[0]['canReceive'];
	                        		$canApprove=$fetch[0]['canApprove'];
	                        	?>
	                        	<?php /*if($_SESSION['gid']!=3)*/
	                        		  if($canApprove!=1){?>
	                            		<strong><i class="fa fa-shopping-cart fa-fw"></i>Your Cart</strong>
	                            <?php }else{?>
	                            	    <strong><i class="glyphicon glyphicon-thumbs-up fa-fw"></i>Delivery Approval</strong> 
	                            <?php }?>
	                        </div>
	                        <div class="panel-body">	                       
	                            <table class="table table-striped table-hover" id="dataTables-lookup-branch5" style="font-size:12px;" role="grid" aria-describedby="dataTables-example_info">
													<thead style="font-weight:bolder;font-size:13px;">
														<tr >		
															<th width="10">Sku</th>
															<th width="120">Desc</th>
															<th width="90" align="center" style="text-align:center;">SRP</th>
															<th width="50">Stock#</th>
															<th width="50" align="center">&nbsp;&nbsp;&nbsp;Qty</th>
															<?php if($canReceive==1){?>
																<th width="20">Received Qty</th>
															<?php }else{?>
																<th width="20"></th>
															<?php }?>		
														</tr>
													</thead>
													<tbody>
													</tbody>
								</table>
								<table   class="table table-striped table-hover" id="grand_total"   style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
									<thead>
										<tr align="">
											<th width="10" style="font-weight:bolder;font-size:11px;">Grand Total</th>
											<th width="90"> </th>
											<th width="50" align="right" style="text-align:right;" class="grnd_my_amt2" style=""></th>
											<th width="50"> </th>
											<th width="50" align="right" style="text-align:right;" class="grnd_my_qty2" style=""> </th>
											<th width="50"> </th>
										</tr>
									</thead>
								</table>
								<?php 
								  	

								  //if($_SESSION['gid']!=3){
								  if($canReceive==1){?>
								    <div class="form-group">
			                            <label>Remarks</label>
			                            <textarea id="ApprovReason" class="form-control" rows="3" style="resize:none;"></textarea>
				                    </div>
									<div class="row col-sm-5" align="center">    
								 		<div class="col-sm-6" >        
									 		<button id="btn_accept2" type="button" class="btn btn-primary col-sm-12 disabled"><i class="glyphicon glyphicon-thumbs-up fa-fw"></i>Accept</button>
									 	</div>
									 	<div class="col-sm-6">									 		
									 		<button id="btn_cancel2" type="button" class="btn btn-primary col-sm-12 disabled">Cancel</button>								 		
									 	</div>
							 		</div>
							 		
						 			 
						 		    
						 		<?php }else if($canApprove==1){?>
						 			<div class="form-group">
			                            <label>Remarks</label>
			                            <textarea id="ApprovReason" class="form-control" rows="3" style="resize:none;"></textarea>
				                     </div>
						 			 
						 		    <div class="row col-sm-5" align="center">   
							 			<div class="col-sm-6">								 		
										 		<button id="btn_merchApproval" type="button" class="btn btn-primary col-sm-12 disabled"><i class="glyphicon glyphicon-thumbs-up fa-fw"></i>Approve</button>								 		
										</div>
										<div class="col-sm-6"> 		
										 		<button id="btn_merchDisapproval" type="button" class="btn btn-primary col-sm-12 disabled"><i class="glyphicon glyphicon-thumbs-down fa-fw"></i> Disapprove</button>								 		
										</div>
									</div>	
						 		<?php }?>
	                        </div>

	                        <div class="panel-footer">
	                            
	                        </div>
	                    </div>
	                    <p>&nbsp;</p>
	                    <p>&nbsp;</p>
                  </div>          

	              <?php } ?>
				</div>

				<div class="panel-footer">
                            	
                </div>
                <div id="test" hidden style=" width: 100%;height: 100%;position: fixed;overflow: hidden;text-align: center; border-radius: inherit;background-color: rgba(0,0,0,0.6);z-index: 1;">test</div>
                <div  hidden style="width:800px;height:700px;border:1px solid; background: url(../css-main/images/ajax-loader.gif) 50% 50% ;">
                	
                </div>

                <?PHP echo $designer->pageDisclaimer(); ?>
			</div>
		</div>
	</div>
    
    <?PHP echo $designer->pageFoot($page);
    	  echo $designer->pageLoading();
     ?>
    
     
</body>

</html>
