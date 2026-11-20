<?php
$data=$_POST['data'];

foreach($data as $key=> $value){
	$val=explode('-', $value[0]);
	echo $val[1].'-'.$value[1];
}
 

?>