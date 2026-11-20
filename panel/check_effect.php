<?php

 
date_default_timezone_set('Asia/Taipei');  
//require_once("../class-main/connection.class.php");
//require_once '../class-main/connection.class.php';
class check_if_expire{
 	
	/*private $host = "localhost";
	private $db_name = "ccndb";
	private $username = "root";
	private $password = "akobicol";*/
	
	
	/*private $host = "192.168.10.40";
	private $db_name = "ccndb";
	private $username = "";
	private $password = '';*/
	private $conn;
		
	public function __construct(){
		//$this->conn = null;        
        //$this->getCon();           	
	}
	public function getCon(){	
        $db=new getConnection();
        $this->conn=$db->PDO();
        return $this->conn; 
	}
	public function isExpire(){		 
	
		$db=$this->getCon();	
		
		$query="SELECT * FROM  tbl_user_account WHERE user_id=?";
		$prepare=$db->prepare($query);
		$prepare->execute(array($_SESSION['uid']));
		$fetch=$prepare->fetchAll();
		$expiry_date=$fetch[0]["last_update"];
		$reg_date=$fetch[0]["date_created"];
		
		$months=$this->getMonths();
		
		$newdate=date("Y-m-d",strtotime("+ ".$months." month",strtotime($reg_date)));
		$dt=new DateTime($newdate);
		$dt2=new DateTime(date("Y-m-d"));
		 
		//echo "current:".date("Y-m-d")." <br>";
		//echo "old:".$newdate."<br>";
		$mon_diff=$dt2->diff($dt)->m;
		$day_diff=$dt2->diff($dt)->d;
	 	
	 	if( $this->currentDate() >= $expiry_date && $expiry_date){
			//echo "Your Userid expires";	
			$_SESSION['change_pwd']=true;
			$_SESSION['id_expired']="Your UserId expires:".$_SESSION['uid'];			 
			header("Location:change_password.php");
		}		 
		//header("Location:sales_report.php");
	}
	
    private function currentDate(){
		$db=$this->getCon();
		$query="SELECT curdate()";
		$prepare=$db->prepare($query);
		$prepare->execute();
		$fetch=$prepare->fetchAll();

		return $fetch[0][0];
	}	
	public function display_expiration(){
		$db=$this->getCon();
		$query="SELECT * FROM  tbl_user_account WHERE user_id=?";
		$prepare=$db->prepare($query);
		$prepare->execute(array($_SESSION['uid']));
		$fetch=$prepare->fetchAll();
		
		$last_update=$fetch[0]["last_update"];
		$reg_date=$fetch[0]["date_created"];		
		$months=$this->getMonths();
		$expiry_date=date("Y-m-d",strtotime("+ ".$months." month",strtotime($reg_date)));
		
		return date("F d, Y",strtotime($last_update));
		
	}	
	/*private function getConnection(){
		return $this->conn;
	}*/
 	public function update_expiry(){
		
		$db=$this->getCon();
		
		$query="SELECT * FROM  tbl_user_account WHERE user_id=?";
		$prepare=$db->prepare($query);
		$prepare->execute(array($_SESSION['uid']));
		$fetch=$prepare->fetchAll();
		$reg_date=$fetch[0]["date_created"];
		
		if(!$fetch[0]["last_update"] || $fetch[0]["last_update"]){
			$months=$this->getMonths();
			$new_expiry_date=strtotime("+ ".$months." months",strtotime($reg_date));
			$new_expiry_date=date("Y-m-d",$new_expiry_date);
			if( date("Y-m-d") >= $new_expiry_date ){
				$new_expiry_date=date("Y-m-d",strtotime("+ ".$months." month",strtotime(date("Y-m-d"))));
				$query="UPDATE tbl_user_account SET last_update=? WHERE user_id=?";
				$prepare=$db->prepare($query);
				$prepare->execute(array($new_expiry_date,$_SESSION['uid']));
				//unset($_SESSION['change_pwd']);
				//unset($_SESSION['id_expired']);				
			}else{
				$query="UPDATE tbl_user_account SET last_update=? WHERE user_id=?";
				$prepare=$db->prepare($query);
				$prepare->execute(array($new_expiry_date,$_SESSION['uid']));
			}
			//echo $new_expiry_date."-".date("Y-m-d",strtotime('NOW'));
			
		}
		else{
			 /*if(date("Y-m-d",strtotime("+0 month",strtotime(date("Y-m-d")))) >= date("Y-m-d",strtotime($fetch[0]["last_update"]))){
				
				 //$_SESSION['change_pwd']=true;
				 //$_SESSION['id_expired']="Your UserId Expires";		
				  return date("Y-m-d",strtotime("+1 month",strtotime(date("Y-m-d"))));
			 }*/
		}
	}
	private function setConnection(){
		
		//$con=new getConnection();
		//$db=$con->PDO();
				
		//return $db;
		
	}
	
	private function getMonths(){
		
		$db=$this->getCon();
				
		//select number of months
		$months_qry="SELECT * FROM user_effectivity";
		$prepare=$db->prepare($months_qry);
		$prepare->execute();
		$fetch2=$prepare->fetchall();
		$months=$fetch2[0]["months"];		
		return $months;	
		
	}
	
	
}

?>