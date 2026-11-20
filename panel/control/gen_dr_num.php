<?php
session_start();
/* gid=2 vendor only*/
if($_SESSION['gid']==2){
	$_SESSION['dr_num']=time().'-'.$_SESSION['uid'];
	echo $_SESSION['dr_num'];
}else{
	$dr=$_GET['dr'];
	if($dr!=''){
		$_SESSION['dr_num']=$dr;
	}
}
?>