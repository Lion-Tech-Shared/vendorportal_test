<?PHP 
	require_once "../class-main/connection.class.php";
	require_once "class/panel-designer.class.php"; 
	$designer = new designPage();

	if(isset($_SESSION["uid"]) && $_SESSION['gid']!=1) {
		header("Location: ../home.php");
	}
    if(!isset($_SESSION["uid"]) ) {
        header("Location: ../home.php");
    }
    		
	$page = "group";
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
			<div class="row-cont">
				<div class="row">
					<div class="col-lg-12">
						<h4 style="padding-left:20px;">User Group Setup</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12" >
						<div class="panel panel-default">
							<div class="panel-body">
								<form class="form-horizontal" action="" method="post">
                                	<div class="form-group">
                                        <div class="col-xs-8 input-sm">
                                            <div class="col-md-2">
                                                <label class="control-label input-sm" for="mmenu">Group:</label>
                                            </div>
                                            <div class="col-md-4">
                                                <select id="group" class="form-control input-sm">
                                                    <?PHP
                                                        $conn = new getConnection();
                                                        $db = $conn->PDO();
                                                        
                                                        //$qry = "SELECT * FROM tbl_user_group WHERE group_id not in (1) ORDER BY group_id";
														$qry = "SELECT * FROM tbl_user_group a left join tbl_privileges b on a.group_id=b.gid ";
                                                        $qry.=" WHERE b.isLocked=? ORDER BY group_id";
                                                        
                                                        $res = $db->prepare($qry);
                                                        $res->execute(array(0));
                                                        $ugroup = $res->fetchAll();
                                                        
                                                        if(count($ugroup) == 0) {
                                                            echo '<option selected="selected" value="0">--No Menu--</option>';
                                                        }
                                                        echo '<option selected="selected value=""></option>';
                                                        for($i = 0; $i < count($ugroup); $i ++) {
                                                            echo '<option  value="'.$ugroup[$i]["group_id"].'">'.$ugroup[$i]["group_code"].'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button id="btnAdd" type="button" class="btn btn-primary btn-default btn-sm">
                                                    <i class="fa fa-plus fa-fw"></i>
                                                </button>
                                            
                                                <button id="btnEdit" type="button" class="btn btn-primary btn-default btn-sm">
                                                    <i class="fa fa-pencil fa-fw"></i>
                                                </button>
                                            
                                                <button id="btnDelete" type="button" class="btn btn-primary btn-default btn-sm disabled">
                                                    <i class="fa fa-trash fa-fw"></i>
                                                </button>
                                            </div>
                                       	</div>
									</div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <i class="fa fa-check fa-fw"></i>
                                            Accessible Features
                                        </div>
                                        <div class="panel-body cont-access">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button id="btnUpdate" name="btnUpdate" type="button" class="btn btn-primary btn-default">
                                            <i class="fa fa-arrow-up fa-fw"></i>
                                            <strong>Update</strong>
                                        </button>
                                    </div>
								</form>
							</div>
						</div>
					</div>
				</div>
                <?PHP echo $designer->pageDisclaimer(); ?>
			</div>
		</div>
	</div>
	
	<?PHP echo $designer->pageFoot($page); ?>
</body>

</html>