<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "control/redirect.php";
	require_once "../panel/check_effect.php";
	$designer = new designPage();
	
	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}
	if($_SESSION['gid']==2 || $_SESSION['gid']==18){
		//header("Location: ../home.php");
	}else{
		//header("Location: ../home.php");
	}			
	//update if expiry date is null
	
	$check_expir_if_null=new check_if_expire();
	//$check_expir_if_null->update_expiry();	
		
	//check if expire
	$getClass=new check_if_expire();
	$getClass->isExpire();	
	
	$change_pwd=new link_2_change_pwd();
	$change_pwd->toChangePwd();
	
	$conn=new getConnection();
	$db=$conn->PDO();
	/* Get data if can Receive */
	$qryCanRec=" SELECT canApprove,canReceive,isCoor,IsPA FROM tbl_privileges WHERE gid=? ";
	$prepare=$db->prepare($qryCanRec);
	$prepare->execute(array($_SESSION['gid']));
	$fetch=$prepare->fetchAll();
	$canReceive=$fetch[0][1];
	$isCoor=$fetch[0]['isCoor'];
	$isPa=$fetch[0]['IsPA'];

	$page = "adjustment";
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
	

        
        <script>
            //var socket = io.connect('http://localhost:80');
        </script>
</head>
<style>
	.sf{font-family:;}
