<?PHP
include_once '../global-class/MMSConnect.class.php';
include_once 'ds-class/ds-CCN_Sales.class.php';

$mms = new MMS();
$mms_db = $mms->getConnection();

$loc = true;
$year = true;
$year = 2010;

$ccn = new CCNSales($mms_db);
$cslcss = $ccn->getCslcss(680047);
$rowLoc = $ccn->getLocSales($cslcss[0], false, true, $year);

for($i = 0; $i < count($rowLoc); $i++) {
	echo $rowLoc[$i][0];
	echo "<br />";
}

echo "Total: ".count($rowLoc);
//echo $row;
?>