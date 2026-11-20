<?PHP
	session_start();	
	class designPage{		
		function pageHead(){
			return '<title>Welcome to LCC CCN Portal</title>
					<link rel="icon" type="image/png" href="css-main/images/LCC_ICON.ico">
					<link href="css-main/bootstrap.min.css" rel="stylesheet">
					<link href="css-main/bootstrap.mod.css" rel="stylesheet">
					<link href="font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
					<link href="css-main/jquery.loadmask.css" rel="stylesheet">
					<!--[if lt IE 9]>
						<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
						<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
					<![endif]-->';
        }
		
		function pageNavi($page) {
			
			$home = "";
			$about = "";
			$contact = "";
			$login = "";
			
			if($page == "home") {
				$home = "active";
			}
			else if($page == "about") {
				$about = "active";
			}
			else if($page == "contact") {
				$contact = "active";
			}
			else if($page == "login") {
				$login = "active";
			}
		
			$out = '<nav class="navbar-inverse" role="navigation">
						<div class="container-fluid" style="background-color:#444">
						   <!-- Brand and toggle get grouped for better mobile display -->
						 
							<div class="navbar-header">								 																	 		
								<!--<font style="color:white;">CCN</font><font style="color:gray;">Portal</font>
									<strong class="cust-logo"></strong>-->
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-body">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							 
								<img  class="img-rounded" alt="cinque tierre" style="float:left;margin:5px -10px 2px 0px;" height="40" width="50" src="css-main/images/header-object.png" />	
								<a id="home_gallery" class="navbar-brand"><font color="#FFFFFF"><strong>CCN Portal</strong></font></a>	
							</div>
							<!-- Collect the nav links, forms, and other content for toggling -->
							<div class="collapse navbar-collapse navbar-right" id="navbar-body">
								
								<ul class="nav navbar-nav">
									<li class="'.$home.'">
										<!--<a href="home.php">
											<i class="fa fa-home"></i>
											Homes
										</a>-->
									</li>
									<!--<li class="'.$about.'" hidden>
										<a href="about.php">
											<i class="fa fa-info-circle"></i>
											About
										</a>
									</li>
									<li class="'.$contact.'" hidden>
										<a href="contact.php">
											<i class="fa fa-phone"></i>
											Contact Us
										</a>
									</li>-->';
									
			if(!isset($_SESSION["passed"]) || !$_SESSION["passed"]) {
				$out1 = 			'<!--<li class="'.$login.'" hidden>
										<a href="login.php">
											<i class="fa fa-unlock"></i>
											Login
										</a>
									</li>-->';
			}
			else {
				$out1 =				'<!--<li class="dropdown" hidden>
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">
											<i class="fa fa-user"></i>
											&nbsp;'.$_SESSION["uid"].'&nbsp;
											<span class="caret"></span>
										</a>
										<ul class="dropdown-menu" role="menu">
											<li>
												<a href="panel/home.php">
													<i class="fa fa-gear fa-fw"></i>
													Panel
												</a>
											</li>
											<li class="divider"></li>
											<li>
												<a href="control/logout.php">
													<i class="fa fa-sign-out fa-fw"></i>
													Logout
												</a>
											</li>
										</ul>
									</li>-->';
			}
								
			$out .=				'</ul>
							</div>
						</div>
					</nav><br/><br>';
					
			return $out;
		}
		
		function pageBanner() {
			return '<div class="row" hidden>
						<div class="banner-image panel col-sm-12">
							<a href="home.php" hidden><div class="banner-logo"><img class="banner-logo-img" src="css-main/images/header-object.png" /></div></a>
							<h1 class="banner-title"><strong style="border: white solid thin; padding:3px;"><font style="color:white;">CCN</font><font style="color:#337AB7;">Portal</font></strong></h1>
							<p class="banner-sub-title" hidden><strong>-At HOME kita digdi-</strong></p>
						</div>
					</div>';
		}
		
		function pageFoot(){
			return '<script src="js-main/jquery-2.1.1.min.js"></script>
					<script src="js-main/bootstrap.min.js"></script>
					<script src="panel/js/login_js.js"></script>
					<footer style="position:fixed; height:30px; bottom:0; width:100%;padding-right:15px;padding-left:15px;">
						<div class="container-fluid text-center">
							<ol class="breadcrumb" hidden>
								<li>
									<a href="home.php">
										<i class="fa fa-home fa-fw"></i>
										Home
									</a>
								</li>
								<li style="display:none;">
									<a href="about.php">
										<i class="fa fa-info-circle fa-fw"></i>
										About
									</a>
								</li>
								<li>
									<a href="contact.php">
										<i class="fa fa-phone fa-fw"></i>
										Contact Us
									</a>
								</li>
							</ol>
							visit our site at <a href="http://www.lcc.com.ph" target="_blank">www.lcc.com.ph</a>					 							 
						</div>
					</footer>';
        }
		function modal(){
			
			return '<div id="modal_forgot_pwd" class="modal fade" role="dialog">
						<div style="height: 100%;" class="modal-backdrop fade in"></div>
						<div class="modal-dialog">
						  <div class="modal-content">
							 <div class="modal-header" style="background-color:#F5F5F5;border-color:GRAY;color:BLACK;padding-left:15px !important;">
							  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							  <p></p>
							  <p class=""><strong><i class="fa fa-send fa-fw"></i>Send link to your email</strong></p>
							  
							 </div>
							 
							 <div class="modal-body" align="left">								 								 
								 <p>User Id</p>         
								 <div id="login-form-group" class="form-group">
								 <p><input id="myuserid" class="form-control" type="text" placeholder="UserId" /></p>																
								 </div>
						   		 <p class="small text-muted">(Send link to your email, follow the link then set your password)</p> 
							</div>
							<div class="modal-footer">
								<button id="btn_send_link" class="btn btn-default" ><i class="fa fa-send fa-fw"></i>Send link</button>             
							</div>
						  </div>
						</div>
						
					</div>'; 
			 
		}
		 
    }
?>