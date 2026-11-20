<?PHP 
	
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	require_once "control/redirect.php";

 	if($_SESSION['gid']!=1){
 		header('Location:../home.php');
 	}
	$conn = new getConnection();	
	$db=$conn->PDO();

	$change_pwd=new link_2_change_pwd();
	$change_pwd->toChangePwd();

	/*$query=' SELECT * FROM tbl_user_group_menu_sub ';
	$query.=" WHERE group_id=? AND menu_id_sub=(SELECT menu_id_sub FROM tbl_app_menu_sub WHERE menu_dsc_sub=?)";	
	$prepare=$db->prepare($query);
	$prepare->execute(array($_SESSION['gid'],"Sales"));
	$count_fetch=$prepare->fetchAll();
	if(count($count_fetch)<1){
		header("Location: ../home.php");
	}
	*/
	$designer = new designPage();

	
	$page = "sales_report";
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
			<div class="panel panel-primary">
			    <!--panel heading-->
				<div class="panel-heading">
					<div class="scol-lg-12">
						<h4 style="padding-left:10px;"><i class="glyphicon glyphicon-gift fa-fw "></i>User Privileges</h4>
					</div>
				</div>
				 <!--panel body-->
				<div class="panel-body">
					<div class="col-lg-12" > 
						  
                        <!-- /.panel-heading -->
	                        <div class="panel-body">
	                            <div class="dataTable_wrapper">
                                
								<div class="col-sm-12">
                                	 <table class="table table-striped table-hover dataTable no-footer" id="dataTables-privileges" role="grid" aria-describedby="dataTables-example_info">
                                    	<thead>
	                                        <tr role="row">
	                                        	<th width="50" class="sorting_asc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 190px;">Group ID</th>
	                                        	<th width="210" class="sorting_asc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 190px;">User</th>
	                                        	<!--<th width="290" class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 220px;">Can View Sales </th>-->
	                                        	<th width="290" class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 200px;">Can Approve </th>
	                                        	<th width="290" class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 165px;">Can Receive </th>                                        	
	                                        	<th width="290" class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 165px;">Is Promo Assistant</th>                                        	
	                                        	<th width="290" class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 165px;">Is CCN Coordinator</th> 
	                                        	<th width="290" class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 165px;">is Locked</th>                                        	
	                                        </tr>
                                     	</thead>
                                     	<tbody>
	                                    	<?php 
	                                    		$qry="SELECT * FROM tbl_user_group a left join tbl_privileges b on a.group_id=b.gid ";
	                                    		$prepare=$db->prepare($qry);
	                                    		$prepare->execute();
	                                    		$fetch=$prepare->fetchAll();
	                                    		
	                                    		for($x=0;$x<count($fetch);$x++){ 
	                                    			$canViewSales=($fetch[$x]["canViewSales"]==1?"checked='checked'":"");
	                                    			$canApprove=($fetch[$x]["canApprove"]==1?"checked='checked'":"");
	                                    			$canReceive=($fetch[$x]["canReceive"]==1?"checked='checked'":"");
	                                    			$isPa=($fetch[$x]["IsPA"]==1?"checked='checked'":"");
	                                    			$isCoor=($fetch[$x]["isCoor"]==1?"checked='checked'":"");
	                                    			$islocked=($fetch[$x]['isLocked']==1?"checked='checked'":"");
	                                    			echo '<tr>';
	                                    			echo '<td align="center">'.$fetch[$x][0].'</td>';
	                                    			echo '<td >'.$fetch[$x][1].'</td>';
	                                    			if($fetch[$x]['isLocked']==1){
	                                    				//echo '<td align="center"></td>';
		                                    			echo '<td align="center"></td>';
		                                    			echo '<td align="center"></td>';
		                                    			echo '<td align="center"></td>';
		                                    			echo '<td align="center"></td>';
	                                    			}else{
		                                    			//echo '<td align="center"><input type="checkbox" '.$canViewSales.' class="'.($fetch[$x][0]."-1").'"/></td>';
		                                    			echo '<td align="center"><input type="checkbox" '.$canApprove.' class="'.($fetch[$x][0]."-2").'"/></td>';
		                                    			echo '<td align="center"><input type="checkbox" '.$canReceive.' class="'.($fetch[$x][0]."-3").'"/></td>';
		                                    			echo '<td align="center"><input type="checkbox" '.$isPa.' 		class="'.($fetch[$x][0]."-5").'"/></td>';
		                                    			echo '<td align="center"><input type="checkbox" '.$isCoor.' class="'.($fetch[$x][0]."-6").'"/></td>';
		                                    		}
		                                    			echo '<td align="center"><input type="checkbox" '.$islocked.' class="'.($fetch[$x][0]."-4").'"/></td>';
	                                    			echo '</tr>';
	                                    			//$data=explode("-",$fetch[$x][0]."-1");
	                                    			//echo '<tr><td>'.$data[0].'</td></tr>';
	                                    		}

	                                    		
	                                        ?>
                                    	 </tbody>
                                	 </table>
                                </div>
                           
                            <!-- /.table-responsive -->                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
					</div>
				</div>
				 <!--panel footer-->
				<div class="panel-footer"><p class="help-block">'is Locked ' means can be or cannot be selected as user group</p></div>
                <?PHP echo $designer->pageDisclaimer(); ?>
			</div>
		</div>
	</div>
    
    <?PHP echo $designer->pageFoot($page); ?>
</body>

</html>