</style>
<body>
	<div id="wrapper">
	<?PHP echo $designer->pageNavi(); ?>
	</div>
	<div class="container-fluid">
		<?PHP echo $designer->pageBelowHeader(); ?>
		<?PHP echo $designer->pageSideBar(); ?>
		<!--<h1>Communication with socket.io!</h1>-->
		<div class="col-sm-9">
        	
			<div class="panel panel-default">
				<div class="panel-heading">
					<!--<div style="font-weight:bold; font-family:cambria; font-size:15px;">						
						 <span><i class="fa  fa-wrench fa-fw"></i>ADJUSTMENT </span>
					</div>-->
					<div class="row">
						<div class="col-lg-7">
							<h4 style="padding-left:0px;"><div style="font-weight:bold;font-size:15px;">						
						 		<span><i class="fa  fa-wrench fa-fw"></i>ADJUSTMENT </span>
							</div></h4>
						</div>

						<div class="col-lg-5 input-sm">
							<div class="col-xs-12 input-group"> 							 
							 <input id="txt_adj" class="form-control input-sm" placeholder="Choose Adjustment" name="vcode" disabled="disabled" value="" onchange="" required="" type="text">												
							 <span class="input-group-btn">
								<button id="btn_find_adj" class="btn btn-default input-sm" type="button" title="Search Trf">
									<i class="fa fa-search fa-fw"></i>
								</button>
								<button id="btn_clear_adj" class="btn btn-default input-sm" type="button" title="Clear Adj Field">
									<i class="fa fa-eraser fa-fw"></i>
								</button>
								<button id="btn_print_adj" class="btn btn-default input-sm" type="button" title="Print Trf">
									<i class="fa fa-print fa-fw"></i>
								</button>
								 
							 </span>							
							</div> 
						</div>
					</div>

				</div>
				<?php if($isPa==1){?>
				<div class="form-group">							
					<div class="col-md-8  ">
						<div class="col-xs-6 	input-group">
							<span class="input-group-btn">
								<button id="btnlu" name="btnlu" class="btn btn-default input-sm" type="button" title="Lookup">
									<i class="fa fa-search fa-fw"></i>
								</button>								 						
							</span>
							<input id="vcode" class="form-control input-sm" placeholder="Select Vendor Code" name="vcode" value="" onchange="" disabled="disabled" required="" type="text">						
							<span class="input-group-btn">								 
								<button id="btnvclear" name="btnvclear" class="btn btn-default input-sm" type="button" title="Clear">
									<i class="fa fa-eraser fa-fw"></i>
								</button>								
							</span>
						</div>
					</div>	
					<p></p>								 		
				</div>
				

                <div class="panel-body">
					<!--<div class="col-lg-12" >
						<div class="panel panel-default">
							<div class="panel-body">
                            	 
                            </div>
                        </div>
                    </div>-->
                    <p>&nbsp;</p>	

                   <div class="well" align="LEFT">
	                    <form>                    	
						 	<!--<div class="form-group">							 
								<div class="col-md-6 input-sm">
									<div class="col-xs-12 input-group">
										<input id="store" class="form-control input-sm" placeholder="Select Store Code" name="store" value="" onchange="" disabled="disabled" type="text">
										<span class="input-group-btn">
											<button id="btnsclear" name="btnsclear" class="btn btn-default input-sm" type="button" title="Clear">
												<i class="fa fa-eraser fa-fw"></i>
											</button>
											<button id="btnlu_2" name="btnlu_2" class="btn btn-default input-sm" type="button" title="Lookup">
												<i class="fa fa-search fa-fw"></i>
											</button>
										</span>
									</div>
								</div>						 
							</div>-->					
	 						<div class="form-group">
											<label><strong>From</strong></label>												 											
											 
											 
												<div class="col-xs-12 input-group"><input id="idFrom" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="" type="text">
													<span class="input-group-btn">
															<button id="btn_search_from" class="btn btn-default input-sm" type="button" title="Clear">
																<i class="fa fa-search fa-fw"></i>
															</button>
														</span>
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
							<div class="form-group">
								<label><strong>Department</strong></label>	
								 
									<div class="col-xs-12 input-group"><input id="str_dept" type="text" class="form-control input-sm" placeholder="" name="vcode" value="" onchange="" disabled="disabled" required="">
										<span class="input-group-btn">
												<button id="btn_search_dept" class="btn btn-default input-sm" type="button" title="Clear">
													<i class="fa fa-search fa-fw"></i>
												</button>
											</span>
									</div>
								 
							</div>
							<div class="form-group">
									<label><strong>Sku</strong></label>	
									<div class="col-xs-12 input-group"> 							 
									 <input id="txt_mySku" class="form-control input-sm" placeholder="Choose Sku" name="vcode" disabled="disabled" value="" onchange="" required="" type="text">												
									 <span class="input-group-btn">
										<button id="btn_search_sku2" class="btn btn-default input-sm" type="button" title="Search Trf">
											<i class="fa fa-search fa-fw"></i>
										</button>										 										 									
									 </span>							
									</div> 
								
								<div class="row"></div>
							</div>
							<div class="form-group">
								<a href="#" id="viewTotal"><i class="fa fa-search fa-fw"></i>Review Overall Selected<span class="mySelectedSkuTotal">(0)</span></a>
							</div>
							<div id="sse">
        <!-- <a href="javascript:WebSocketTest()">Run WebSocket</a>-->
      </div>
		                    <!--<div class="form-group">
			                    <label>SKU</label>
			                    <input id="ASku" class="form-control" >
			                    <p id="small" class="small text-muted">Press enter after SKU input.</p>	
			                </div>-->
			                 <!--<div class="form-group">
			                    <label>Quantity</label>
			                    <input id="Aqty" class="form-control" >
			                    
			                </div>-->
			                <!-- <div class="form-group">
		                        <label>Type of Adjustment</label>
		                        <select id="AType" class="form-control" >
		                        <option></option>
		                        <?php	
		                        	/*$con=new getConnection();
		                        	$db=$con->PDO();	                
		                        	$qry='SELECT * FROM tbl_type_adj ORDER BY type ASC';
		                        	$prepare=$db->prepare($qry);
		                        	$prepare->execute();
		                        	$fetch=$prepare->fetchAll();
		                        	for($i=0;$i<count($fetch);$i++){
			                            echo '<option value='.$fetch[$i][2].'>'.$fetch[$i][1].'</option>';
		                            }*/
		                          
		                        ?>	-->
		                        	
		                        	<!--<option value="-">Short/Over(-)</option>
		                            <option value="-">REGULAR PULL-OUT(-)</option>
		                            <option value="-">TOTAL PULL-OUT(-)</option>
		                            <option value="+">PHYSICAL ADJUSTMENT(+)</option>
		                            <option value="-">PHYSICAL ADJUSTMENT(-)</option>
		                            <option value="-">PILFERAGE/SL(-)</option>
		                            <option value="+">OVER DELIVERY(+)</option>
		                            <option value="-">RETURN TO VENDOR(RTV)(-)</option>
									 <option value="+">OTHERS</option>-->	                            	                        
		                           
		                        <!-- </select>
		                    </div>-->
		                     <!--<div class="form-group">
	                            <label>Reason for Adjustment</label>
	                            <textarea id="AReason" class="form-control" rows="3"   style="resize:none;"></textarea>
		                     </div>-->
	                     	<button id="ASubmit" type="button" class="btn btn-primary">&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;</button>
	                     	<button id="Acancel" type="button" class="btn btn-primary">&nbsp;&nbsp;&nbsp;Cancel&nbsp;&nbsp;&nbsp;</button>
	                     </form>
	                 </div>    
                </div>

                <?PHP echo $designer->pageDisclaimer(); ?>
                <div class="panel-footer"></div>
                <?php } ?>
			</div>
		</div>
	</div>
	
    <?PHP echo $designer->pageFoot($page); ?>
    
</body>

</html>