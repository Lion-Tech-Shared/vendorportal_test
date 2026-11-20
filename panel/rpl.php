<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	$designer = new designPage();
	
	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}	
	$page = "rpl";
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
        <?PHP echo $designer->pageBelowHeader(); ?>    
		<?PHP echo $designer->pageSideBar(); ?>
        
        <div class="col-sm-9">
        	<!--<div class="row-cont">-->
			<div class="panel panel-default" >            
				<div class="panel-heading">
                	<!---<div class="col-lg-12">---->
					<div>
						<h4 style="padding-left:1px;"><i class="fa fa-refresh fa-fw"> </i>RPL</h4>
					</div>
				</div>
                <div class="panel-body" style="border:1px solid #FFF;">
					  <div class="panel-group" id="accordion">
					                                <div class="panel panel-default">
					                                    <div class="panel-heading">
					                                        <h4 class="panel-title">
					                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Sku Setup</a>
					                                        </h4>					                                        
					                                    </div>
					                                    <div id="collapseOne" class="panel-collapse collapse in">
					                                        <div class="panel-body">
					                                        		<div class="row">
					                                        				<div class="col-xs-3">
																				<div class="col-xs-12 input-group">
																					<span class="input-group-btn">
																							<button class="btn btn-primary input-sm" type="button" title="Clear">
																								Dept
																							</button>
																					</span>
																					<input id="txt_dept" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="" type="text" />
																					<span class="input-group-btn">
																							<button id="btn_search_dept" class="btn btn-default input-sm" type="button" title="Clear">
																								<i class="fa fa-search fa-fw"></i>
																							</button>
																					</span>
																					
																				</div>
																			</div>

					                                        		    	<div class="col-xs-3">
																				<div class="col-xs-12 input-group">
																					<span class="input-group-btn">
																							<button class="btn btn-primary input-sm" type="button" title="Clear">
																								Sku
																							</button>
																					</span>
																					<input id="txt_sku" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="" type="text" />
																					<span class="input-group-btn">
																							<button id="btn_search_sku" class="btn btn-default input-sm" type="button" title="Clear">
																								<i class="fa fa-search fa-fw"></i>
																							</button>
																					</span>
																					
																				</div>
																			</div>

																			<div class="col-xs-2">
																				<div class="col-xs-12 input-group">
																					<span class="input-group-btn">
																							<button class="btn btn-primary input-sm" type="button" title="Clear">
																								Min 
																							</button>
																					</span>
																					<input id="txt_min" class="form-control input-sm" placeholder="" name="vcode" value="" onchange=""   required="" type="text" />
																					 																				
																				</div>
																		 	</div>

																		 	<div class="col-xs-2">
																				<div class="col-xs-12 input-group">
																					<span class="input-group-btn">
																							<button class="btn btn-primary input-sm" type="button" title="Clear">
																								Max
																							</button>
																					</span>
																					<input id="txt_max" class="form-control input-sm" placeholder="" name="vcode" value="" onchange=""   required="" type="text" />
																					 																				
																				</div>
																		 	</div>
																		 	<div class="col-xs-2">
																		 					<button id="btn_clear_rpl" class="btn btn-primary input-sm" type="button" title="">
																								Clear 
																							</button>																				 
																							<button id="btn_add_rpl" class="btn btn-primary input-sm" type="button" title="">
																								 Add 
																							</button>																					 																																					
																		 	</div>
																		 
																	</div>
																	<hr/>
																	<p></p>
																	<div class="row">
						                                            	<!--datatables-------->					                                            	 
								                                        <div class="col-lg-12" >
															                    <div class="panel panel-default">
															                        <div class="panel-heading">
															                            <strong><i class="fa fa-gear fa-fw"></i>My Sku Setup</strong>
															                        </div>
															                        <div class="panel-body">														                        	
															                        	<!--Table-->
															                            <table class="table table-striped table-hover " id="dataTables-rpl" style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
																								<thead style="font-weight:bolder;font-size:14px;">
																									<tr >		
																										<th style="text-align:center;width:100px;" width="80">Sku</th>
																										<th style="text-align:center" style="width:150px;">Description</th>
																										<th style="text-align:center" width="100">Minimum</th>																												 
																										<th style="text-align:center" width="100">Maximum</th>
																										<th style="text-align:center" style="width:10px;"> </th>
																										<th style="text-align:center" style="width:10px;"> </th>
																										
																									</tr>
																								</thead>
																								<tbody>
																								</tbody>
																						</table>	
																						<!--End table---->																		 																			 
															                        </div>

															                        <div class="panel-footer" align="right">
															                            	<!--<button type="button" class="btn btn-primary">Add/Update</button>-->
															                        </div>
															                    </div>
														                </div>
						                                            	<!--End datatables------>
					                                            	</div>
					                                        </div>
					                                    </div>
					                                </div>
					                                <div class="panel panel-default">
					                                    <div class="panel-heading">
					                                        <h4 class="panel-title">
					                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Schedule Setup</a>
					                                        </h4>
					                                    </div>
					                                    <div id="collapseTwo" class="panel-collapse collapse in">
					                                        <div class="panel-body">
					                                        	<!--row---->
					                                           	<div class="row">					                                        				 
																			<div class="col-xs-4">
																				<div class="col-xs-12 input-group">
																					<span class="input-group-btn">
																							<button class="btn btn-primary input-sm" type="button" title="Clear">
																								Schedule Code 
																							</button>
																					</span>
																					<input id="txt_sked_code" class="form-control input-sm" placeholder="" name="vcode" value="" onchange=""   required="" type="text" />
																					 																				
																				</div>
																		 	</div>

																		 	<div class="col-xs-4">
																				<div class="col-xs-12 input-group">
																					<span class="input-group-btn">
																							<button class="btn btn-primary input-sm" type="button" title="Clear">
																								Vendor
																							</button>
																					</span>
																					<input id="txt_vendor" class="form-control input-sm" placeholder="" name="vcode" value="" onchange=""   required="" type="text" disabled="disabled" />
																					<span class="input-group-btn">
																							<button id="btn_search_vendor" class="btn btn-default input-sm" type="button" title="Clear">
																								<i class="fa fa-search fa-fw"></i>
																							</button>
																					</span>																		
																				</div>
																		 	</div>
																		 	<div class="col-xs-2">
																		 					<button id="btn_clear_rpl_sked" class="btn btn-primary input-sm" type="button" title="">
																								Clear 
																							</button>																				 
																							<button id="btn_add_rpl_sked" class="btn btn-primary input-sm" type="button" title="">
																								 Add 
																							</button>																					 																																					
																		 	</div>
																		 
																</div>
																<!--end row---->
																<hr/>
																<p></p>
																<div class="row"><!--row-->								                                            			                                            	
							                                        <div class="col-lg-12" ><!--datatables-------->			
														                    <div class="panel panel-default">
														                        <div class="panel-heading">
														                            <strong><i class="fa fa-gear fa-fw"></i>My Schedule Setup</strong>
														                        </div>
														                        <div class="panel-body">														                        	
														                        	<!--Table-->
														                            <table class="table table-striped table-hover " id="dataTables-rpl-sked" style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
																							<thead style="font-weight:bolder;font-size:14px;">
																								<tr >		
																									<th style="text-align:center;width:100px;" width="80">Sku</th>
																									<th style="text-align:center" style="width:150px;">Description</th>
																									<th style="text-align:center" width="100">Minimum</th>																												 
																									<th style="text-align:center" width="100">Maximum</th>
																									<th style="text-align:center" style="width:10px;"> </th>
																									<th style="text-align:center" style="width:10px;"> </th>
																									
																								</tr>
																							</thead>
																							<tbody>
																							</tbody>
																					</table>	
																					<!--End table---->																		 																			 
														                        </div>

														                        <div class="panel-footer" align="right">
														                            	<!--<button type="button" class="btn btn-primary">Add/Update</button>-->
														                        </div>
														                    </div>
													                </div><!--End datatables------>					                                            	
				                                            	</div> <!--End Row-->

					                                        </div>
					                                    </div>
					                                </div>
					                                <div class="panel panel-default">
					                                    <div class="panel-heading">
					                                        <h4 class="panel-title">
					                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Collapsible Group Item #3</a>
					                                        </h4>
					                                    </div>
					                                    <div id="collapseThree" class="panel-collapse collapse">
					                                        <div class="panel-body">
					                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					                                        </div>
					                                    </div>
					                                </div>
					                            </div>
                </div>
            <?PHP echo $designer->pageDisclaimer(); ?>
        	</div>
        </div>
	</div>
	<div id="min_max_div" class="alert alert-info" style="text-align:center;width:400px;z-index:10;position:absolute;top:300px;left:550px;margin:0 100px;border:3px solid;border-color:lightBLUE;">
                                
                                <span class="span_rpl_msg">Minimum/Maximum Updated</span>
                                 
                            </div>
	<?PHP echo $designer->pageFoot($page); ?>
</body>
</html>