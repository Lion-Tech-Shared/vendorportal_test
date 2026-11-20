<?php
require "../../class-main/connection.class.php";
require_once "redirect.php";
session_start(); 

$newFunc=new link_2_change_pwd();
$newFunc->checkSession();	 /* redirect if session not set */

/**add class myClass**/
$checkDays=new myClass();

$conn = new getConnection();
$db = $conn->PDO();

$selDate=$_GET['selDate']; 
 
$qry=" SELECT date_format(curdate(),'%m/%d/%Y') as curdate";
 
$prepare=$db->prepare($qry);
$prepare->execute();
$fetch=$prepare->fetchAll();
 
$currentDate=$fetch[0][0]; 

/************Compute difference*********************/
$curr=new DateTime($currentDate);
$selDat=new DateTime($selDate);

if($selDate > $currentDate){
	$days_diff=$selDat->diff($curr)->format('%a');
	$return=$checkDays->isSetDateOver($days_diff);
	/**another way of getting difference of days*/
	/**$diff=date_diff($selDat,$curr);**/
 
	//echo $diff->format('%R%a days');
}
echo $return;



/*----------------My Class--------------------------------------*/
class myClass{
	
	/*maxdays could be dynamic retrieving from db table*/
	private $maxDays=45;

	public function isSetDateOver($setDateDays){
		/**-1 means over 1=not over**/
		if($setDateDays > $this->maxDays){
			return -1;
		}
		return 1;	
	}	

}



?>


