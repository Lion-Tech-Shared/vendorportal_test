<?php
 

class link_2_change_pwd{
	
	public function toChangePwd(){		
	
		if(isset($_SESSION['change_pwd'])){
			//$page = "change_password";
			header("Location: change_password.php");		
		}
		
	}
	public function checkSession(){
		if(!isset($_SESSION['uid'])){
			header("Location:../home.php");
		}
	}
}

?>