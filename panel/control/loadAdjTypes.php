<?php
	require "../../class-main/connection.class.php";
	session_start();

	$con=new getConnection();
	$db=$con->PDO();	                
	$qry='SELECT * FROM tbl_type_adj ORDER BY type ASC';
	$prepare=$db->prepare($qry);
	$prepare->execute();
	$fetch=$prepare->fetchAll();
	$str="";
	if(isset($_GET['myOption'])){
		$str.='<option></option>';
	}else{
		$str.='<option selected="selected"></option>';
	}
	
	for($i=0;$i<count($fetch);$i++){
		if(isset($_GET['myOption'])){
			if($_GET['myOption']==$fetch[$i][0]){
				$str.='<option value='.$fetch[$i][0].' selected="selected">'.$fetch[$i][1].'</option>';
			}else{
				$str.='<option value='.$fetch[$i][0].'>'.$fetch[$i][1].'</option>';
			}
		}else{
        	$str.='<option value='.$fetch[$i][0].'>'.$fetch[$i][1].'</option>';
        }
    }

    echo $str;
?>