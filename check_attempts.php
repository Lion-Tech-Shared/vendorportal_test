<?php
 
class checkAttempts{
	
	private $uid;
	private $pwd;
	private $db;
 	
 	public function __construct(){
 				$_SESSION['login_attempts'] = 0;
 	}
	public function check_attempts($uid,$ip,$db){
		
		if(!isset($_SESSION['myuserid'])){													
				$_SESSION['myuserid']=$uid;
				$_SESSION['my_ip']=$ip;
				$_SESSION['login_attempts']++;								 			
			}else{
					if($_SESSION['myuserid']==$uid){						
						$_SESSION['login_attempts']++;						
						if( $_SESSION['login_attempts'] > 2){								
								$conn = new getConnection();								 
								$db=$conn->PDO();
								$select_first="SELECT *  FROM tbl_user_account WHERE user_id=?";
								$prepare_qry=$db->prepare($select_first);
								$prepare_qry->execute(array($uid));
								$fetch_qry=$prepare_qry->fetchAll();								 
								if(count($fetch_qry)>0){									 
									$prepares = $db->prepare("INSERT INTO login_attempts(userid,attempts,ip,datetime) VALUES(?,?,?,NOW())"); //userid,attempts,datetime
									$prepares->execute(array(strtolower($uid),3,$ip));	
								}
								unset($_SESSION['myuserid']);
								unset($_SESSION['login_attempts']);
								unset($_SESSION['my_ip']);										 
						}else{
							
						}
					}else{
												 
						$_SESSION['login_attempts']=1;
						$_SESSION['myuserid']=$uid;
						$_SESSION['my_ip']=$ip;				 						
					}
			}
	}
	 
}
?>