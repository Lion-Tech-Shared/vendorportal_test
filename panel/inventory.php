<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "control/redirect.php";
	
	$change_pwd=new link_2_change_pwd();
	$change_pwd->toChangePwd();
	
	$designer = new designPage();

	if(!isset($_SESSION["uid"])) {
		header("Location: ../home.php");
	}	
	$page = "inventory";
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
	 table td,table th{text-align: center;font-size: 11px;}
	 table th{font-weight: bolder; font-size: 11px; border-top-style: solid; border-top-width: 5px; border-top: 5px;}
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
							<h4 style="padding-left:0px;"><span class="glyphicon glyphicon-th-list fa-fw"></span><Strong>Inventory</strong></h4>
						</div>
						<?php //$arr=array("MDG","MPC","ADMIN");
							$arr=array("");
							if($_SESSION['uid']==2 || in_array(substr($_SESSION['uid'],0,3),$arr) || in_array($_SESSION['uid'],$arr) ){?>
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
						<?php }?>	
					 </div>
						
				</div>
				<div class="panel-body" align="center">	
				   
	              <?php ?>
	              <div class="row container-fluid">
					<div class=" ">         
							<!----for vendor---------->
		 					<?php if($_SESSION['gid']==2){ ?>
								<div class="col-lg-11 input-sm">
									<div class="col-xs-5 input-group"> 							 
										<input id="dr_num" class="form-control input-sm" placeholder="Choose Store" name="vcode" disabled="disabled" value="" onchange="" required="" type="text">												
										<span class="input-group-btn">										 										
										<button id="btn_label_store" class="btn btn-default input-sm" type="button" title="Print DR"></button>
										<button id="btn_find_store" class="btn btn-default input-sm vendor" type="button" title="Search DR">
											<i class="fa fa-search fa-fw"></i>Store
										</button>	
										 	
										<button id="btn_print" class="btn btn-default input-sm" type="button" title="Print Inventory">
												<i class="fa fa-print fa-fw"></i>
											</button>																					 
										 </span>							
									</div> 
									 
								</div> 		
							<!----for merch user---------->	
							<?php }else{ ?>
							 
								<div class="col-lg-11 input-sm">
									<div class="col-xs-9 input-group"> 							 
										 <input id="dr_num" class="form-control input-sm" placeholder="Choose Vendor" name="vcode" disabled="disabled" value="" onchange="" required="" type="text">												

										 <span class="input-group-btn">
										 <button id="btn_label" class="btn btn-default input-sm" type="button" title="Print DR">
												 
										 </button>
										<button id="btn_find_vendor" class="btn btn-default input-sm" type="button" title="Search DR">
											<i class="fa fa-search fa-fw"></i>Vendor
										</button>
										<button id="btn_label_store" class="btn btn-default input-sm" type="button" title="Print DR">
												 
										</button>
										<button id="btn_find_store" class="btn btn-default input-sm" type="button" title="Search DR">
											<i class="fa fa-search fa-fw"></i>Store
										</button>	
										 	
										<button id="btn_print" class="btn btn-default input-sm" type="button" title="Print Inventory">
												<i class="fa fa-print fa-fw"></i>
											</button>																					 
										 </span>							
									</div> 
									 
								</div> 		
							<?php } ?>		
                            <!--<table  class="table table-striped table-hover " id="dataTables-lookup-branch5" style="font-size:10px;" role="grid" aria-describedby="dataTables-example_info">
									<thead style="font-weight:bolder;text-align:center;">
										<tr  style="font-weight:bolder;text-align:right;">		
											<th width="1">Store</th>
											<th width="100">Sku</th>
											<th width="100">Description</th>
											<th width="100">On Hand</th>
											 									 
										</tr>
									</thead>
									<tbody>
									</tbody>
							</table>				-->
						<div><p>&nbsp;</p>	</div>
						<div><p>&nbsp;</p>	</div>		
	                 </div> 
                  </div>          

	              <?php  ?>
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
