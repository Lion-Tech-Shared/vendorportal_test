<?php
	require "../../class-main/connection.class.php";
	require_once "redirect.php";
	session_start();

	$newFunc=new link_2_change_pwd();
	$newFunc->checkSession();	 /* redirect if session not set */

	$conn = new getConnection();
	$db = $conn->PDO();
	 
	if(!isset($_GET['flag'])){
		header("Location:../home.php");
	}
	$pullout=$_GET['pullout'];	
	$qry="SELECT * FROM tbl_pullout_hdr WHERE pullout_num=?";
	$prepare=$db->prepare($qry);
	$prepare->execute(array($pullout));
	$fetch=$prepare->fetchAll();
	$pick_up_date=$fetch[0]['pick_up_date'];
	$date_created=$fetch[0]['date_created'];


	$qry="SELECT curdate()";
	$prepare=$db->prepare($qry);
	$prepare->execute();
	$fetch=$prepare->fetchAll();
	$current_date=date("Y-m-d",strtotime($fetch[0][0]));

	$deadline_date=date("Y-m-d",strtotime("+ 44 days",strtotime($date_created)));
	//$current_date=date('Y-m-d H:i:s');
	/*$lock_date=$fetch[0]["datetime"];
	 		 
	$dt=new DateTime($current_date);
	$dt2=new DateTime($lock_date);	 
	$day_diff=$dt->diff($dt2)->d;
	$minutes_diff=$dt->diff($dt2)->i;
	$hours_diff=$dt->diff($dt2)->h;
	if( $day_diff > 0 || $hours_diff >= 1  || $minutes_diff > 60 ){ 
		$clear_attempts="DELETE FROM login_attempts WHERE userid=?";
		$prepare_clear=$db->prepare($clear_attempts);
		$prepare_clear->execute(array($uid));
		unset($_SESSION['msg']);*/
	if($deadline_date < $current_date){
		//echo "Deadline Date : ".$deadline_date." <> Current Date : ".$current_date;
		echo -1; /**Cannot Pick because already over the terms**/
	}else{
		echo 1;  /**can pick up**/
	}	
	
?>