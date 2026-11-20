<?PHP
	require $_SERVER["DOCUMENT_ROOT"]."/supplierportal/class-main/connection.class.php";
	require $_SERVER["DOCUMENT_ROOT"]."/supplierportal/control/functions.php";
	date_default_timezone_set('Asia/Taipei');
	session_start();
	
	if(!isset($_SESSION["uid"])) {
		header("Location: ../../login.php");
	}
	
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$dboard = "active";
	$smonitor = "";
	$sdigi = "";
	$sstat = "";
	$imonitor = "";
	$idigi = "";
	$istat = "";
	$maintenance = "";
	$rege = "";
	$acte = "";
	$regs = "";
	$acts = "";
	$records = "";
	$elist = "";
	$slist = "";
	
	$rc = "";
	$vc = "";
	$ec = "";
	
	$country = ARRAY(
		'AD' => 'Andorra',
		'AE' => 'United Arab Emirates',
		'AF' => 'Afghanistan',
		'AG' => 'Antigua and Barbuda',
		'AI' => 'Anguilla',
		'AL' => 'Albania',
		'AM' => 'Armenia',
		'AN' => 'Netherlands Antilles',
		'AO' => 'Angola',
		'AQ' => 'Antarctica',
		'AR' => 'Argentina',
		'AS' => 'American Samoa',
		'AT' => 'Austria',
		'AU' => 'Australia',
		'AW' => 'Aruba',
		'AZ' => 'Azerbaijan',
		'BA' => 'Bosnia and Herzegovina',
		'BB' => 'Barbados',
		'BD' => 'Bangladesh',
		'BE' => 'Belgium',
		'BF' => 'Burkina Faso',
		'BG' => 'Bulgaria',
		'BH' => 'Bahrain',
		'BI' => 'Burundi',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BN' => 'Brunei Darrussalam',
		'BO' => 'Bolivia',
		'BR' => 'Brazil',
		'BS' => 'Bahamas',
		'BT' => 'Bhutan',
		'BV' => 'Bouvet Island',
		'BW' => 'Botswana',
		'BY' => 'Belarus',
		'BZ' => 'Belize',
		'CA' => 'Canada',
		'CC' => 'Cocos (keeling) Islands',
		'CD' => 'Congo, Democratic Peoples Republic',
		'CF' => 'Central African Republic',
		'CG' => 'Congo, Republic of',
		'CH' => 'Switzerland',
		'CI' => 'Cote dIvoire',
		'CK' => 'Cook Islands',
		'CL' => 'Chile',
		'CM' => 'Cameroon',
		'CN' => 'China',
		'CO' => 'Colombia',
		'CR' => 'Costa Rica',
		'CS' => 'Serbia and Montenegro',
		'CU' => 'Cuba',
		'CV' => 'Cap Verde',
		'CS' => 'Christmas Island',
		'CY' => 'Cyprus Island',
		'CZ' => 'Czech Republic',
		'DE' => 'Germany',
		'DJ' => 'Djibouti',
		'DK' => 'Denmark',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'DZ' => 'Algeria',
		'EC' => 'Ecuador',
		'EE' => 'Estonia',
		'EG' => 'Egypt',
		'EH' => 'Western Sahara',
		'ER' => 'Eritrea',
		'ES' => 'Spain',
		'ET' => 'Ethiopia',
		'FI' => 'Finland',
		'FJ' => 'Fiji',
		'FK' => 'Falkland Islands (Malvina)',
		'FM' => 'Micronesia, Federal State of',
		'FO' => 'Faroe Islands',
		'FR' => 'France',
		'GA' => 'Gabon',
		'GB' => 'United Kingdom (GB)',
		'GD' => 'Grenada',
		'GE' => 'Georgia',
		'GF' => 'French Guiana',
		'GG' => 'Guernsey',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GL' => 'Greenland',
		'GM' => 'Gambia',
		'GN' => 'Guinea',
		'GP' => 'Guadeloupe',
		'GQ' => 'Equatorial Guinea',
		'GR' => 'Greece',
		'GS' => 'South Georgia',
		'GT' => 'Guatemala',
		'GU' => 'Guam',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HK' => 'Hong Kong',
		'HM' => 'Heard and McDonald Islands',
		'HN' => 'Honduras',
		'HR' => 'Croatia/Hrvatska',
		'HT' => 'Haiti',
		'HU' => 'Hungary',
		'ID' => 'Indonesia',
		'IE' => 'Ireland',
		'IL' => 'Israel',
		'IM' => 'Isle of Man',
		'IN' => 'India',
		'IO' => 'British Indian Ocean Territory',
		'IQ' => 'Iraq',
		'IR' => 'Iran (Islamic Republic of)',
		'IS' => 'Iceland',
		'IT' => 'Italy',
		'JE' => 'Jersey',
		'JM' => 'Jamaica',
		'JO' => 'Jordan',
		'JP' => 'Japan',
		'KE' => 'Kenya',
		'KG' => 'Kyrgyzstan',
		'KH' => 'Cambodia',
		'KI' => 'Kiribati',
		'KM' => 'Comoros',
		'KN' => 'Saint Kitts and Nevis',
		'KP' => 'Korea, Democratic Peoples Republic',
		'KR' => 'Korea, Republic of',
		'KW' => 'Kuwait',
		'KY' => 'Cayman Islands',
		'KZ' => 'Kazakhstan',
		'LA' => 'Lao Peoples Democratic Republic',
		'LB' => 'Lebanon',
		'LC' => 'Saint Lucia',
		'LI' => 'Liechtenstein',
		'LK' => 'Sri Lanka',
		'LR' => 'Liberia',
		'LS' => 'Lesotho',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourgh',
		'LV' => 'Latvia',
		'LY' => 'Libyan Arab Jamahiriya',
		'MA' => 'Morocco',
		'MC' => 'Monaco',
		'MD' => 'Moldova, Republic of',
		'MG' => 'Madagascar',
		'MH' => 'Marshall Islands',
		'MK' => 'Macedonia',
		'ML' => 'Mali',
		'MM' => 'Myanmar',
		'MN' => 'Mongolia',
		'MO' => 'Macau',
		'MP' => 'Northern Mariana Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MS' => 'Montserrat',
		'MT' => 'Malta',
		'MU' => 'Mauritius',
		'Mv' => 'Maldives',
		'MW' => 'malawi',
		'MX' => 'Mexico',
		'MY' => 'Malaysia',
		'MZ' => 'Mozambique',
		'NA' => 'Namibia',
		'NC' => 'New Caledonia',
		'NE' => 'Niger',
		'NF' => 'Norfolk Island',
		'NG' => 'Nigeria',
		'NI' => 'Nicaragua',
		'NL' => 'Netherlands',
		'NO' => 'Norway',
		'NP' => 'Nepal',
		'NR' => 'Nauru',
		'NU' => 'Niue',
		'NZ' => 'New Zealand',
		'OM' => 'Oman',
		'PA' => 'Panama',
		'PE' => 'Peru',
		'PF' => 'French Polynesia',
		'PG' => 'papua New Guinea',
		'PH' => 'Phillipines',
		'PK' => 'Pakistan',
		'PL' => 'Poland',
		'PM' => 'St. Pierre and Miquelon',
		'PN' => 'Pitcairn Island',
		'PR' => 'Puerto Rico',
		'PS' => 'Palestinian Territories',
		'PT' => 'Portugal',
		'PW' => 'Palau',
		'PY' => 'Paraguay',
		'QA' => 'Qatar',
		'RE' => 'Reunion Island',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'SA' => 'Saudi Arabia',
		'SB' => 'Solomon Islands',
		'SC' => 'Seychelles',
		'SD' => 'Sudan',
		'SE' => 'Sweden',
		'SG' => 'Singapore',
		'SH' => 'St. Helena',
		'SI' => 'Slovenia',
		'SJ' => 'Svalbard and Jan Mayen Islands',
		'SK' => 'Slovak Republic',
		'SL' => 'Sierra Leone',
		'SM' => 'San Marino',
		'SN' => 'Senegal',
		'SO' => 'Somalia',
		'SR' => 'Suriname',
		'ST' => 'Sao Tome and Principe',
		'SV' => 'El Salvador',
		'SY' => 'Syrian Arab Republic',
		'SZ' => 'Swaziland',
		'TC' => 'Turks and Caicos Islands',
		'TD' => 'Chad',
		'TF' => 'French Southern Territories',
		'TG' => 'Togo',
		'TH' => 'Thailand',
		'TJ' => 'Tajikistan',
		'TK' => 'Tokelau',
		'TM' => 'Turkmenistan',
		'TN' => 'Tunisia',
		'TO' => 'Tonga',
		'TP' => 'East Timor',
		'TR' => 'Turkey',
		'TT' => 'Trinidad and Tobago',
		'TV' => 'Tuvalu',
		'TW' => 'Taiwan',
		'TZ' => 'Tanzania',
		'UA' => 'Ukraine',
		'UG' => 'Uganda',
		'UM' => 'US Minor Outlying Islands',
		'US' => 'United States',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VA' => 'Holy See (City Vatican State)',
		'VC' => 'Saint Vincent and the Grenadines',
		'VE' => 'Venezuela',
		'VG' => 'Virgin Islands (British)',
		'VI' => 'Virgin Islands (USA)',
		'VN' => 'Vietnam',
		'VU' => 'Vanuatu',
		'WF' => 'Wallis and Futuna Islands',
		'WS' => 'Western Samoa',
		'YE' => 'Yemen',
		'YT' => 'Mayotte',
		'YU' => 'Yugoslavia',
		'ZA' => 'South Africa',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);
	
	if(isset($_GET["active"])) {
		switch($_GET["active"]) {
			case md5(1):
					$dboard = "";
					$smonitor = "active";
					$sdigi = "active";
				break;
			case md5(2):
					$dboard = "";
					$smonitor = "active";
					$sstat = "active";
				break;
			case md5(3):
					$dboard = "";
					$imonitor = "active";
					$idigi = "active";
				break;
			case md5(4):
					$dboard = "";
					$imonitor = "active";
					$istat = "active";
				break;
			case md5(5):
					$dboard = "";
					$maintenance = "active";
					$rege = "active";
				break;
			case md5(6):
					$dboard = "";
					$maintenance = "active";
					$acte = "active";
				break;
			case md5(7):
					$dboard = "";
					$maintenance = "active";
					$regs = "active";
				break;
			case md5(8):
					$dboard = "";
					$maintenance = "active";
					$acts = "active";
				break;
			case md5(9):
					$dboard = "";
					$records = "active";
					$elist = "active";
				break;
			case md5(10):
					$dboard = "";
					$records = "active";
					$slist = "active";
				break;
			case md5(99):
			case md5(100):
					$dboard = "";
					$smonitor = "";
					$sdigi = "";
					$sstat = "";
					$imonitor = "";
					$idigi = "";
					$istat = "";
					$maintenance = "";
					$rege = "";
					$acte = "";
					$regs = "";
					$acts = "";
					$records = "";
					$elist = "";
					$slist = "";
					
					$rc = "";
					$vc = "";
					$ec = "";
				break;
			default:
					header("Location: home.php");
				break;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Supplier Portal [Panel]</title>
	
	<link rel="icon" type="image/png" href="../../css-main/images/LCC_ICON.ico">
	<link href="../css/imports.css" rel="stylesheet">
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">
					<i class="fa fa-gear"></i>
					<!-- <i class="glyphicon glyphicon-cog"></i> -->
					[Panel]
				</a>
            </div>
            <!-- /.navbar-header -->
			
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>
						<i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
						<li>
							<a href="../../home.php">
								<i class="fa fa-home fa-fw"></i>
								My Home
							</a>
                        </li>
                        <!--<li>
							<a href="#">
								<i class="fa fa-user fa-fw"></i>
								My Profile
							</a>
                        </li>
                        <li>
							<a href="#">
								<i class="fa fa-gear fa-fw"></i>
								My Settings
							</a>
                        </li>-->
                        <li class="divider"></li>
                        <li>
							<a href="../../control/logout.php">
								<i class="fa fa-sign-out fa-fw"></i>
								Logout
							</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <!--<li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                        </li>-->
						<li>
							<a class="<?PHP echo $dboard; ?>" href="home.php">
								<i class="fa fa-dashboard fa-fw"></i>
								Dashboard
							</a>
                        </li>
                        <li class="<?PHP echo $smonitor; ?>">
                            <a href="#">
								<i class="fa fa-money fa-fw"></i>
								Sales Monitoring
								<span class="fa arrow"></span>
							</a>
							<ul class="nav nav-second-level">
                                <li>
                                    <a class="<?PHP echo $sdigi; ?>" href="home.php?active=<?PHP echo md5(1); ?>">Sales Report</a>
                                </li>
                                <!--<li>
                                    <a class="<?PHP //echo $sstat; ?>" href="home.php?active=<?PHP //echo md5(2); ?>">Sales Statistics</a>
                                </li>-->
                            </ul>
                        </li>
                        <!--<li class="<?PHP //echo $imonitor; ?>">
                            <a href="#">
								<i class="fa fa-cube fa-fw"></i>
								Inventory Monitoring
								<span class="fa arrow"></span>
							</a>
							<ul class="nav nav-second-level">
                                <li>
                                    <a class="<?PHP //echo $idigi; ?>" href="home.php?active=<?PHP //echo md5(3); ?>">Digital Presentation</a>
                                </li>
                                <li>
                                    <a class="<?PHP //echo $istat; ?>" href="home.php?active=<?PHP //echo md5(4); ?>">Inventory Statistics</a>
                                </li>
                            </ul>
						</li>-->
						<?PHP if($_SESSION["gid"] != 2) { ?>
						<li class="<?PHP echo $maintenance; ?>">
                            <a href="#">
								<i class="fa fa-gear fa-fw"></i>
								Maintenance
								<span class="fa arrow"></span>
							</a>
							<ul class="nav nav-second-level">
                                <!--<li>
                                    <a class="<?PHP //echo $rege; ?>" href="home.php?active=<?PHP //echo md5(5); ?>">Enroll Employee</a>
                                </li>-->
								<?PHP if($_SESSION["gid"] == 1) { ?>
                                <li>
                                    <a class="<?PHP echo $acte; ?>" href="home.php?active=<?PHP echo md5(6); ?>">Register Employee</a>
                                </li>
								<?PHP } ?>
								<!--<li>
                                    <a class="<?PHP //echo $regs; ?>" href="home.php?active=<?PHP //echo md5(7); ?>">Enroll Vendor</a>
                                </li>-->
                                <li>
                                    <a class="<?PHP echo $acts; ?>" href="home.php?active=<?PHP echo md5(8); ?>">Register Vendor</a>
                                </li>
                            </ul>
						</li>
						<li class="<?PHP echo $records; ?>">
							<a href="">
								<i class="fa fa-list-alt fa-fw"></i> 
								Report
								<span class="fa arrow"></span>
							</a>
							<ul class="nav nav-second-level">
								<?PHP if($_SESSION["gid"] == 1) { ?>
								<li>
									<a class="<?PHP echo $elist; ?>" href="home.php?active=<?PHP echo md5(9); ?>">Employee List</a>
								</li>
								<?PHP } ?>
								<li>
									<a class="<?PHP echo $slist; ?>" href="home.php?active=<?PHP echo md5(10); ?>">Vendor List</a>
								</li>
							<ul>
                        </li>
						<?PHP } ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
				<?PHP if(!isset($_GET["active"])) { ?>
                <div class="col-lg-12" >
                    <h3 class="page-header">Dashboard</h3>
                </div>
				<?PHP }
				else if(isset($_GET["active"]) && ($_GET["active"] == md5(1) || $_GET["active"] == md5(2))) { ?>
				<div class="col-lg-12" >
                    <h3 class="page-header">Sales Review</h3>
                </div>
				<?PHP }
				else if(isset($_GET["active"]) && ($_GET["active"] == md5(3) || $_GET["active"] == md5(4))) { ?>
				<div class="col-lg-12" >
                    <h3 class="page-header">Inventory Review</h3>
                </div>
				<?PHP }
				else if(isset($_GET["active"]) && $_GET["active"] == md5(5)) { ?>
				<div class="col-lg-12" >
                    <h3 class="page-header">Create Employee Profile</h3>
                </div>
				<?PHP }
				else if(isset($_GET["active"]) && $_GET["active"] == md5(6)) { ?>
				<div class="col-lg-12" >
                    <h3 class="page-header">Activate Employee Account</h3>
                </div>
				<?PHP }
				else if(isset($_GET["active"]) && $_GET["active"] == md5(7)) { ?>
				<div class="col-lg-12" >
                    <h3 class="page-header">Create Vendor Profile</h3>
                </div>
				<?PHP }
				else if(isset($_GET["active"]) && $_GET["active"] == md5(8)) { ?>
				<div class="col-lg-12" >
                    <h3 class="page-header">Activate Vendor Account</h3>
                </div>
				<?PHP }
				else if(isset($_GET["active"]) && $_GET["active"] == md5(9)) { ?>
				<div class="col-lg-12" >
                    <h3 class="page-header">Employee Record</h3>
                </div>
				<?PHP }
				else if(isset($_GET["active"]) && $_GET["active"] == md5(10)) { ?>
				<div class="col-lg-12" >
                    <h3 class="page-header">Vendor Record</h3>
                </div>
				<?PHP } ?>
            </div>
			<?PHP if(isset($_GET["active"]) && $_GET["active"] == md5(1)) { ?>
			<div class="row">
				<div class="col-lg-6">
					<?PHP //echo date("F d, Y H:i:s"); ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-sort fa-fw"></i>
							Sales Selector
						</div>
						<div class="panel-body">
							<form class="form-horizontal">
								<div class="form-group">
									<label class="col-md-3 control-label input-sm" for="cmb_year">Year:</label>
									<div class="col-md-5">
										<select onchange="" id="cmb_year" class="form-control input-sm">
											<option class="2013" value="2013">2013</option>
											<option class="2014" value="2014">2014</option>
										</select>
									</div>
									<!--<label class="col-md-3 control-label" for="cmb_month"><em>[current]</em></label>-->
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label input-sm" for="cmb_month">Month:</label>
									<div class="col-md-5">
										<select onchange="setMonthWeeks()" id="cmb_month" class="form-control input-sm">
											<option class="1" value="1">January</option>
											<option class="2" value="2">February</option>
											<option class="3" value="3">March</option>
											<option class="4" value="4">April</option>
											<option class="5" value="5">May</option>
											<option class="6" value="6">June</option>
											<option class="7" value="7">July</option>
											<option class="8" value="8">August</option>
											<option class="9" value="9">September</option>
											<option class="10" value="10">October</option>
											<option class="11" value="11">November</option>
											<option class="12" value="12">December</option>
										</select>
									</div>
									<!--<label class="col-md-3 control-label" for="cmb_month"><em>[current]</em></label>-->
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label input-sm" for="cmb_month">Week:</label>
									<div class="col-md-5">
										<select onchange="" id="cmb_month_week" class="cmb_month_week form-control input-sm">
											<option class="0" value="0">---</option>
										</select>
									</div>
									<!--<label class="col-md-3 control-label" for="cmb_month"><em>[current]</em></label>-->
								</div>
								<div class="form-group">
									<div class="col-md-5">
										<button onclick="viewSales()" type="button" class="btn btn-primary btn-sm">
											<i class="fa fa-search"></i>
											<strong>View</strong>
										</button>
									</div>
								</div>
							</form>		
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-arrow-right fa-fw"></i>
							Computed Sales for the selected Month
						</div>
						<div class="panel-body">
							
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-arrow-right fa-fw"></i>
							Per Week Sales (Week X of the selected Month)
						</div>
						<div class="panel-body">
							<div id="sales-body"></div>
						</div>
					</div>
				</div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(2)) { ?>
			<div class="row">
				<div class="col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-check"></i>
							asdas
						</div>
						<div class="panel-body">
								
						</div>
					</div>
				</div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(3)) { ?>
			<div class="row">
				<div class="col-lg-12" >
                    <div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-check"></i>
							asdas
						</div>
						<div class="panel-body">
							
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(4)) { ?>
			<div class="row">
				<div class="col-lg-12" >
                    <div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-check fa-fw"></i>
							asdas
						</div>
						<div class="panel-body">
								
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(5)) { ?>
			<div class="row">
				<div class="col-lg-12" >
                    <div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-info-circle fa-fw"></i>
							Employee Information
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="control/register_ee.php" method="post">
								<p class="text-muted">Kindly fill-up all required fields (<font style="color:red;">*</font>) and click "Submit" to process registration.</p>
								<hr />
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="ee_no">Employee No.:<font style="color:red;"> *</font></label>
									<div class="col-md-4 input-sm">
										<input id="ee_no" type="number" class="form-control input-sm" min="0" step="1" data-bind="value:replyNumber" placeholder="" name="ee_no" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="ee_name">Employee Name:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="ee_name" type="text" class="form-control input-sm" placeholder="" name="ee_name" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="ee_dept">Department:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="ee_dept" type="text" class="form-control input-sm" placeholder="" name="ee_dept" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="ee_email_address">Email:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="ee_email_address" type="text" class="form-control input-sm" placeholder="" name="ee_email_address" value="" autofocus required>
									</div>
								</div>
								<!--<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="vname">Expiration Date:<font style="color:red;"> *</font></label>
									<div class="col-md-4 input-sm">
										<input id="vname" type="text" class="form-control input-sm" placeholder="" name="eeexp" value="" autofocus required>
									</div>
								</div>-->
								<hr />
								<div class="text-right">
									<button name="" type="submit" class="btn btn-primary btn-default">
										<i class="fa fa-check fa-fw"></i>
										<strong>Submit</strong>
									</button>
								</div>
							</form>
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(6)) { ?>
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2" >
                    <div class="panel panel-default">
						<!--<div class="panel-heading">
							<i class="fa fa-check-circle fa-fw"></i>
							Activate Employee Account
						</div>-->
						<div class="panel-body">
							<form class="form-horizontal" action="control/activate_ee.php?id=<?PHP if(isset($_GET["id"])) { echo $_GET["id"]; } else { echo 0; }?>" method="post">
								<p class="text-muted">Kindly fill-up all required fields (<font style="color:red;">*</font>) or click lookup button to choose from the list and click "Activate" to generate username and password.</p>
								<hr />
								<div class="form-group">
									<label class="control-label col-md-3 input-sm" for="uid">User ID:<font style="color:red;"> *</font></label>
									<div class="col-md-7 input-sm">
										<div class="col-xs-10">
											<input id="uid" type="text" class="form-control input-sm" placeholder="" name="uid" value="<?PHP if(isset($_GET["id"])) echo $_GET["id"]; ?>" disabled="" required>
										</div>
										<button name="" type="button" class="btn btn-default btn-sm col-xs-1" title="Lookup" data-toggle="modal" data-target=".dialog-employee-lookup">
											<i class="fa fa-search fa-fw"></i>
										</button>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 input-sm" for="email">User Email:<font style="color:red;"> *</font></label>
									<div class="col-md-7 input-sm">
										<div class="col-xs-10">
											<input id="email" type="text" class="form-control input-sm" placeholder="" name="email" value="" onchange="" autofocus required>
										</div>
									</div>
								</div>
								<!--<div class="form-group">
									<div class="col-md-10 col-md-offset-2 input-sm">
										<p id="user-dtl" class="text-muted">Name will appear here...</p>
									</div>
								</div>-->
								<hr />
								<div class="text-right">
									<button name="" type="submit" class="btn btn-primary btn-default">
										<i class="fa fa-check fa-fw"></i>
										<strong>Activate</strong>
									</button>
								</div>
							</form>
							<div id="auto"></div>
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(7)) { ?>
			<div class="row">
				<div class="col-lg-12" >
                    <div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-info-circle fa-fw"></i>
							Vendor Information
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="control/register_vendor.php" method="post">
								<p class="text-muted">Kindly fill-up all required fields (<font style="color:red;">*</font>) and click "Submit" to process registration.</p>
								<hr />
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="vname">Vendor Name:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_name" type="text" class="form-control input-sm" placeholder="" name="v_name" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Principal Name:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_principal_name" type="text" class="form-control input-sm" placeholder="" name="v_principal_name" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Street:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_street" type="text" class="form-control input-sm" placeholder="" name="v_street" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">City:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_city" type="text" class="form-control input-sm" placeholder="" name="v_city" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Country:<font style="color:red;"> *</font></label>
									<div class="col-md-4 input-sm">
										<select class="form-control" id="v_country" name="v_country">
											<?PHP
												foreach($country as $key => $value) {
													echo '<option value="'.$key.'" '.(($key == "PH") ? 'selected="selected"' : "").' >'.$value.'</option>';
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Other Name:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_other_name" type="text" class="form-control input-sm" placeholder="" name="v_other_name" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Contact Name:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_contact_name" type="text" class="form-control input-sm" placeholder="" name="v_contact_name" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Landline No.:<font style="color:red;"> *</font></label>
									<div class="col-md-4  input-sm">
										<input id="v_land_line" type="text" class="form-control input-sm" placeholder="" name="v_land_line" value="" autofocus required>
									</div>
									<label class="control-label col-md-2 input-sm" for="pname">Local No.:<font style="color:red;"> *</font></label>
									<div class="col-md-4 input-sm">
										<input id="v_local_no" type="text" class="form-control input-sm" placeholder="" name="v_local_no" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Mobile No.:<font style="color:red;"> *</font></label>
									<div class="col-md-4 input-sm">
										<input id="v_mobile_no" type="text" class="form-control input-sm" placeholder="" name="v_mobile_no" value="" autofocus required>
									</div>
									<label class="control-label col-md-2 input-sm" for="pname">Fax No.:<font style="color:red;"> *</font></label>
									<div class="col-md-4 input-sm">
										<input id="v_fax_no" type="text" class="form-control input-sm" placeholder="" name="v_fax_no" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">FTP Site:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_ftp_site" type="text" class="form-control input-sm" placeholder="" name="v_ftp_site" value="" autofocus required>
									</div>
								</div>
								<hr />
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Vendor Email:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_email" type="text" class="form-control input-sm" placeholder="" name="v_email" value="" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 input-sm" for="pname">Principal Email:<font style="color:red;"> *</font></label>
									<div class="col-md-10 input-sm">
										<input id="v_principal_email" type="text" class="form-control input-sm" placeholder="" name="v_principal_email" value="" autofocus required>
									</div>
								</div>
								<hr />
								<div class="text-right">
									<button name="" type="submit" class="btn btn-primary btn-default">
										<i class="fa fa-check fa-fw"></i>
										<strong>Submit</strong>
									</button>
								</div>
							</form>
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(8)) { ?>
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2" >
                    <div class="panel panel-default">
						<!--<div class="panel-heading">
							<i class="fa fa-check-circle fa-fw"></i>
							Activate Vendor Account
						</div>-->
						<div class="panel-body">
							<form class="form-horizontal" action="control/activate_vendor.php?id=<?PHP if(isset($_GET["id"])) { echo $_GET["id"]; } else { echo 0; }?>" method="post">
								<p class="text-muted">Kindly fill-up all required fields (<font style="color:red;">*</font>) or click lookup button to choose from the list and click "Activate" to generate username and password.</p>
								<hr />
								<div class="form-group">
									<label class="control-label col-md-3 input-sm" for="vcode">Vendor Code:<font style="color:red;"> *</font></label>
									<div class="col-md-7 input-sm">
										<div class="col-xs-10">
											<input id="vcode" type="text" class="form-control input-sm" placeholder="" name="vcode" value="<?PHP if(isset($_GET["id"])) echo $_GET["id"]; ?>" disabled="" onchange="alert('asd')" required>
										</div>
										<button name="" type="button" class="btn btn-default btn-sm col-xs-1" title="Lookup" data-toggle="modal" data-target=".dialog-vendor-lookup">
											<i class="fa fa-search fa-fw"></i>
										</button>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 input-sm" for="email">Vendor Email:<font style="color:red;"> *</font></label>
									<div class="col-md-7 input-sm">
										<div class="col-xs-10">
											<input id="email" type="text" class="form-control input-sm" placeholder="" name="email" value="" onchange="" autofocus required>
										</div>
									</div>
								</div>
								<!--<div class="form-group">
									<div class="col-md-10 col-md-offset-2 input-sm">
										<p id="vendor-dtl" class="text-muted">Vendor name will appear here...</p>
									</div>
								</div>-->
								<hr />
								<div class="text-right">
									<button name="" type="submit" class="btn btn-primary btn-default">
										<i class="fa fa-check fa-fw"></i>
										<strong>Activate</strong>
									</button>
								</div>
							</form>
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(9)) { ?>
			<div class="row">
				<div class="col-lg-12" >
                    <div class="panel panel-default">
						<div class="panel-body">
							<div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-employee" style="font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>EE #</th>
                                            <th>Reg. Code</th>
                                            <th>Name</th>
											<th>Dept.</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Reg. Date</th>
                                            <th>Exp. Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
											<td>asd</td>
											<td>asd</td>
											<td>asd</td>
											<td>as</td>
											<td>da</td>
											<td>df</td>
											<td>asd</td>
											<td>aad</td>
											<td>asd</td>
										</tr>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(10)) { ?>
			<div class="row">
				<div class="col-lg-12" >
                    <div class="panel panel-default">
						<div class="panel-body">
							<div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-vendor" style="font-size:12px;">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Vendor #</th>
                                            <th>Reg. Code</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>FTP Site</th>
                                            <th>Status</th>
                                            <th>Reg. Date</th>
                                            <th>Exp. Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<tr>
											<td>asd</td>
											<td>asd</td>
											<td>asd</td>
											<td>as</td>
											<td>da</td>
											<td>df</td>
											<td>asd</td>
											<td>aad</td>
											<td>asd</td>
										</tr>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(99)) { ?>
			<hr />
			<div class="row">
				<div class="col-lg-12" >
                    <div class="panel panel-primary">
						<div class="panel-body">
							<i class="fa fa-thumbs-up fa-fw"></i>
							Account activated.
						</div>
					</div>
                </div>
			</div>
			<?PHP }
			else if(isset($_GET["active"]) && $_GET["active"] ==  md5(100)) { ?>
			<hr />
			<div class="row">
				<div class="col-lg-12" >
                    <div class="panel panel-red">
						<div class="panel-body">
							<i class="fa fa-warning fa-fw"></i>
							Failed activate account.
						</div>
					</div>
                </div>
			</div>
			<?PHP } ?>
        </div>
    </div>

    <!-- jQuery -->
    <script src="http://192.168.10.53/lcclib/script/jquery/jquery-2.1.1.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
	<script src="../../js-main/bootstrap.min.js"></script>
    <!--<script src="<?PHP //echo $lib->linkCompo(); ?>/script/jquery/_jquery.bootstrap/bootstrap.min.js"></script>-->

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../js-main/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <!-- <script src="../js/plugins/morris/raphael.min.js"></script> -->
    <!-- <script src="../js/plugins/morris/morris.min.js"></script> -->
    <!-- <script src="../js/plugins/morris/morris-data.js"></script> -->

    <!-- Custom Theme JavaScript -->
    <script src="../js/sb-admin-2.js"></script>
	
	<!-- function JavaScript -->
    <script src="../js/sales.js"></script>
	
	<!-- loadmask JavaScript -->
    <script src="../../js-main/jquery.loadmask.min.js"></script>
	
	<!-- DataTables JavaScript -->
    <script src="../../js-main/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="../../js-main/plugins/dataTables/dataTables.bootstrap.js"></script>

	<?PHP include "dialogs.html"; ?>
	
	<script>
		$(function() {
			onPageLoad();
		});
	</script>

	<!--<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
		  ...
		</div>
	  </div>
	</div>-->
</body>

</html>