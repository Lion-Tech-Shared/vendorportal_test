<?php

session_start();

$funct=new myClass(); 
$funct->_require();

$conn = new getConnection();
$db = $conn->PDO();
 
$vendor_num=$_SESSION['uid'];
$id=$_GET["id"];
if($_SESSION['gid']!=2){
	header("Location:../home.php");
}
if($_SESSION['gid']==2){
	if($id=='read-all'){
		
			/*$qry="SELECT A.*,B.ee_name,B.store,C.is_approved";
			$qry.=" FROM  tbl_requestadj A ";
			$qry.=" LEFT JOIN tbl_user_info B ON A.userid=B.user_id ";
			$qry.=" LEFT JOIN tbl_adjustment C ON A.id=C.id "; 
			$qry.=" WHERE B.store IN(";
			$qry.='SELECT DISTINCT C.cctloc AS STORE FROM TBL_LCSUCN A LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS LEFT JOIN TBL_STORE_MASTER D ON c.cctloc=D.STrnum WHERE A.LVENDR='.$vendor_num.' ';
			$qry.=')  ORDER BY A.requestDate ';*/
			$qry=" UPDATE tbl_adjustment SET viewDate=NOW(),is_viewed=1 WHERE userid=? AND is_viewed=? ";
			$prepare=$db->prepare($qry);
			$prepare->execute(array($vendor_num,0));
			//$fetch=$prepare->fetchAll();
			//for($i=0;$i<count($fetch);$i++){
				//$funct->_insert($fetch[$i]["id"],$vendor_num,$db);
			//}
			echo 1;	
		 
	}else{
		/*$qry="SELECT * FROM tbl_adjustment WHERE id='".$id."'";
		$prepare=$db->prepare($qry);
		$prepare->execute();
		$fetch=$prepare->fetchAll();

		if(count($fetch)<1){
			//$date=NOW();;				
			//$qry.='SELECT DISTINCT C.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM TBL_LCSUCN A LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS LEFT JOIN TBL_STORE_MASTER D ON c.cctloc=D.STrnum WHERE A.LVENDR='.$vendor_num.' ';					
			 
			 $funct->_insert($id,$vendor_num,$db);		
			 echo 1; 
		}*/
		 
			$funct->_insert($id,$vendor_num,$db);	
	 	 
	}
}

class myClass{
	 
	private $conn;
    
	public function __construct(){
		$this->conn = null;
       /* try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }*/
        //return $this->conn;	
        //$this->_require();      
	}
	public function _insert($myid,$vendor_num,$db){
		//$db=$this->getConnection();	
		$qry="SELECT * FROM tbl_adjustment WHERE id='".$myid."'";
		$prepare=$db->prepare($qry);
		$prepare->execute();
		$fetch=$prepare->fetchAll();

		if(count($fetch)<1){
		//$date=NOW();;				
		//$qry.='SELECT DISTINCT C.cctloc AS STORE,D.STRNAM AS STORE_NAME FROM TBL_LCSUCN A LEFT JOIN TBL_CSSMST B ON A.LCNVDR=B.CCMNUM LEFT JOIN TBL_CSSMCT C ON B.CCMNUM=C.CCTCSS LEFT JOIN TBL_STORE_MASTER D ON c.cctloc=D.STrnum WHERE A.LVENDR='.$vendor_num.' ';							 
			//$funct->_insert($id,$vendor_num,$db);			
			//$qry=" INSERT INTO tbl_adjustment (id,userid,viewDate,is_approved,remarks) ";
			//$qry.=" VALUES ('".$myid."','".$vendor_num."',NOW(),0,' ') ";
			$qry=" UPDATE tbl_adjustment SET viewDate=NOW(),is_viewed=1 WHERE id=? AND is_viewed=? ";
			$prepare=$db->prepare($qry);
			$prepare->execute(array($myid,0));
			echo 1; 
		} 	
	}
	private function getConnection(){

		return $this->conn;
	}
	private function _return(){

		return $this->$db;
	}

	public function _require(){
		require "../../class-main/connection.class.php";        
	}

}
 
?>