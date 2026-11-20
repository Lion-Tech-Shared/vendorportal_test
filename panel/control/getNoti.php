<?php       
require "../../class-main/connection.class.php";
session_start();

require "getNotiClass.php";

$conn = new getConnection();
$db = $conn->PDO();
$db->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

$privDtl = $_GET['privDtl'];

//$split=explode("-",$privDtl);
//echo $split[1]; 
//exit;
$myClass = new myClass($privDtl,$db);
$isVndr = 0;
$col = '';

$rows = array();
$rows2 = array(); 
 
if( $myClass->isVendor() ){
	//echo 'vendor';
	$col['flag'] = 'vUpd';
	$col['val'] = 0;

	$col['xFlag'] = 'mUpd';
	$col['val2'] = 1;

	$col['xFlag2'] = 'pUpd';
	$col['val3'] = 0;

	$col['xFlag3'] = 'ctorVwd';
	$col['val4'] = 0;

	

	$col['xFlag5'] = 'mVwd';
	$col['val6'] = 0;

	$col['xFlag6'] = 'pVwd';
	$col['val7'] = 0;

	$col['or'] = 'vUpd';
	$col['orVal'] = 1;

	$col['or2'] = 'pUpd';
	$col['orVal2'] = 1;

	$col['xFlag4'] = 'vVwd';
	$col['val5'] = 0;

	$col['or3'] = 'pUpd';
	$col['orVal3'] = 1;

	$col['or4'] = 'vUpd';
	$col['orVal4'] = 0;

	/*transfer*/
	$col2['tcol'] = 'txn';
	$col2['tVal'] = 5; 

	$col2['tcol2'] = 'pUpd';
	$col2['tVal2'] = 1; 

	$col2['tcol3'] = 'vVwd';
	$col2['tVal3'] = 1; 

	/*pullout*/
	$col2['pcol'] = 'txn';
	//$col2['tVal'] = 3;  3 or 6

	$col2['pcol2'] = 'pUpd';
	$col2['pVal2'] = 0; 

	$col2['pcol3'] = 'vVwd';
	$col2['pVal3'] = 1; 
		
	$result = $myClass->getNoti($db,$col);
	//array_push($rows, $myClass->orgArrays($result,$col) ); 
	$rows = $myClass->orgArrays($result,$col);

	/*Transfer*/
	$result2 = $myClass->getNoti5($db,$col2);
	$rows2 = $myClass->orgArrays($result2,$col2);

	/*Pullout*/
	$result3 = $myClass->getNoti6($db,$col2);
	$rows3 = $myClass->orgArrays($result3,$col2);
	
	if(count($rows2) > 0){
		for($i = 0; $i < count($rows2); $i++){
			array_push($rows,$rows2[$i]);			 
		}		
	}
	if(count($rows3) > 0){
		for($i = 0; $i < count($rows3); $i++){
			array_push($rows,$rows3[$i]);			 
		}		
	}
}
else if( $myClass->canApprove() ){
	$col['flag'] = 'mUpd';
	$col['val'] = 0;

	$col['xFlag'] = 'vUpd';
	$col['val2'] = 0;

	$col['xFlag2'] = 'pUpd';
	$col['val3'] = 0;

	$col['xFlag3'] = 'ctorVwd';
	$col['val4'] = 0;

	$col['xFlag4'] = 'vVwd';
	$col['val5'] = 0;

	$col['xFlag5'] = 'mVwd';
	$col['val6'] = 0;

	$col['xFlag6'] = 'pVwd';
	$col['val7'] = 0;

	$col['or'] = 'mUpd';
	$col['orVal'] = 1;

	$col['or2'] = 'pUpd';
	$col['orVal2'] = 1;

	/*transfer*/
	$col2['tcol'] = 'txn';
	$col2['tVal'] = 5; 

	$col2['tcol2'] = 'pUpd';
	$col2['tVal2'] = 1; 

	$col2['tcol3'] = 'mVwd';
	$col2['tVal3'] = 0; 


	/*pullout*/
	$col2['pcol'] = 'txn';
	//$col2['tVal'] = 3;  3 or 6

	$col2['pcol2'] = 'pUpd';
	$col2['pVal2'] = 1; 

	$col2['pcol3'] = 'mVwd';
	$col2['pVal3'] = 0;


	$result = $myClass->getNoti2($db,$col);	 
	//array_push($rows, $myClass->orgArrays($result,$col) ); 
	$rows = $myClass->orgArrays($result,$col);

	$result2 = $myClass->getNoti5($db,$col2);
	$rows2 = $myClass->orgArrays($result2,$col2);
	
	/*Pullout*/
	$result3 = $myClass->getNoti7($db,$col2);
	$rows3 = $myClass->orgArrays($result3,$col2);

	//if(count($rows2) > 0)
	//	array_merge($rows,$rows2);
	if(count($rows2) > 0){
		for($i = 0; $i < count($rows2); $i++){
			array_push($rows,$rows2[$i]);			 
		}
		
	}
		 
	if(count($rows3) > 0){
		for($i = 0; $i < count($rows3); $i++){
			array_push($rows,$rows3[$i]);			 
		}		
	}
}
else if( $myClass->canReceive() ){ /* also PA*/
	$col['flag'] = 'pUpd';
	$col['val'] = 0;

	$col['xFlag'] = 'vUpd';
	$col['val2'] = 1;

	$col['xFlag2'] = 'mUpd';
	$col['val3'] = 0;

	$col['xFlag3'] = 'ctorVwd';
	$col['val4'] = 0;

	$col['xFlag4'] = 'vVwd';
	$col['val5'] = 0;

	$col['xFlag5'] = 'mVwd';
	$col['val6'] = 0;

	$col['xFlag6'] = 'pVwd';
	$col['val7'] = 0;

	/*set to 2 , because 2 is not valid value . to exclude this or clause because PA cannot receive noti received.*/
	$col['or'] = 'pUpd';
	$col['orVal'] = 2;

	$col['or2'] = 'pUpd';
	$col['orVal2'] = 2;

	$col['or3'] = 'txn';
	$col['orVal3'] = 5;

	$col['or4'] = 'pUpd';
	$col['orVal4'] = 0;

	/*transfer*/
	$col2['tcol'] = 'txn';
	$col2['tVal'] = 5; 

	$col2['tcol2'] = 'pUpd';
	$col2['tVal2'] = 0; 

	$col2['tcol3'] = 'ctorVwd';
	$col2['tVal3'] = 0; 
	
	/*pullout*/
	$col2['pcol'] = 'txn';
	//$col2['tVal'] = 3;  3 or 6

	$col2['pcol2'] = 'pUpd';
	$col2['pVal2'] = 0; 

	$col2['pcol3'] = 'vUpd';
	$col2['pVal3'] = 1; 


	$result = $myClass->getNoti3($db,$col);
	$rows = $myClass->orgArrays($result,$col); /*Store del*/
	//if(count($rows) > 0)
		//array_push($rows, var)	  
	$result3 = $myClass->getNoti6($db,$col2);
	$rows3 = $myClass->orgArrays($result3,$col2);
	
	if(count($rows3) > 0){
		for($i = 0; $i < count($rows3); $i++){
			array_push($rows,$rows3[$i]);			 
		}		
	}

	$result2 = $myClass->getNoti5($db,$col2);
	$rows2 = $myClass->orgArrays($result2,$col2);
	
	if(count($rows2) > 0){
		for($i = 0; $i < count($rows2); $i++){
			array_push($rows,$rows2[$i]);
			// array_merge($rows,$rows2);
		}
		
	}
} 
else if( $myClass->isCoordinator() ){
	$col['flag'] = 'pUpd';
	$col['val'] = 1;	

	$col['xFlag3'] = 'ctorVwd';
	$col['val4'] = 0;
	 
	/*set to 2 , because 2 is not valid value . to exclude this or clause because PA cannot receive noti received.*/
	$col['or'] = 'pUpd';
	$col['orVal'] = 2;

	$col['or2'] = 'pUpd';
	$col['orVal2'] = 2;

	/*transfer*/
	$col2['tcol'] = 'txn';
	$col2['tVal'] = 5; 

	$col2['tcol2'] = 'pUpd';
	$col2['tVal2'] = 1; 

	$col2['tcol3'] = 'ctorVwd';
	$col2['tVal3'] = 0; 

	/*pullout*/
	$col2['pcol'] = 'txn';
	//$col2['tVal'] = 3;  3 or 6

	$col2['pcol2'] = 'pUpd';
	$col2['pVal2'] = 1; 

	$col2['pcol3'] = 'ctorVwd';
	$col2['pVal3'] = 0;

	$result = $myClass->getNoti4($db,$col,4);
	$rows = $myClass->orgArrays($result,$col);


	$result2 = $myClass->getNoti5($db,$col2);
	$rows2 = $myClass->orgArrays($result2,$col2);
	
	if(count($rows2) > 0){
		for($i = 0; $i < count($rows2); $i++){
			array_push($rows,$rows2[$i]);
			// array_merge($rows,$rows2);
		}
		
	}
	/*pullout*/
	$result3 = $myClass->getNoti7($db,$col2);
	$rows3 = $myClass->orgArrays($result3,$col2);
	
	if(count($rows3) > 0){
		for($i = 0; $i < count($rows3); $i++){
			array_push($rows,$rows3[$i]);			 
		}		
	}
	
}
 

if( $myClass->canViewOrderNoti() ){
	//echo 'can view';
}
if( $myClass->canViewReceiveNoti() ){
	//echo 'can view receive';
}
//echo $result[0][0];
echo json_encode($rows);

?>