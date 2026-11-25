<?PHP  
	session_start();		
	require_once "../class-main/connection.class.php";	
	require_once "../panel/check_effect.php";
	
				 
	class designPage{
		function pageLoading(){
			$out= '<div id="loadingGif" style="display:none;z-index:99999; "><div id="myMask" class="loadmask"  style=""  ></div>';
     		$out.='<div id="myMsg" class="" style="vertical-align:middle;margin: auto;position: fixed;top: 0; left: 0; bottom: 0; right: 0;';
     		$out.=' height:100%;width:100%;z-index:99999; -moz-opacity: 0.9;opacity: .90;filter: alpha(opacity=90); ';
     		$out.=';background-position:center;background-size:200px 200px;">';
     		$out.=' <img id="loadingImgGif" src="../css-main/images/ajax-loader.gif" width="200" height ="200" style="margin: auto;position: fixed;top: 0; left: 0; bottom: 0; right: 0;" alt="My Gif" title="Loading" />';
     		$out.=' </div>';
     		$out.=' </div>';
     		return $out;
		}
		function pageBelowHeader(){
			return '';
			return '<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<!--<svg class="glyph stroked bag"><use xlink:href="#stroked-bag"></use></svg>-->
							<span class="fa fa-truck fa-4x"></span>
							<!--<i class="fa fa-calendar fa-3x"></i>-->
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large">14</div>
							<div class="text-muted">Store Delivery</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<!--<svg class="glyph stroked empty-message"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-empty-message"></use></svg>-->
							<span class="glyphicon glyphicon-transfer fa-3x"></span>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large">23</div>
							<div class="text-muted">Transfer</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<!--<svg class="glyph stroked male-user"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-male-user"></use></svg>-->
							<span class="fa fa-minus fa-4x">
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large">30</div>
							<div class="text-muted">Regular Pullout</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left"> 
							<!--<svg class="glyph stroked app-window-with-content"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-app-window-with-content"></use></svg>-->
							<!--<span class="glyphicon glyphicon-log-out fa-3x">  </span>-->
							<svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"/></svg>
						</div>
		 				<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large">24</div>
							<div class="text-muted">Total Pullout</div>
						</div>
					</div> 
				</div>
			</div>
			<div class="row"></div>';
		}	
		function pageHead(){ 
			//header("Location:http://vendorportal_test.lccgroup.com");
			return '<meta charset="utf-8"> 
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<meta name="description" content="">
					<meta name="author" content="">
					<title>Vendor Portal</title>
					<link rel="icon" type="image/png" href="../css-main/images/LCC_ICON.ico">
					<link href="../css-main/bootstrap.min.css" rel="stylesheet">
					<!--to circle icon-->
					<!--<link href="../css-main/sb-admin-2.css" rel="stylesheet">-->
					<link href="../css-main/bootstrap.mod.css" rel="stylesheet">
					<link href="../css-main/datepicker3.css" rel="stylesheet">
					<link href="../font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
					<link href="../css-main/jquery.loadmask.css" rel="stylesheet">
					<link href="../css-main/plugins/jquery.dataTables.css" rel="stylesheet">
					<!--[if lt IE 9]>
						<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
						<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>					 
						<script src="../lumino/js/html5shiv.js"></script>
						<script src="../lumino/js/respond.min.js"></script>						 
					<![endif]-->
					<!--<link href="../css-main/cssmod/bootstrap.min.css" rel="stylesheet">-->
					<link href="../css-main/cssmod/styles.css" rel="stylesheet">
					';

        }
		
		function pageNavi() {
			
			$return='<nav class="navbar-inverse navbar-fixed-top" role="navigation">
						<div class="container-fluid" style="background-color:#444">
							<div class="navbar-header">
								<div class="row" >
								  <div class="col-sm-2">								
									<img  class="img-rounded" alt="cinque tierre" style="float:left;margin:5px -10px 2px 0px;" height="40" width="50" src="../css-main/images/header-object.png" />
								  </div>
								  <div  class="col-sm-9">	
									<a id="home_gallery" class="navbar-brand"><font color="#FFFFFF"><strong>Vendor Portal</strong></font></a>	
								  </div>
								</div>
							</div>
							
							<div class="navbar-right  ">';
							if($_SESSION['uid']=='ADMIN' && $_SESSION['gid']==1){
							$return.='  <ul class="nav navbar-nav ">
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">
											<i class="fa fa-user fa-fw"></i>
											<font class="nav-text"></font>
											<i class="fa fa-caret-down"></i>

										</a>
										
										<ul class="dropdown-menu dropdown-user">
											<li hidden>
												<a href="../home.php">
													<i class="fa fa-home fa-fw"></i>
													Home
												</a>
											</li>
											<li class="divider" hidden></li>
											<li hidden>
												<a href="../control/logout.php">
													<i class="fa fa-sign-out fa-fw"></i>
													Logout
												</a>
											</li>
											
											<li class="dropdown-header" >												 												 
													Other Tools											 
											</li>
											<li class="divider"></li>
											<li  >
												<a id="add_user" href="#">
													<i class="fa fa-plus fa-fw"></i>
													Add User Account
												</a>
											</li>
											<li  >
												<a id="a_rpt" href="#">
													<i class="fa  fa-gear  fa-fw"></i>
													CMS
												</a>
											</li>
											<li  >
												<a id="delete_user" href="#">
													<i class="fa fa-minus fa-fw"></i>
													Delete User Account
												</a>
											</li>

											<li  >
												<a id="a-deactivate-user" href="#" class="dropdown-toggle" data-toggle="dropdown">
													<i class="fa fa-remove fa-fw"></i>
													Enable/Disable User Account
												</a>
												 
											</li>
											<li  >
												<a id="a_privileges" href="user_privilege.php">
													<i class="glyphicon glyphicon-gift fa-fw "></i>
													User Privileges
												</a>
											</li>											
											<li  >
												<a id="a_resetUser" href="#">
													<i class="fa fa-refresh fa-fw"></i>
													Reset User
												</a>
											</li>
											<li  >
												<a id="a_viewDwnloadLogs" href="#">
													<i class="fa  fa-eye  fa-fw"></i>
													View Download logs
												</a>
											</li>
										</ul>
									</li>
								</ul>
							 </div>';
							}else{ 
								$return.='  <ul id="myNoti" class="nav navbar-nav ">';
								  $return.='<li id="fir" > 
								          	    <a id="requestAdj" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" href="#">
													<i class="fa fa-envelope fa-fw"></i><span id="me_badge" class="label label-info" style="background:RED;"></span>										
												    <i id="caret" class="fa fa-caret-down"> </i>
												</a>
												<ul id="reqViewer" class="dropdown-menu dropdown-messages">							                         							                       
							                         <li class="divider"></li> 
								                     <li class="message-preview">
								                          <a href="#">
								                            <div class="media">
								                            <span class="pull-left">                                    
								                                  </span>
								                                  <div class="media-body">
								                                      <h5 class="media-heading"><strong></strong>
								                                      </h5>
								                                      
								                                      <p><i class="fa fa-ban fa-fw"></i> No Notification</p>
								                                  </div>
								                              </div>
								                          </a>
								                      </li>
							                    </ul>
										    </li>';
								  $return.='<li class="dropdown">
												<a class="dropdown-toggle" data-toggle="dropdown" href="#">
													<i class="fa fa-user fa-fw"></i>
													 
													<i class="fa fa-caret-down"></i>

												</a>';

										if(substr($_SESSION['uid'],0,3)=="STR" || substr($_SESSION['uid'],0,3)=="ITD" || substr($_SESSION['uid'],0,3)=="GER" || $_SESSION['uid']=="MPC00484TA"){
											$return.='<ul class="dropdown-menu dropdown-user">
												<li hidden>
													<a href="../home.php">
														<i class="fa fa-home fa-fw"></i>
														Home
													</a>
												</li>
												<li class="divider" hidden></li>
												<li hidden>
													<a href="../control/logout.php">
														<i class="fa fa-sign-out fa-fw"></i>
														Logout
													</a>
												</li>
												
												<li class="dropdown-header" >												 												 
														Other Tools											 
												</li>
												<li class="divider"></li>
												';
												
													$return.='<li><a id="receive" href="../panel/store_delivery.php">
															<i class="fa fa-arrow-circle-right fa-fw"></i>
															Receive DR
														</a>';
														
													$return.='</li>																																			 												 
												 
											</ul>';
										}else if($_SESSION['gid']==2){

										}
						$return.='</li>';
						          
						$return.='</ul>
							 </div>';
							}
						$return.='</div>
					</nav><br/>
					<div  ><p>&nbsp;</p><p>&nbsp;</p></div>
					';					
					return $return;
		}
		
		function pageSideBar() {
			date_default_timezone_set('Asia/Taipei');
			$conn = new getConnection();
			$db = $conn->PDO();
			
			$qry = "SELECT
						a.*
					FROM
						tbl_app_menu a JOIN (SELECT
												menu_id
											FROM
												`tbl_user_group_menu`
											WHERE
												group_id = ? )  b ON a.menu_id = b.menu_id
					ORDER BY
						a.menu_arrange ASC";
						
			$menu = array();
			try {
				$res = $db->prepare($qry);
				$res->execute(array($_SESSION['gid']));
				$menu = $res->fetchAll();
			} catch (Throwable $e) {
				$menu = array();
			}
			//$out='<div class="alert alert-danger col-sm-12" align="center"  ><span class="fa fa-info-circle"></span>
            //   We will be having system maintenance on October 31,2015 from 9am-10am, the system is unavailable this period of time.
            //              </div>'; 
            $out='';

            //if($_SESSION['gid']!=5){
            	try {
            		$notice= $db->prepare("SELECT * FROM tbl_cm");
					$notice->execute();
					$notice_fetch = $notice->fetchAll();
					if(!empty($notice_fetch) && isset($notice_fetch[0]['notice']) && trim($notice_fetch[0]['notice']) != ''){
						$out.='<div class="alert alert-info col-sm-12" align="center"  ><span class="fa fa-info-circle fa-fw"></span>'.$notice_fetch[0]['notice'].'</div>';
					}
            	} catch (Throwable $e) {}
            //}
			$out .= '<div class="col-sm-3 ">';

			if(count($menu) > 0) {
				$out.=	'<div class="sidebar-nav">
							<div class="navbar navbar-default  " role="navigation">
								<div class="navbar-header">
									<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
										<span class="sr-only">Toggle navigation</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
									<span class="visible-xs navbar-brand">Panel Options</span>
								</div>
								<div class="navbar-collapse collapse sidebar-navbar-collapse">
									<ul class="nav navbar-nav">';
				 
				for($i = 0; $i < count($menu); $i ++) {
									$out .= '<li>
												<a class="" href="'.$menu[$i]["menu_link"].'" '.($menu[$i]["menu_has_sub"] ? 'class="dropdown-submenu" data-toggle="dropdown"' : '').'>
													<i class="fa '.$menu[$i]["menu_ico"].' fa-fw"></i>';
									$out.=			'	'.$menu[$i]["menu_dsc"].' ';
														if($menu[$i]["menu_has_sub"]) 
															$out.='<b class="caret"></b>';	
									$out.='			</a>';
					
					if($menu[$i]["menu_has_sub"]) {
										$out .= '<ul class="dropdown-menu" area-labelled="dropdownMenu" role="menu">';
						
						$qry = "SELECT 
									a.*
								FROM
									tbl_app_menu_sub a JOIN (SELECT
																menu_id_sub 
															FROM 
																tbl_user_group_menu_sub 
															WHERE
																group_id = ?) b ON a.menu_id_sub = b.menu_id_sub 
								WHERE 
									a.menu_id = ?
								ORDER BY 
									a.menu_arrange_sub";
									
						$menu_sub = array();
						try {
							$res = $db->prepare($qry);
							$res->execute(array($_SESSION["gid"],$menu[$i]["menu_id"]));
							$menu_sub = $res->fetchAll();
						} catch (Throwable $e) {}
												
						for($j = 0; $j < count($menu_sub); $j ++) {
												$out .= '<li class="dropdown-submenu multi-level">												
															<a href="'.$menu_sub[$j]["menu_link_sub"].'">';
															if($menu_sub[$j]["menu_id"]==9){

																$out.='<i class="fa fa-thumbs-o-up fa-fw"></i>'	;
															}
															$sub=$menu_sub[$j]["menu_dsc_sub"];
															if(isset($_SESSION['isWhse'])){
																$sub=$menu_sub[$j]["menu_dsc_sub"];
																$sub=str_replace("Store", "Warehouse",$sub);
															}
															 

												$out.='		'.$sub.'
															</a>';
												$out.='';			

												//$out.='			<ul class="dropdown-menu">
												//				<li><a>Request Order</a></li>
												//				<li><a>Receive Order</a></li>
												//			</ul>
												$out.='		</li>';
						}
											$out .= '</ul>';
					}
										$out .= '</li>';
				}
				$out .=	'</ul>
						</div>
					</div>
				</div>';
			}						//$data_date=1;
									$yr = !empty($data_date) ? substr(strval($data_date),0,2) : '';
									$mon = !empty($data_date) ? substr(strval($data_date),2,2) : '';
									$day = !empty($data_date) ? substr(strval($data_date),4,2) : '';
									$whole = ($yr && $mon && $day) ? $mon.'/'.$day.'/'.$yr : date('m/d/y');
									//$data_date2=1;
									$yr2 = !empty($data_date2) ? substr(strval($data_date2),0,2) : '';
									$mon2 = !empty($data_date2) ? substr(strval($data_date2),2,2) : '';
									$day2 = !empty($data_date2) ? substr(strval($data_date2),4,2) : '';
									$whole2 = ($yr2 && $mon2 && $day2) ? $mon2.'/'.$day2.'/'.$yr2 : date('m/d/y');
									
									//get expiration of userid
									$con=new getConnection();
									$db=$con->PDO();
									$query="SELECT * FROM tbl_user_account WHERE user_id='".$_SESSION['uid']."'";
									$prepare=$db->prepare($query);
									$prepare->execute();
									$fetch=$prepare->fetchAll();									 
									$expiration=$fetch[0]["last_log_date"];					
									
									/*$qry="SELECT MAX(csldat) FROM tbl_ccn_sales_dtl";
									$prepares=$db->prepare($qry);
									$prepares->execute();
									$fets=$prepares->fetchAll();*/
									//echo count($fets);
									//echo "<p>max date:".$fets[0][0]."-</p>";
										 										

									$out .= '<div class="list-group">
									<a class="list-group-item" style="background-color:#f5f5f5">You are logged-in as: <strong>'.($_SESSION["gid"]==1?"":$_SESSION['uid']).'</strong></a>
									<a class="list-group-item">Date Now:<i class="fa fa-calendar fa-fw"></i> '.date('F j, Y').'</a>
									<a class="list-group-item">Sales Accepted Until:<i class="fa fa-calendar fa-fw"></i> '.date('F j,Y',strtotime($whole)).'</a>
									
									';
									if($_SESSION["gid"]==1){
										$out.='<a class="list-group-item">Last Update of DB :<i class="fa fa-calendar fa-fw"></i> '.date('F j,Y',strtotime($whole2)).'</a>';
									}
									$exp_display = '';
									try {
										$expiry=new check_if_expire();
										$exp_display = $expiry->display_expiration();
									} catch (Throwable $e) {}
									$out.='<a class="list-group-item">Your "Password" expires on:<i class="fa fa-calendar fa-fw"></i> '.$exp_display.'</a>';
						$out.='</div>';
						if($_SESSION["gid"]==1){
							/*$new=date("Y-m-d ");
							$old=date("y-m-d",strtotime("2015-05-20"));
							echo "old:".$old."<br>";
							echo "new:".$new;
							$od=new DateTime($old);
							$nw=new DateTime($new);
							
							$d=$nw->diff($od)->m;
							$day=$nw->diff($od)->d; 
							echo $day;*/
														
							$out.='<div class="list-group">
								   <a class="list-group-item" style="background-color:#f5f5f5"><i class="fa fa-user fa-fw"></i>User Effectivity</a>
								   <a class="list-group-item">
										<div class="row  " >
										<label class="container-fluid">Number of Months</label>
										  <div class="col-lg-9" >
											  <select id="num_mon" class="form-control " style="border:1px solid;width:100%;"><option></option>';										
												for( $x = 1; $x <= 12; $x++){
													$out.='<option>'.$x.'</option>';
												}
								$out.='		  </select>
										  </div>									  
										  <div class="col-lg-3" style="margin-left:-40px;padding-top:5px;">
											<div id="logo" class="col-xs-12"></div>
										  </div>
																												  
										</div>	
									</a> 
							</div>';
						}
						
						$out.='	</div>';
				  
			return $out;
		}
		
		function pageBanner() {
			return '<div class="row">
						<div class="banner-image panel col-sm-12">
							<a href="home.php"><div class="banner-logo"> </div></a>
							<h1 class="banner-title"><strong>CCN Portal</strong></h1>
							<p class="banner-sub-title"><strong>-At HOME kita digdi-</strong></p>
						</div>
					</div>';
		}
		
		function pageFoot($page){ 
			$out = '<script src="../js-main/jquery-2.1.1.min.js"></script>
					<script src="../js-main/bootstrap.min.js"></script>
					<script src="../js-main/bootstrap-datepicker.js"></script>
					<script src="js/sales.js"></script>
					<script src="../js-main/jquery.loadmask.min.js"></script>
					<script src="../js-main/plugins/dataTables/jquery.dataTables.min.js"></script>
					<script src="../js-main/plugins/dataTables/datatables.js"></script> 
					<script src="../js-main/lumino.glyphs.js"></script>

					<!--<script src="../../lumino/js/chart.min.js"></script>-->
					<!--<script src="../../lumino/js/chart-data.js"></script>-->
					<script src="../../lumino/js/easypiechart.js"></script>
					<script src="../../lumino/js/easypiechart-data.js"></script>
					 
					<!--<script src="js/noInspectElement.js"></script>-->
					<script>
						$(document).ready(function(){
							myTimeId=0;
						});
					</script>';
			//$out .= '<script src="../js-main/socket.io.min.js"></script>';		
			//$out.='<script src="http://localhost:8080/socket.io/socket.io.min.js"></script>';
			//$out.='<script src="http://localhost:8080/socket.io/socket.io.min.js"></script>';
			//$out.='<script src="/socket.io/2.0.3/package/lib/socket.js"></script>';
			//$out.='<script src="https://localhost:80/socket.io/socket.io.js"></script>';
			//$out.='<script src="node_modules/socket.io/lib/socket.io.js" type="text/javascript"></script>';
			//$out.='require';
			if($page == "sales_report") {
				$out .= '<script src="js/sales_page.js"></script>';
			}
			else if($page == "activate_employee") {
				$out .= '<script src="js/act_ee.js"></script>';
			}
			else if($page == "activate_vendor") {
				$out .= '<script src="js/act_vd.js"></script>';
			}
			else if($page == "list_employee") {
				$out .= '<script src="js/list_ee.js"></script>';
			}
			else if($page == "list_vendor") {
				$out .= '<script src="js/list_vd.js"></script>';
			}
			else if($page == "menu") {
				$out .= '<script src="js/menu.js"></script>';
			}
			else if($page == "group") {
				$out .= '<script src="js/group.js"></script>';
			} 
			else if($page == "store_delivery") {
				$out .= '<script src="js/store_delivery.js"></script>';
				$out .= '<script src="js/strDeliveryMultCheck.js"></script>';
				$out .= '<script src="../js-main/infragistics.core.js"></script>';
				$out .= '<script src="../js-main/infragistics.lob.js"></script>';
				$out .= '<script src="../js-main/infragistics.documents.core.js"></script>';
				$out .= '<script src="../js-main/infragistics.excel.js"></script>';

			} 
			else if($page == "inventory") {
				$out .= '<script src="js/inventory.js"></script>';
			} 
			else if($page == "adjustment") {
				$out .= '<script src="js/adjustment.js"></script>';
			} 
			else if($page == "transfer_receiving") {
				$out .= '<script src="js/transfer_receiving.js"></script>';
				$out .= '<script src="js/functions.js"></script>';
			} 
			else if($page == "transfer") {
				$out .= '<script src="js/transfer.js"></script>';
			}
			else if($page == "regular") {
				$out .= '<script src="js/regular.js"></script>';
				$out .= '<script src="js/functions.js"></script>';
			}   
			else if($page == "total"){
				$out .= '<script src="js/total.js"></script>';
				$out .= '<script src="js/functions.js"></script>';
			}
			else if($page == "addpastore") {
				$out .= '<script src="js/addPaStore.js"></script>';
			} 
			else if($page == "rpl") {
				$out .= '<script src="js/rpl.js"></script>';
			} 
			else if($page == "home"){
				//$out .= '<script src="../js-main/jquery.min.js"></script>';
				//$out .= '<script src="../js-main/xlsx.core.min.js"></script>';
			}
			else if($page == "manage_dept"){
				$out .= '<script src="js/manage_dept.js"></script>';
			}
			if(isset($_SESSION['uid']))
				$out .= '<script src="js/my_js.js"></script>';
			$out.='
				     <script>
				        $(function(){
				        	var winSizeHalf=$(window).width()/2;
						 	var miniSize=$("#miniDialog").width()/2;
						 	$("#miniDialog").hide();
						 	//alert(miniSize);
						  	//$("#miniDialog").css("marginLeft",(winSizeHalf-miniSize)+"px;");
				        });
				     	
				     </script>
			';
		 	$out.='<script src="js/general.js"></script>';		
			$out .= $this->dialogs($page);
			
			$out .= '<footer style="height:30px;">
						<div class="container-fluid text-center">
							visit our site at <a href="http://www.lcc.com.ph" target="_blank">www.lcc.com.ph</a>							 							 
						</div>
					</footer>';
			
			return $out;
        }
		
		function pageDisclaimer() {
			$conn = new getConnection();
			$db = $conn->PDO();
			
			if(!isset($_SESSION["saved_disc"])) {
				$_SESSION["saved_disc"] = false;
			}
			
			$qry = "SELECT 
						content
					FROM
						tbl_disclaimer";
						
			$res = $db->prepare($qry);
			$res->execute();
			$row = $res->fetchAll();
			
			return '<div class="row"'.($row[0]["content"] == "" ? 'hidden' : '').'>
						<div class="col-lg-12" >
							<!--<div class="disclaimer-cont">
								<i class="fa fa-warning fa-fw"></i>
								<i><strong>NOTE:</strong>&nbsp;'.($_SESSION["saved_disc"] ? '<strong>' : '').$row[0]["content"].($_SESSION["saved_disc"] ? '</strong>' : '').'</i>
							</div>-->
						</div>
					</div>';
		}
		
		 function dialogs($page) {
		 	/*****************************GLOBAL DIALOGS**************************************************/
		 
			$out = '<div class="modal fade dialog-alert" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
						<div class="modal-dialog  ">
							<div class="modal-content">
								<div class="modal-header"  style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;>
									<button class="close" aria-label="Close" data-dismiss="modal" type="button">
										<!--<span aria-hidden="true">x</span>-->
									</button>
									<h4 id="myLargeModalLabel" class=" ">
										System Says
									</h4>
								</div>
								<div class="modal-body">
									<div id="dialog_terms_and_cond" class="well well-lg">
						                        <h4><center><u>Terms And Conditions</center></u><br/></h4>
						                        <p style="font-family:serif;font-size:15px;">“CCN Supplier shall be given 45 days to collect all merchandise, fixtures, modules, etc. for pull-out.  Failure to do so, it shall be subject to forfeiture and ownership shall be transferred to LCC.”</p>
						                    </div>
									<p><center><strong><span class="span_clarify" style="font-family:serif;font-size:20px;" hidden>By clicking yes you agree <p class="terms_con_cap"> in "Terms And Condition"</p></span><br><span style="font-family:serif;font-size:20px;" class="span_are_you_sure">Are you sure?</span></strong></center></p>
								</div>

								<div class="modal-footer">
										<div class="pull-right col-md-4">	
											<div class="row col-xs-8"><button type="button" id="btn_yes" class="btn btn-primary col-xs-11">Yes</button></div>									      
											<div class="row col-xs-8"><button type="button" id="btn_no" class="btn btn-primary col-xs-11">No</button></div>																																													 
										</div>
								</div>

							</div>
						</div>
					</div>';
			$out.= '<div class="modal fade dialog-loading" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
						<div class="modal-dialog  ">
							<div class="modal-content">
								 
								<div class="modal-body">
									
								</div>

								<div class="modal-footer">
										 
								</div>

							</div>
						</div>
					</div>';		
			$outs='<div id="miniDialog" class="alert alert-info "role="alert" align="center" style="background-color:#444;padding-tops:0px;z-index:99999;position:fixed;color:#fff;width:500px;height:60px;top: 0;left: 0; opacity: 0.8;filter: alpha(opacity=80);border:7px solid #ddd;" >
						<span class="fa fa-info-circle fa-fw " style="color:white;font-size:15px;"></span><span class="mySku_msg" style="text-align:center;font-weight:bold;"></span><a href="#" class="pull-right" style=""><!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--></a>
				   </div>'		;
			$out.= '<div class="modal fade msg-dialog" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:10001;">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
						<div class="modal-dialog  ">
							<div class="modal-content">
								<div class="modal-header"  style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;>
									<button class="close" aria-label="Close" data-dismiss="modal" type="button">
										<!--<span aria-hidden="true">x</span>-->
									</button>
									<h4 id="myLargeModalLabel" class=" ">
										System Says
									</h4>
								</div>

								<div class="modal-body">
									 
						                        <h3><center><strong><p class="my_msg"></p></strong></center><br/></h3>						                       
						            								
								</div>

								<div class="modal-footer">
										<div class="pull-right col-md-3">	
											<button type="button" id="btn_yes" class="btn btn-primary col-xs-12" aria-label="Close" data-dismiss="modal">Ok</button>
																																																						
										</div>
								</div>

							</div>
						</div>
					</div>';
			$out.= '<div id="miniDialog" class="modal fade msg-dialog" tabindex="-1" data-backdrop="false"  aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:99999;">
					 	<div class="modal  " style="height: 434px;"></div>
						<div class="modal-dialog  ">
							 
								<br>
								<br>
								<br><br>
								<br>
								<br>
								<br>
								<br>
								<br> 
								<div class="modal-body" align="center">
						 			<div id="miniDialogs" class="alert alert-info "  align="center" style="background-color:#444; color:#fff; opacity: 0.8;filter: alpha(opacity=90);border:7px solid #ddd;" >
											<span class="fa fa-info-circle fa-fw " style="color:white;font-size:15px;"></span><span class="mySku_msg" style="text-align:center;font-weight:bold;"></span> 
	  								 </div>
						                        
						            								
								</div> 

							 
						</div>
					</div>';	
			$out.= '<div id="loadingDialog" class="modal fade msg-dialog" tabindex="-1" data-backdrop="false"  aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:99999;">
					 	<div class="modal  " style="height: 434px;"></div>
						<div class="modal-dialog  ">
							 
								<br>
								<br>
								<br><br>
								<br>
								<br>
								<br>
								<br>
								<br> 
								<div class="modal-body" align="center">
						 			<div id="miniDialogs" class="alert alert-info "  align="center" style="background-color:#444; color:#fff; opacity: 0.8;filter: alpha(opacity=90);border:7px solid #ddd;" >
										 <div id="loadingImgGif" style="background-image:url(../css-main/images/ajax-loader.gif);width:50px;height:50px;" alt="My Gif" title="Loading" /></div>	<span class="fa fa-info-circle fa-fw " style="color:white;font-size:15px;"></span><span class="mySku_msg" style="text-align:center;font-weight:bold;"></span> 
	  								 </div>
						                        
						            								
								</div> 

							 
						</div>
					</div>';						
			$forfeited_alert='<div class="modal fade dialog-alert2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
						<div class="modal-dialog  ">
							<div class="modal-content">
								<div class="modal-header"  style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;>
									<button class="close" aria-label="Close" data-dismiss="modal" type="button">
										<!--<span aria-hidden="true">x</span>-->
									</button>
									<h4 id="myLargeModalLabel" class=" ">
										System Says
									</h4>
								</div>
								<div class="modal-body">									 
									<p><center><strong><span class="span_clarify" style="font-family:serif;font-size:20px;" ><span style="font-family:serif;font-size:20px;" class="span_are_you_sure"></span></strong></center></p>
								</div>

								<div class="modal-footer">
										<div class="pull-right col-md-3">	
											<!--<div class="row col-xs-12"><button type="button" id="btn_yes" class="btn btn-primary col-xs-11">Yes</button></div>-->
											<div class="row col-xs-12"><button type="button" id="btn_no"  aria-label="Close" data-dismiss="modal"	 class="btn btn-primary col-xs-12">Ok</button></div>																																													 
										</div>
								</div>

							</div>
						</div>
					</div>';		
			$generic_alert='<div class="modal fade generic-alert" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
						<div class="modal-dialog  ">
							<div class="modal-content">
								<div class="modal-header"  style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;>
									<button class="close" aria-label="Close" data-dismiss="modal" type="button">
										<!--<span aria-hidden="true">x</span>-->
									</button>
									<h4 id="myLargeModalLabel" class=" ">
										System Says
									</h4>
								</div>
								<div class="modal-body">									 
									<p><center><strong><span class="span_clarify" style="font-family:serif;font-size:20px;" ><span style="font-family:serif;font-size:20px;" class="generic_txt"></span></strong></center></p>
								</div>

								<div class="modal-footer">
										<div class="pull-right col-md-4">	
											<div class="row col-xs-8"><button type="button" id="btn_yes" aria-label="Close" data-dismiss="modal"	 class="btn btn-primary col-xs-11">Yes</button></div>
											<div class="row col-xs-8"><button type="button" id="btn_no"  aria-label="Close" data-dismiss="modal"	 class="btn btn-primary col-xs-11">No</button></div>																																													 
										</div>
								</div>

							</div>
						</div>
					</div>';		
			$out.= '<div class="modal fade dialog-lookup-viewReq" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-2">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Request For Adjustment
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="tbl-view-request" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="100">Ref #</th>
															<th width="100">Adjustment Type</th>
															<th width="20">Requester</th>
															<th width="100">Sku</th>															 
															<th width="100">Description</th>	
															<th width="100">Cost</th>	
															<th width="100">Qty</th>
															<th width="100">Total Cost</th>	
															
															<th width="100">Reason</th>
															<th width="100">Date Requested</th>															 
															<th width="100">Status</th>	';
														    if($_SESSION['gid']==2)	
														   		$out.='<th width="100"><center><input id="check_all_adj" type="checkbox"/> Approval </center></th>';
														   	else
														   		$out.='<th width="100"><center></center></th>';	
														   $out.='
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>
									<div class="modal-footer">
										<div class="pull-right">';
										     if($_SESSION['gid']==2){
												$out.='<button type="button" id="submit_adj" class="btn btn-primary col-sm-12">Submit(If Checked All)</button>';
											 }	
										$out.='</div>
									</div>
								</div>
							</div>
						</div>';
			$dialog_dept= '<div class="modal fade dialog-lookup-dept" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup Department
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click Department to select.</p>
										<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-lookup-dept" style="">
													<thead>
														<tr align="center">		
															<th width="550" align="center" style="text-align:center;">Department</th>
																														
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>								
										</div>
									</div>
								</div>
							</div>
						</div>';		
			$dialog_sku= '<div class="modal fade dialog-lookup-sku" tabindex="-1" role="dialog"  aria-labelledby="myLargeModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog  '.($page=='regular'?"modal-lg":"").'" >
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup SKU
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click SKU to select.</p>
										<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-lookup-sku" style="font-size:11px;">
													<thead>
														<tr align="center">';		
													$dialog_sku.=($page=='regular'?"<th width='10'></th>":"");														
													$dialog_sku.='<th width="150" align="center" style="text-align:center;">Sku</th>
															<th width="250" >Description</th>';
													$dialog_sku.=($page=='regular'?"<th width='250'></th>":"");		
													$dialog_sku.=($page=='regular'?"<th width='250'></th>":"");		
													$dialog_sku.=';																		 
															<!--<th width="250" >Stock #</th>-->
															<!--<th width="250" >Qty</th>-->
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>								
										</div>';
							$dialog_sku.=($page=='regular'?'<a href="#"><i class="fa fa-search fa-fw"></i>View Selected<span class="mySelectedSku"></span></a>':'');
							$dialog_sku.='</div>
									<div class="modal-footer">';
										$dialog_sku.=($page=='regular'?'<button id="btn_submit_sku" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Ok(For Checked row)</button>':'');
							$dialog_sku.='
									</div>
								</div>
							</div>
						</div>';	
			$dialog_sku2= '<div class="modal fade dialog-lookup-sku2" tabindex="-1" role="dialog"  aria-labelledby="myLargeModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
							<div class="modal-dialog  modal-lg" >
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup SKU
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->';
										if($page=="store_delivery"){
											$dialog_sku2.='<div class="row col-md-5">												 
												 			<input type="file" id="files" class="btn btn-primary" name="files" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ></input>													 		 																	
										 					<p class="small text-muted">Browse Excel file.</p>
										 				  </div>';
									 	}
										$dialog_sku2.='<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-lookup-sku" style="font-size:11px;">
													<thead>
														<tr align="center">		
															<th width="50" align="center" style="text-align:center;"></th>	
															<th width="150" align="center" style="text-align:center;">Sku</th>
															<th width="250" >Description</th>';															 

												$dialog_sku2.=$page=="transfer"?'':'<th width="250">Stock #</th>';
												$dialog_sku2.='<th width="250" >Qty</th>';
												$dialog_sku2.=$page=="transfer"?'<th width="150"></th>':'';

												$dialog_sku2.='</tr>
													</thead>
													<tbody>
													</tbody>
											</table>								
										</div>
										<a href="#"><i class="fa fa-search fa-fw"></i>View Selected<span class="mySelectedSku"></span></a>
									</div>
									<div class="modal-footer">
										<button id="btn_submit_sku" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Ok(For Checked row)</button>
									</div>
								</div>
							</div>
						</div>';			
			$lookup_print='<div class="modal fade dialog-lookup-print" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-2 modal-lg">
								<div class="modal-content modal-content-5">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Print Preview
										</h4>
									</div>
									<div class="modal-body ifram-container">
										<iframe src="generate_sale/loading_pdf.html" class="dr-rpt" width="100%" height="550px"></iframe>
									</div>
								 

								</div>
							</div>
						</div>';
			$transfer_lookup='<div class="modal fade dialog-lookup-trf" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="overflow:scroll;">
							<div class="modal-dialog modal-dialog-2 modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Transfer
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-trf" style="font-size:11px;overflow:scroll;">
													<thead>
														<tr>		
															<th width="50"><center>VP-TRF #</center></th>
															<th width="150"><center>Vendor</center></th>															
															<!--<th width="30">Sku</th>	
															<th width="100">Description</th>	
															<th width="20">Qty</th>	-->
															<th width="100"><center>CCN MF #</center></th>															 
															<th width="50">From Location</th>
															<th width="10">To Location</th>															 
															<th width="100"><center>Date Created</center></th>
															<th width="10">Status</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>

								</div>
							</div>
						</div>';		
				$lookup_vendor='<div class="modal fade dialog-lookup-vendor" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-branchdvndr" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="10"><center>Vendor #</center></th>
															<th width="200">Vendor Name</th>															 
															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>
									<div class="modal-footer">
										<button id="btn_submit" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6 hidden" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';	
			   $lookup_fromLoc= '<div class="modal fade dialog-lookup-fromLoc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-fromLoc" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="10"><center>From Location</center></th>
															<th width="200">Name</th>															 
															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>
									<div class="modal-footer">
										<button id="btn_submit" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6 hidden" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';
			$lookup_pullout='<div class="modal fade dialog-lookup-regular" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="overflow:scroll;">
							<div class="modal-dialog modal-dialog-2 modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Pullout
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-regular" style="font-size:11px;overflow:scroll;">
													<thead>
														<tr>		
															<th width="50"><center>VP-Pull-Out # </center></th>
															<th width="150"><center>Vendor</center></th>															
															<!--<th width="30">Sku</th>	
															<th width="100">Description</th>	
															<th width="20">Qty</th>	-->
															<th width="100"><center>From</center></th>															 
															<th width="90">Created By</th>
															<th width="100">Date Created</th>															 
															<th width="100"><center>Pick-Up Date</center></th>
															<th width="100"><center>Actual Pick-Up Date</center></th>
															<th width="90">Status</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>

								</div>
							</div>
						</div>';
			$terms_and_con='<div class="modal fade dialog-lookup-terms" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div style="height: 120%;" class="modal-backdrop fade in"></div>
							<div class="modal-dialog" >
								<div class="modal-content">
									<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
										<p></p>
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										
										<h4 id="myLargeModalLabel" class="">
											Terms And Conditions
										</h4>

										<p></p>
									</div>
									<div class="modal-body">
									  		<div class="well well-lg">
						                        <h4>  </h4>
						                        <p style="font-family:serif;font-weight:bolder;"> “CCN Supplier shall be given 45 days to collect all merchandise, fixtures, modules, etc. for pull-out.  Failure to do so, it shall be subject to forfeiture and ownership shall be transferred to LCC.”</p>
						                    </div>						
									</div>
									
									<div class="modal-footer">
										 
									</div>
								</div>
							</div>
						</div>';
			$skuSel=' <div class="modal fade dialog-lookup-skuSel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:99999;">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">List of Selected Sku</p>
										<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-lookup-skuSel" style="font-size:11px;">
													<thead>
														<tr align="center">		
															<th width="550" align="center" style="text-align:center;">Sku</th>
															<th width="250" >Description</th>';															 
													$skuSel.=$page=='transfer'?'':($page=='regular'?'':'<th width="250" >Stock Number</th>');															 
													$skuSel.='<th width="250" >Quantity</th>															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>											
										</div>
									</div>
									<div class="modal-footer">

									</div>

								</div>
							</div>
						</div>';			
			/*********************************GLOBAL DIALOGS END***************************************************************/
			if($page == "sales_report") {
				$out .= '<div class="modal fade dialog-lookup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click ID to select.</p>
										<div class="">
											<table class="table table-striped table-hover" id="dataTables-lookup" style="font-size:11px;">
												<thead>
													<tr>
														<th align="center">ID</th>
														<th>Name</th>
														<th ><div class="check_all"><input type="checkbox"> Check All </div></th>														 
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
											<button id="btn_multiple" class="btn btn-default col-sm-12" type="button">Ok (For Checked Stores)</button>
											<div class="row" /></div>
										</div>
										
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade dialog-lookup-2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click code to select.</p>
										<div class="">
											<table class="table table-striped table-hover" id="dataTables-lookup-branch" style="font-size:11px;">
												<thead>
													<tr>
														<th>Code</th>
														<th>Branch Name</th>
														<th>Company Name</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade dialog-lookup-3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-2 modal-lg">
								<div class="modal-content modal-content-2">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Sales Viewer
										</h4>
									</div>
									<div class="modal-body ifram-container">
										<iframe src="generate_sale/loading_pdf.html" class="siframe"></iframe>
									</div>
								</div>
							</div>
						</div>';
			}
			else if($page == "activate_employee" || $page == "activate_vendor") {
				$out .= '<div class="modal fade dialog-lookup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click ID to select.</p>
										<div class="">
											<table class="table table-striped table-hover" id="dataTables-lookup" style="font-size:11px;">
												<thead>
													<tr>
														<th>ID</th>
														<th>Name</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>';
			}
			else if($page == "menu") {
				$out .= '<div class="modal fade dialog-menu" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Add Side Menu
										</h4>
									</div>
									<div class="modal-body">
										<form class="form-horizontal" action="control/add_menu.php" method="post">
											<div class="form-group">
												<label class="control-label col-md-3 input-sm" for="mdsc">Menu Description:</label>
												<div class="col-md-7 input-sm">
													<div class="col-xs-12">
														<input type="text" id="mdsc" name="mdsc" class="form-control input-sm" required></input>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 input-sm" for="mlink">Menu Link:</label>
												<div class="col-md-7 input-sm">
													<div class="col-xs-12">
														<input type="text" id="mlink" name="mlink" class="form-control input-sm" value="#"></input>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 input-sm" for="mico">Menu FA Icon:</label>
												<div class="col-md-7 input-sm">
													<div class="col-xs-12">
														<input type="text" id="mico" name="mico" class="form-control input-sm" required></input>
													</div>
												</div>
											</div>
											<hr />
											<div class="text-right">
												<button name="" type="submit" class="btn btn-primary btn-default">
													<i class="fa fa-save fa-fw"></i>
													<strong>Save</strong>
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade dialog-menu-sub" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Add Sub Menu
										</h4>
									</div>
									<div class="modal-body">
										<form class="form-horizontal" action="control/add_menu.php" method="post">
											<div class="form-group">
												<label class="control-label col-md-3 input-sm" for="mdsc">Menu Description:</label>
												<div class="col-md-7 input-sm">
													<div class="col-xs-12">
														<input type="text" id="mdsc" name="mdsc" class="form-control input-sm" required></input>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 input-sm" for="mlink">Menu Link:</label>
												<div class="col-md-7 input-sm">
													<div class="col-xs-12">
														<input type="text" id="mlink" name="mlink" class="form-control input-sm" value="#"></input>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 input-sm" for="mico">Menu FA Icon:</label>
												<div class="col-md-7 input-sm">
													<div class="col-xs-12">
														<input type="text" id="mico" name="mico" class="form-control input-sm" required></input>
													</div>
												</div>
											</div>
											<hr />
											<div class="text-right">
												<button name="" type="submit" class="btn btn-primary btn-default">
													<i class="fa fa-save fa-fw"></i>
													<strong>Save</strong>
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>';
			}
			else if($page == "group") {
				$out .= '<div class="modal fade dialog-group" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Add User Group
										</h4>
									</div>
									<div class="modal-body">
										<form class="form-horizontal" action="control/add_group.php" method="post">
											<div class="form-group">
												<label class="control-label col-md-3 input-sm" for="mdsc">Group Name:</label>
												<div class="col-md-7 input-sm">
													<div class="col-xs-12">
														<input type="text" id="gname" name="gname" class="form-control input-sm" required></input>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 input-sm" for="mlink">Group Description:</label>
												<div class="col-md-7 input-sm">
													<div class="col-xs-12">
														<input type="text" id="gdsc" name="gdsc" class="form-control input-sm"></input>
													</div>
												</div>
											</div>
											<hr />
											<div class="text-right">
												<button name="" type="submit" class="btn btn-primary btn-default">
													<i class="fa fa-save fa-fw"></i>
													<strong>Save</strong>
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>';
			}
			else if($page == "store_delivery") {
			$out.=$skuSel;
			$out.= $dialog_sku2;		
				$out.= '<div class="modal fade dialog-lookup-store" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup Store
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click Store # to select.</p>
										<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-lookup-store" style="font-size:11px;">
													<thead>
														<tr align="center">		
															<th width="550" align="center" style="text-align:center;">Store</th>
															<th width="250" >Store Name</th>
															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>								
										</div>
									</div>
									<div class="modal-footer">
										<button id="btn_submit" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6 hidden" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';
				$out.=$dialog_dept;
				$out.= '<div class="modal fade dialog-lookup-3" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click Contract # to select.</p>
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-branch3" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="50"><center>Sku</center></th>
															<th width="250">Description</th>
															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>
											<div class="row"></div>
										</div>

									</div>

								</div>
							</div>
						</div>';	
				$out.= '<div class="modal fade dialog-lookup-dr" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="overflow:scroll;">
							<div class="modal-dialog modal-dialog-2 modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Store Delivery
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-branchdr" style="font-size:11px;overflow:scroll;">
													<thead>
														<tr>		
															<th width="50"><center>VP-DR #</center></th>
															<th width="50"><center>DR #</center></th>
															<th width="100">Vendor</th>															
															<th width="50">Date Created</th>															 
															<th width="50">Status</th>
															<th width="100">Merch Approval</th>
															<th width="100">Destination</th>
															<th width="200"><center>Remarks</center></th>
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>

								</div>
							</div>
						</div>';
				$out.= $lookup_print;
				$out.= '<div class="modal fade dialog-lookup-deliveryWindow" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Delivery Window
										</h4>
									</div>
									<div class="modal-body">
										<div align="center">
											<p class="delWin_drNum" style="font-weight:bolder;text-decoration:underline;" ></p>											
										</div>
										<label>DR #</label> 							 
									 	<div class="row"> 
									 		 <div class="col-sm-12">
									 		 	<input id="delWin_dr_num" class="form-control input-sm" placeholder="DR #" name="vcode"  value="" onchange="" required="" type="text">
									 		 </div>	
									 	</div>
									 	<p></p>			
									 	<label>Delivery Date</label>						  
									 	<div class="row">
									 		 <div class="col-sm-12">									 		
												<div class="col-xs-12 input-group">
													<input id="delWin_date" type="text" class="form-control input-sm dp" placeholder="Date" name="to" value="" onchange="" required="" disabled="disabled">
													<span class="input-group-btn">
														<button id="btn_date_delWin" class="btn btn-default input-sm" type="button" title="Picker">
															<i class="fa fa-calendar fa-fw"></i>
														</button>
													</span>
												</div>												 
									 		 </div>	
									 	</div>
									 	<p></p>	
									 	<label>Remarks</label>
									 	<div class="row">
									 		 <div class="col-sm-12">	
									 			<textarea id="delWin_remarks" class="form-control" rows="3" style="resize:none;" ></textarea>
										     </div>
										</div>									 			
									 	
									</div>
								 	<div class="modal-footer">
										<button id="btn_submit_delWin" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">OK</button>
									</div>

								</div>
							</div>
						</div>';							
			}
			else if($page == "inventory") {
				$out.= '<div class="modal fade dialog-lookup-vendor" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-branchdvndr" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="20"><center>Vendor #</center></th>
															<th width="100">Vendor Name</th>															 
															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>
									<div class="modal-footer">
										<button id="btn_submit" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6 hidden" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';
						$out.= '<div class="modal fade dialog-lookup-print" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-2 modal-lg">
								<div class="modal-content modal-content-5">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Inventory Print Preview
										</h4>
									</div>
									<div class="modal-body ifram-container">
										<iframe src="generate_sale/loading_pdf.html" class="siframe" width="100%" height="550px"></iframe>
									</div>
								 

								</div>
							</div>
						</div>';
			}else if($page=='transfer'){
				/* dialog for Vendor lookup*/
						$out.= $lookup_vendor;
						/* dialog for from location lookup */
						$out.=$lookup_fromLoc;
						/* dialog for To location lookup */
						$out.= '<div class="modal fade dialog-lookup-toLoc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-toLoc" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="10"><center>From Location</center></th>
															<th width="200">Name</th>															 
															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>
									<div class="modal-footer">
										<button id="btn_submit" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6 hidden" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';
						$out.= $transfer_lookup;
						$out.=$dialog_dept;
						//$out.=$dialog_sku; 
						$out.=$lookup_print.$dialog_sku2.$skuSel;
			}else if($page=='transfer_receiving'){		
					$out.=$transfer_lookup;
					$out.=$lookup_print;
					
			}else if($page=='regular'){		
				$out.=$lookup_pullout;	
				$out.=$lookup_print;			
				$out.=$lookup_vendor;
				$out.=$lookup_fromLoc;
				$out.=$dialog_dept;
				$out.=$dialog_sku;
				$out.=$terms_and_con;
				$out.=$forfeited_alert.$skuSel;
			}else if($page=='total'){
				$out.=$lookup_pullout;	
				$out.=$lookup_print;			
				$out.=$lookup_vendor;
				$out.=$lookup_fromLoc;
				$out.=$dialog_dept;
				$out.=$dialog_sku;
				$out.=$terms_and_con;
				$out.=$forfeited_alert;
			}else if($page=='addpastore'){
					$out.= '<div class="modal fade dialog-lookup-pa" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-pa" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="10"><center><strong>User ID</strong></center></th>
															<th width="200"><center><strong>Promo Name</strong></center></th>															 															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>
									<div class="modal-footer">
										<button id="btn_submit" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6 hidden" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';
					$out.= '<div class="modal fade dialog-lookup-store" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-store" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="50"><strong>Select/Unselect</strong></th>	
															<th width="50"><strong>Store</strong></th>
															<th width="300"><strong>Store Name</strong></th>																
																													 															
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>
									<div class="modal-footer">
										<button id="btn_submit"  style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';
					$out .= '<div class="modal fade dialog-lookup-vendor" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click ID to select.</p>
										<div class="">
											<table class="table table-striped table-hover" id="dataTables-lookup-vendor" style="font-size:11px;">
												<thead>
													<tr>
														<th>Vendor ID</th>
														<th>Vendor Name</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
					</div>';
			}
			else if($page=='rpl'){
				$out.=$dialog_sku;
				$out.=$dialog_dept;
			}/*$this->dialogs()*/
			if($page == "adjustment"){
				/*$out.= '<div class="modal fade dialog-lookup-viewReq" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Request For Adjustment
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<!--<p id="sku_label" type="button" class="btn btn-primary">Sku</p> -->
										<div class="modal-lookup2" id="modal-lookup2">
											 <table class="table table-striped table-hover" id="dataTables-lookup-branchdvndr" style="font-size:11px;">
													<thead>
														<tr>		
															<th width="20"><center>Vendor #</center></th>
															<th width="100">Vendor Name</th>															 
															 
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>										
											<!--<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>-->
											<div class="row"></div>
										</div>

									</div>
									<div class="modal-footer">
										<button id="btn_submit" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6 hidden" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';*/
				$out.=' <div class="modal fade dialog-lookup-skuSel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:99998;">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">List of Selected Sku</p>
										<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-lookup-skuSel" style="font-size:11px;">
													<thead>
														<tr align="center">		
															<th width="550" align="center" style="text-align:center;">Sku</th>
															<th width="250" >Description</th>															 																												
															<th width="150" >Quantity</th>															 
															<th width="250" >Type of Adjustment</th>		
															<th width="250" >Reason</th>	
															<th width="50" ></th>		
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>											
										</div>
									</div>
									<div class="modal-footer">

									</div>

								</div>
							</div>
						</div>';	
				$out.=' <div class="modal fade dialog-adj-return" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:99998;">
							<div class="modal-dialog ">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											System Says
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Result of Adjustment Request</p>
										<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-adj-return" style="font-size:11px;">
													<thead>
														<tr align="center">		
															<th width="150" align="center" style="text-align:center;">Sku</th>
															<th width="250" >Description</th>															 																																											 
															<th width="250" >Qty</th>		
															<th width="250" >Type</th>	
															<th width="250" >Message</th>	
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>											
										</div>
									</div>
									<div class="modal-footer">

									</div>

								</div>
							</div>
						</div>';			
				$out.=$lookup_fromLoc;	
				$out.=$dialog_dept;
				$out.=$generic_alert;

					$outs='	<div class="modal fade dialog-lookup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click ID to select.</p>
										<div class="">
											<table class="table table-striped table-hover" id="dataTables-lookup" style="font-size:11px;">
												<thead>
													<tr>
														<th align="center">Store Code</th>
														<th>Name</th>
														 														 
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
											 
											<div class="row" /></div>
										</div>
										
									</div>
								</div>
							</div>
						</div>';
						$out.='	<div class="modal fade dialog-lookup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click ID to select.</p>
										<div class="">
											<table class="table table-striped table-hover" id="dataTables-lookup" style="font-size:11px;">
												<thead>
													<tr>
														<th align="center" width="100">Vendor/Store Code</th>
														<th width="100">Name</th>
														 														 
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
											 
											<div class="row" /></div>
										</div>
										
									</div>
								</div>
							</div>
						</div>';
					$out.= '<div class="modal fade dialog-lookup-adj" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-2 modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup Adjustment
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click Store # to select.</p>
										<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-lookup-adj" style="font-size:11px;">
													<thead>
														<tr align="center">		
															<th width="100">Ref #</th>
															<th width="100">Adjustment Type</th>
															<th width="20">Requester</th>
															<th width="100">Sku</th>															 
															<th width="100">Description</th>	
															<th width="100">Cost</th>	
															<th width="100">Qty</th>
															<th width="100">Total Cost</th>	
															
															<th width="100">Reason</th>
															<th width="100">Date Requested</th>															 
															<th width="100">Status</th>															   														   
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>								
										</div>
									</div>
									<div class="modal-footer">
										<button id="btn_submit" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6 hidden" type="button">Ok(For Checked Stores)</button>
									</div>
								</div>
							</div>
						</div>';	 
				$out.=$lookup_print;
				/*adjustment*/
				$out.= '<div class="modal fade dialog-lookup-sku3" tabindex="-1" role="dialog"  aria-labelledby="myLargeModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="z-index:9999;">
							 
							<div class="modal-dialog  modal-lg" >
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										<h4 id="myLargeModalLabel" class="modal-title">
											Lookup SKU
										</h4>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										<p class="text-muted" style="position:absolute;">Click SKU to select.</p>
										<div class="modal-lookup" id="modal-lookup">
											 <table class="table table-striped table-hover" id="dataTables-lookup-sku3" style="font-size:11px;">
													<thead>
														<tr align="center">		
															<th width="50" align="center" style="text-align:center;"></th>	
															<th width="150" align="center" style="text-align:center;">Sku</th>
															<th width="250" >Description</th>															 
															<th width="250" >Qty</th>
															<th width="550" >Type of Adjustment</th>
															<th width="250" >Reason</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
											</table>								
										</div>
										<a href="#"><i class="fa fa-search fa-fw"></i>View Selected<span class="mySelectedSku"></span></a>
									</div>
									<div class="modal-footer">
										<button id="btn_submit_sku" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Ok(For Checked row)</button>
									</div>
								</div>
							</div>
						</div>';		

			}
			else if($page=="manage_dept"){
				$out.=$lookup_vendor.$dialog_dept.$lookup_fromLoc.$generic_alert;
			}
			if($_SESSION['uid']=='ADMIN' && $_SESSION['gid']==1){
				$out .= '<div class="modal fade deactivate-user" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div style="height: 120%;" class="modal-backdrop fade in"></div>
							<div class="modal-dialog" >
								<div class="modal-content">
									<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
										<p></p>
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										
										<h4 id="myLargeModalLabel" class="modal-title">
											User Account Management
										</h4>
										<p></p>
									</div>
									<div class="modal-body">
										<!--<a href="#" data-dismiss="modal">asdasd</a>-->
										 
										<div class="">
											<table class="table table-striped table-hover" id="dataTables-lookups" style="font-size:11px;">
												<thead>
													<tr>
														<th align="center">User ID</th>
														<th>Status</th>
														<th ><div ><input id="dis_check_all" type="checkbox" />&nbsp;Check All</div></th>														 
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
											
											<div class="row" /></div>
										</div>
										
									</div>
								</div>
							</div>
						</div>';
				 $out .= '<div class="modal fade add-user" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div style="height: 120%;" class="modal-backdrop fade in"></div>
							<div class="modal-dialog" >
								<div class="modal-content">
									<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
										<p></p>
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										
										<h4 id="myLargeModalLabel" class="modal-title">
											Add User Account
										</h4>
										<p></p>
									</div>
									<div class="modal-body">
									 
										<div class="form-group"><label>UserId</label>
													<input type="text" class="form-control userid" value autofocus="autofocus" required />
													<p> </p>
												 
												<label>Full Name</label>
													<input type="text" class="form-control fn" />										 
													<p> </p>	
												 											
												<label>Department/Position</label>	
													<input type="text" class="form-control department" />
												<p></p>
												<label>Store</label>	
													<input type="text" class="form-control store" />
												<p></p>
										</div>										
									</div>
									
									<div class="modal-footer">
										<button id="btn_add_user" class="btn btn-default">										 
											<strong>&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;</strong>
										</button>
									</div>
								</div>
							</div>
						</div>';
				$out .= '<div class="modal fade delete-user" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div style="height: 120%;" class="modal-backdrop fade in"></div>
							<div class="modal-dialog" >
								<div class="modal-content">
									<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
										<p></p>
										<button class="close" aria-label="Close" data-dismiss="modal" type="button">
											<span aria-hidden="true">x</span>
										</button>
										
										<h4 id="myLargeModalLabel" class="modal-title">
											Delete User Account
										</h4>
										<p></p>
									</div>
									<div class="modal-body">
									 
										<div class="form-group"><label>UserId</label>
													<input type="text" class="form-control userid" value autofocus="autofocus" required />
													<p> </p>												 												 
											<p></p>
										</div>										
									</div>
									
									<div class="modal-footer">
										<button id="btn_delete_user" class="btn btn-default">										 
											<strong>&nbsp;&nbsp;Delete&nbsp;&nbsp;&nbsp;</strong>
										</button>
									</div>
								</div>
							</div>	
						</div>';	
				$out .= '<div class="modal fade msg-box" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
					<div class="modal-dialog" >
						<div class="modal-content">
							<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
								<p></p>
								<button class="close" aria-label="Close" data-dismiss="modal" type="button">
									<span aria-hidden="true">x</span>
								</button>
								
								<h4 id="myLargeModalLabel" class="modal-title">
									System Says
								</h4>
								<p></p>
							</div>
							<div class="modal-body">
							 
								 								
							</div>
							
							<div class="modal-footer">
								 
							</div>
						</div>
					</div>
				</div>';
				$out .= '<div class="modal fade change_rpt_mdl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 100%;" class="modal-backdrop fade in"></div>
					<div class="modal-dialog" style="height: 90%;" >
						<div class="modal-content" style=" ">
							<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
								<p></p>
								<button class="close" aria-label="Close" data-dismiss="modal" type="button">
									<span aria-hidden="true">x</span>
								</button>
								
								<h4 id="myLargeModalLabel" class="modal-title">
									Content Management
								</h4>
								<p></p>
							</div>
							<div class="modal-body">

								<i class="fa fa-wrench fa-fw"></i><label class="text-muted"><strong>Change Sales Report</strong></label>
								<p></p>
								<div class="row">
									<div class="col-sm-6">
										<label class="text-info">For Counter Caption</label>
									 	<input type="text" class="form-control for_counter col-sm-3" value autofocus="autofocus" required />							 								
								 	</div>
								 	<div class="col-sm-6">
										<label class="text-info">Not For Counter Caption</label>
									 	<input type="text" class="form-control not_for_counter col-sm-3" value autofocus="autofocus" required />							 								
								 	</div>								 	
							 	</div>
							 	<hr>
							 	<p></p>							 	
							 	<i class="fa fa-wrench fa-fw"></i><label class="text-muted"><strong>Change Notice</strong></label>
								<p></p>													 	
								<label class="text-info">Notice Caption</label>
							 	<textarea type="text" class="form-control notice col-sm-3" value autofocus="autofocus" required  style="resize:none;"/></textarea>							 								
								<p>&nbsp;</p>
								
							</div>	
							<div class="modal-footer">
								<button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Submit</button>							
							</div>
						</div>
					</div>
				</div>';
				$out .= '<div class="modal fade mdl-reset" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
					<div class="modal-dialog" >
						<div class="modal-content">
							<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
								<p></p>
								<button class="close" aria-label="Close" data-dismiss="modal" type="button">
									<span aria-hidden="true">x</span>
								</button>
								
								<h4 id="myLargeModalLabel" class="modal-title">
									Reset User
								</h4>
								<p></p>
							</div>
							<div class="modal-body">
							 	<div class="row">
									<div class="col-sm-12">
										<label class="text-info">USERID</label>
									 	<input type="text" id="userid" class="form-control for_counter col-sm-3" value autofocus="autofocus" required />							 								
										<p id="pSmall" class="small text-muted">&nbsp;</p>									 	
								 	</div>
								 	 							 
							 	</div>							 								
							</div>
							
							<div class="modal-footer">
								 <button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Reset</button>							
							</div>
						</div>
					</div>
				</div>';
				$out .= '<div class="modal fade mdl-privilege" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
					<div class="modal-dialog" >
						<div class="modal-content">
							<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
								<p></p>
								<button class="close" aria-label="Close" data-dismiss="modal" type="button">
									<span aria-hidden="true">x</span>
								</button>
								
								<h4 id="myLargeModalLabel" class="modal-title">
									User Privileges
								</h4>
								<p></p>
							</div>
							<div class="modal-body">
							 	<div class="row">
									<div class="col-sm-12">
										<label class="text-info">USERID</label>
									 	<input type="text" id="userid" class="form-control for_counter col-sm-3" value autofocus="autofocus" required />							 								
										<p id="pSmall" class="small text-muted">&nbsp;</p>									 	
								 	</div>
								 	 							 
							 	</div>							 								
							</div>
							
							<div class="modal-footer">
								 <button id="btn_submit" style="display: block;" id="btn_multiple" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Reset</button>							
							</div>
						</div>
					</div>
				</div>';		
				//modal view download logs--------------------------			
				$out .= '<div class="modal fade mdl-dwnloadLogs" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div style="height: 120%;" class="modal-backdrop fade in"></div>
					<div class="modal-dialog modal-dialog-2" >
						<div class="modal-content">
							<div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
								<p></p>
								<button class="close" aria-label="Close" data-dismiss="modal" type="button">
									<span aria-hidden="true">x</span>
								</button>
								
								<h4 id="myLargeModalLabel" class="modal-title">
									Download Logs
								</h4>
								<p></p>
							</div>
							<div class="modal-body">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="dataTables-example_wrapper">
							 	<!--<a href="#" data-dismiss="modal">asdasd</a>-->										 
										<div class="">
											<table class="table table-striped table-hover" id="tbl-dwnloadLogs" style="font-size:11px;">
												<thead>
													<tr>
														<th align="center"><center>Records to Download</center></th>
														<th>Records downloaded</th>
														<th>Download/Downloaded Variance</th>
														<th ><center>Date From</center></th>														 
														<th ><center>Date To</center></th>		
														<th ><center>Start Download</center></th>		
														<th ><center>End Download</center></th>		
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
											
											<div class="row" /></div>
										</div>
								</div>
															 								
							 </div>							
							<div class="modal-footer">
							</div>
						</div>
					</div>
				</div>';

			}			
			return $out;
		}
    }
?>
 