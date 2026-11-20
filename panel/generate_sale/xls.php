<?php
session_start();
/*
* THIS IS SECTION IS FOR CLASS
* BEGIN
*/
class myClass{
    //public $gTotsls; 

    public function __construct(){
                        
        
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
                $qry=" INSERT INTO tbl_adjustment (id,userid,viewDate,is_approved,remarks) ";
                $qry.=" VALUES ('".$myid."','".$vendor_num."',NOW(),0,' ') ";
                $prepare=$db->prepare($qry);
                $prepare->execute();
                echo 1; 
            } 	
    }
    public function _serialing(){
            
    }
    private function getConnection(){

        return $this->conn;
    }
    private function _return(){

        return $this->$db;
    }

    public function _getTotal(){
        //return $this->$gTotsls;
    }

    public function _require(){
        require "../../class-main/connection.class.php";        
    }


    public function _getSerial($tbl,$cnv,$loc,$cnn,$from,$to,$my_total,$db){
        $qry_srl = "SELECT 
                        serial, srlamt
                    FROM
                        tbl_ccn_sales_srl".$tbl."  
                    WHERE
                        srlcnv = ".$cnv."
                    AND
                        srlnum = ".$loc."
                    AND
                        srlcnn = ".$cnn."
                    AND
                        srlfmd = ".$from."
                    AND
                        srltod = ".$to."
                    AND 
                        srlamt = ".$my_total."
                    ORDER BY
                        serial DESC
                    limit 1 ";			
        //$serial = array();					
        $res_srl = $db->prepare($qry_srl);
        $res_srl->execute();
        $serial = $res_srl->fetchAll();		
        return $serial;
    }    

    public function _serialAmt($froml2,$tol2,$dtArr,$cslcss,$strnum,$cslcct,$cslctp,$db){
        //for serial labeling---------------------------------------------------------------{	
            //return $tol2;
            $gqty = 0;
                
            $gCashRsls = 0;
            $gCashMsls = 0;
            $gCash = 0;
            
            $gCredRsls = 0;
            $gCredMsls = 0;
            $gCred = 0;
            $gTotsls = 0;
            $cash_total=0;
            $credit_total=0;
            $x=0;
            while (strtotime($froml2) <= strtotime($tol2)) {
        
                $dtArr = explode("/", $froml2);
                $dt = (substr($dtArr[2], 2, 2).$dtArr[0].$dtArr[1]);
                $totQty = 0;
                $cash_total=0;
                $credit_total=0;
                
                $qry = "SELECT 
                            csldat, sum(cslqty) as qty,	sum(csramt - cscamt) as sls
                        FROM
                            tbl_ccn_sales_dtl
                          WHERE
                            cslcss = ".$cslcss."
                        AND
                            cslloc = ".$strnum."
                        AND
                            cslcct = ".$cslcct."
                        AND
                            csmflg = 'R'
                        AND
                            csldat = ".$dt."
                        AND 
                             cslctp=".$cslctp."	
                            
                        GROUP BY
                            csldat";
                            
                $res = $db->prepare($qry);
                $res->execute();
                $salesCashR = $res->fetchAll();
                
                $totQty += $salesCashR[0]["qty"];
                //$flag += $totQty;
                $cash_total +=$salesCashR[0]["qty"];
                                
                $qry = "SELECT 
                            csldat, sum(cslqty) as qty,	sum(csramt - cscamt) as sls
                        FROM
                            tbl_ccn_sales_dtl
                        WHERE
                            cslcss = ".$cslcss."
                        AND
                            cslloc = ".$strnum."
                        AND
                            cslcct = ".$cslcct."
                        AND
                            csmflg = 'M'
                        AND
                            csldat = ".$dt."
                        AND 
                            cslctp=".$cslctp."								 
                            
                        GROUP BY
                            csldat";
                            
                $res = $db->prepare($qry);
                $res->execute();
                $salesCashM = $res->fetchAll();
                
                $totQty += $salesCashM[0]["qty"];
                $cash_total += $salesCashM[0]["qty"];
                 
                $qry = "SELECT 
                            csldat, sum(cslqty) as qty,	sum(cscamt) as sls
                        FROM
                            tbl_ccn_sales_dtl
                        WHERE
                            cslcss = ".$cslcss."
                        AND
                            cslloc = ".$strnum."
                        AND
                            cslcct = ".$cslcct."
                        AND
                            csmflg = 'R'
                        AND
                            csldat = ".$dt."
                        AND 
                            cslctp=".$cslctp."								 
                            
                        GROUP BY
                            csldat";
                            
                $res = $db->prepare($qry);
                $res->execute();
                $salesCredR = $res->fetchAll();
                                    
                $totQty += $salesCredR[0]["qty"];
                $credit_total +=$salesCredR[0]["qty"];
                $qry = "SELECT 
                            csldat, sum(cslqty) as qty,	sum(cscamt) as sls
                        FROM
                            tbl_ccn_sales_dtl
                        WHERE
                            cslcss = ".$cslcss."
                        AND
                            cslloc = ".$strnum."
                        AND
                            cslcct = ".$cslcct."
                        AND
                            csmflg = 'M'
                        AND
                            csldat = ".$dt."
                        AND 
                            cslctp=".$cslctp."								 											
                        GROUP BY
                            csldat";
                            
                $res = $db->prepare($qry);
                $res->execute();
                $salesCredM = $res->fetchAll();
                
                $totQty += $salesCredM[0]["qty"];
                $credit_total += $salesCredM[0]["qty"];
                if($cash_total>0){
                    $totQty=$cash_total;
                }else{
                    $totQty=$credit_total; 
                }
                 $froml2 = date ("m/d/Y", strtotime("+1 day", strtotime($froml2)));
                $gqty += $totQty;					 
                $gTotsls += ($salesCashR[0]["sls"] + $salesCashM[0]["sls"] + $salesCredR[0]["sls"] + $salesCredM[0]["sls"]);					
                $x++; 
            }
            //$gTotsls+=1;
            return array('total'=>$gTotsls);	
        //for serial labeling --------------------------------------------------------------}								
    }

}
/*
* THIS IS SECTION IS FOR CLASS
* END =====================================================================================================
*/


/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);



include "../../PHPExcel-1.8.1/Classes/PHPExcel.php";
$connection = new myClass();
$connection->_require();
require "../../control/functions.php";
include "../../MPDF57/mpdf.php";
session_start();

ini_set('memory_limit', '1026M');


$vcode      = $_GET['vcode'];
$store      = $_GET['store'];
$from       = $_GET['from'];
$to         = $_GET['to'];
$sbu        = $_GET['sbu'];
$format     = $_GET['format'];
$per        = $_GET['per'];
$isv        = $_GET['isv'];





$conn = new getConnection();
$objPHPExcel = new PHPExcel();
$db = $conn->PDO();

/*check if user gid can access sales report*/
$query=' SELECT * FROM tbl_user_group_menu_sub ';
$query.=" WHERE group_id=? AND menu_id_sub=(SELECT menu_id_sub FROM tbl_app_menu_sub WHERE menu_dsc_sub=?)";	
$prepare=$db->prepare($query);
$prepare->execute(array($_SESSION['gid'],"Sales"));
$count_fetch=$prepare->fetchAll();
if(count($count_fetch)<1){
    echo '<strong>Forbidden to access sales report temporarily.</strong>';
    exit;
}
/*---------------end-----------------------*/

// IF SBU IS EQUAL TO ZERO====================

if($_GET["sbu"] == '0'){
    echo '<strong>Invalid SBU!</strong>';  
    exit;
}

$tbl = ($_GET['sbu'] == 'DS'?"":"Stage");
$lvendr = $_GET["vcode"];
if($lvendr == 6300122)
    $lvendr = 630012;


$storeCode = explode(",", $_GET["store"]);
$store = "";

for($i = 0; $i < count($storeCode); $i ++) {
	$storeCodeCont = explode(" : ", $storeCode[$i]);		
	
	if($i > 0) {
		$store .= ",";
	}
			
	$store .= $storeCodeCont[0];
}    

$fromS = $_GET["from"];
$toS = $_GET["to"];

$fromArr = explode("/", $fromS);
$toArr = explode("/", $toS);

$from = (substr($fromArr[2], 2, 2).$fromArr[0].$fromArr[1]);
$to = (substr($toArr[2], 2, 2).$toArr[0].$toArr[1]);

$qry = "SELECT distinct a.cslloc, a.cslcss, d.v_code, d.v_name, a.cslcct, a.cctdsc FROM tbl_ccn_sales_dtl a ";

//pag vendor dae 
if($_GET["isv"] == "true") {		
    if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) {
    //$check_query="SELECT * FROM tbl_lcsecloc ";
    //if($_SESSION["gid"]) {
    $qry .= "LEFT JOIN
        (select lcstor from tbl_lcsecloc".$tbl." where lcuseid = '".$_SESSION["uid"]."' AND lcstor <> '') b on a.cslloc = b.lcstor ";
    }
}

$qry .= "LEFT JOIN
tbl_lcsucn".$tbl." c on a.cslcss = c.lcnvdr
LEFT JOIN
tbl_vendor_master".$tbl." d on c.lvendr = d.v_code
LEFT JOIN
tbl_store_master".$tbl." e on a.cslloc = e.strnum
WHERE
a.csldat BETWEEN ".$from." AND ".$to." ";

if($_GET["sbu"]) {
$qry .= "AND e.strsbu IN('".$_GET["sbu"]."','HG' ) ";
}

if($_GET["isv"] == "true") {
if($_SESSION["gid"] != 1 && $_SESSION["gid"] != 2) {
//$qry .= "AND b.lcstor <> '' ";			
}

$qry .= "AND d.v_code = ".$lvendr." ";
}
else {
$qry .= "AND a.cslloc = ".$lvendr." AND a.cslcct in (".trim($cslcct, ' ').") ";
}

if($store) {
$qry .= "AND a.cslloc in (".trim($store, " ").") ";
}

$qry .=	"ORDER BY a.cslloc, d.v_code, a.cslcss, a.cslcct";

$res = $db->prepare($qry);
$res->execute();
$cslloc = $res->fetchAll();

//print_r($cslloc);
//exit();

$qry_ctr=$db->prepare('SELECT * FROM tbl_cm');		
$qry_ctr->execute();
$qry_ctr_fetch=$qry_ctr->fetchAll();


if($_GET["format"] == "t"){
    // START IF TOTAL :: 

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Christian Cernechez")
                ->setLastModifiedBy("Christian Cernechez")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

    $objPHPExcel->setActiveSheetIndex(0);

    
     // TOP BY TOTAL
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CONCESSIONAIRE SUMMARY SALES REPORT')
                ->setCellValue('A2','Period : '.date("F", strtotime($fromS)).' '.date("d", strtotime($fromS)).'-'.date("d", strtotime($toS)).', '.date("Y", strtotime($fromS)))
                ->setCellValue('H1', date('m/d/Y H:i:s'));  
                        
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('A1')->getFont()->setBold(true);


    // SET HEADER
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A3','STORE CODE')
                ->setCellValue('B3','STORE NAME')
                ->setCellValue('C3','COMPANY')
                ->setCellValue('D3','CONTRACT#')
                ->setCellValue('E3','CONTRACT DESC')
                ->setCellValue('F3','Comm Rate')
                ->setCellValue('G3','TOTAL QTY')
                ->setCellValue('H3','TOTAL AMOUNT'); 

    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('A3')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('B3')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('C3')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('D3')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('E3')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('F3')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('G3')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('H3')->getFont()->setBold(true);

    $tqty = 0;
    $tsls = 0;
    
    $row = 4;
    for($i = 0; $i < count($cslloc); $i++) {

        
        $qry = "SELECT 
                    strnum, strnam, glcnam
                FROM
                    tbl_store_master".$tbl."
                WHERE
                    strnum = ".$cslloc[$i]["cslloc"]."
                ORDER BY
                    strnum
                ASC";
                    
        $res = $db->prepare($qry);
        $res->execute();
        $str = $res->fetchAll();
        
        $qry = "SELECT 
                    sum(cslqty) as qty,
                    sum(csramt) as net
                FROM
                    tbl_ccn_sales_dtl
                WHERE
                    cslcss = ".$cslloc[$i]["cslcss"]."
                AND
                    cslloc = ".$str[0]["strnum"]."
                AND
                    cslcct = ".$cslloc[$i]["cslcct"]."
                AND
                    csldat BETWEEN ".$from." AND ".$to." ";
                    
        $res = $db->prepare($qry);
        $res->execute();
        $sales = $res->fetchAll();
        
        //my query
        
          $qry ="SELECT distinct a.cslctp,b.ccprte FROM tbl_ccn_sales_dtl a left join tbl_cssctp".$tbl." b on a.cslctp=b.ccpcod ";
          $qry.="WHERE a.cslcss=".$cslloc[$i]["cslcss"]." ";
          $qry.=" AND a.csldat BETWEEN ".$from." AND ".$to." ";
          $qry.=" AND a.cslloc=".$cslloc[$i]["cslloc"]." AND a.cslcct=".$cslloc[$i]["cslcct"]."  ORDER BY a.cslctp ASC";													
          $res_com_rate = $db->prepare($qry);
          $res_com_rate->execute();
          $fetchs = $res_com_rate->fetchAll();


        //end my query
                    
        //get per store
        $resfetch = count($fetchs);
        //print_r($fetchs);
        //exit();
        for($ii = 0; $ii < $resfetch; $ii++) {

            

            //added by chan 10/20/2022
            //$objPHPExcel->createSheet($ii);

              $get_sum='SELECT SUM(csramt) as totals,SUM(cslqty) as total_qty 
                        FROM tbl_ccn_sales_dtl 
                        WHERE cslctp="'.$fetchs[$ii]['cslctp'].'" AND cslcss="'.$cslloc[$i]['cslcss'].'"
                        AND cslloc = '.$str[0]["strnum"].'
                        AND cslcct = '.$cslloc[$i]["cslcct"].'
                        AND	csldat BETWEEN '.$from.' AND '.$to.'';											
              $prepare_qry=$db->prepare($get_sum);
              $prepare_qry->execute();
              $get_val=$prepare_qry->fetchAll();

            //edited by chan 10/20/2022            
            //if($ii<1){    
            //$objPHPExcel->setActiveSheetIndex($ii)
            //if the loop run once
            if($ii<1){
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$row.'',$str[0]["strnum"])
                            ->setCellValue('B'.$row.'',$str[0]["strnam"])
                            ->setCellValue('C'.$row.'',$str[0]["glcnam"])
                            ->setCellValue('D'.$row.'',$cslloc[$i]["cslcct"])
                            ->setCellValue('E'.$row.'',$cslloc[$i]["cctdsc"])
                            ->setCellValue('F'.$row.'',$fetchs[$ii]['ccprte'].'%')
                            ->setCellValue('G'.$row.'',$get_val[0]["total_qty"])
                            ->setCellValue('H'.$row.'',$get_val[0]["totals"]);
            }else{
                //edited by chan 10/20/2022
                //$objPHPExcel->setActiveSheetIndex($ii)
                /*$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F'.$row.'',$fetchs[$ii]['ccprte'].'%')
                            ->setCellValue('G'.$row.'',$get_val[0]["total_qty"])
                            ->setCellValue('H'.$row.'',$get_val[0]["totals"]);*/
                //pag more thaan 1
                $newrow = $row + $ii;
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('F'.$newrow.'',$fetchs[$ii]['ccprte'].'%')
                ->setCellValue('G'.$newrow.'',$get_val[0]["total_qty"])
                ->setCellValue('H'.$newrow.'',$get_val[0]["totals"]);

                
            }
            
            //edited by chan 10/20/2022
            //$objPHPExcel->setActiveSheetIndex($ii)
            $objPHPExcel->setActiveSheetIndex(0)
                        ->getStyle('G'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
            //$objPHPExcel->setActiveSheetIndex($ii)
            $objPHPExcel->setActiveSheetIndex(0)
                        ->getStyle('H'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
        
            
        } 				
        
        
        $tqty += $sales[0]["qty"];
        $tsls += $sales[0]["net"];

        $row += $resfetch;
    


    }    

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('F'.$row.'','TOTAL')
                ->setCellValue('G'.$row.'',$tqty)
                ->setCellValue('H'.$row.'',$tsls);

    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('G'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('H'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('F'.$row.'')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('G'.$row.'')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('H'.$row.'')->getFont()->setBold(true);

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Rename worksheet
    $objPHPExcel->getActiveSheet()
                ->setTitle('CCNSALESTOTAL');

    foreach (range('G', 'H') as $letra) {            
        $objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(20);
    }

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="CCNSalesSummary-Total'.date('MdY').'.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0



    //END OF TOTAL
}else{
    // IF DETAILED
    if($_GET["per"] == "dt") { // IF per date
    
        if(count($cslloc) == 0) {
            // IF EMPTY
        }else{
            
            $counter=0;
            for($i = 0; $i < count($cslloc); $i ++) {
                $totQty=0;
                $gqty=0;	
                $tin='';
                $qry ="SELECT distinct a.cslctp,b.ccprte FROM tbl_ccn_sales_dtl a left join tbl_cssctp".$tbl." b on a.cslctp=b.ccpcod ";
                $qry.="WHERE cslcss=".$cslloc[$i]["cslcss"]." ";
                $qry.=" AND csldat BETWEEN ".$from." AND ".$to." ";
                $qry.=" AND cslloc=".$cslloc[$i]["cslloc"]." AND cslcct=".$cslloc[$i]["cslcct"]."";													
                $res_com_rate = $db->prepare($qry);
                $res_com_rate->execute();
                $fetchs = $res_com_rate->fetchAll();		
                $fetchs2 = $res_com_rate->fetchAll();		
              
                $qry ="SELECT tin FROM tbl_tin".$tbl."  WHERE store=?";																			
                $pTin= $db->prepare($qry);
                $pTin->execute(array($cslloc[$i]["cslloc"]));
                $cTin = $pTin->fetchAll();		

                if(count($cTin) < 1){
                    $tin='0';
                }else{
                    $tin=$cTin[0][0];
                }

                $countComm = count($fetchs);
                for($ii = 0; $ii < $countComm; $ii ++) {
                      
                    $fromL = $fromS;
                    $toL = $toS;	
                    $fromL2 = $fromS;
                    $toL2 = $toS;	
                    $qry = "SELECT 
                            strnum, strnam, glcnam
                        FROM
                            tbl_store_master".$tbl."
                        WHERE
                            strnum = ".$cslloc[$i]["cslloc"]."
                        ORDER BY
                            strnum
                        ASC";
                            
                    $res = $db->prepare($qry);
                    $res->execute();
                    $str = $res->fetchAll();

                    /*----call myClass----*/
                    $myFunct = new myClass();		
                    if( $countComm == 1 ){		
                        $my_total=$myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$ii]["cslctp"],$db);
                        $mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$my_total['total'],$db);					
                        //$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");				
                    }else if( $countComm > 1 ){	
                        /* Process only once with multiple commission rate because this will loop according to num. of comm. rates */
                        if($sbu == 'DS'){
                            $my_total=$myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$ii]["cslctp"],$db);
                            $mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$my_total['total'],$db);					
                            //$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");				
                        }else{						
                            if( $ii == 0 ){
                            $myTotal = 0.00;
                                for($myI = 0; $myI < $countComm; $myI++) {		
                                    $my_total = $myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$myI]["cslctp"],$db);						
                                    $myTotal +=  $my_total['total'];
                                
                                    /*Process only if the last record of comm rate loop*/						
                                    if( $countComm == ($myI+1)) {
                                        $parot = '';
                                        $mySerial = $myFunct->_getSerial($tbl,$cslloc[$i]["cslcss"],$cslloc[$i]["cslloc"],$cslloc[$i]["cslcct"],$from,$to,$myTotal,$db);						
                                    }	
                                    
                                }	
                            //$html .='<table width="100%"><tr><td>'.$fetchs[$myI]["cslctp"].'</td></tr></table>';	
                            }		
                        }

                        //$mpdf->watermarkText =($mySerial[0]['serial'] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");		
                    }

                    //$my_total=$myFunct->_getTotal();
                    if($counter> 0) {
                        //$html .= '<pagebreak /><br />';
                    }

                    if($fetchs[$ii]["cslctp"] != $cslloc[$i]['cslctp']){
                        // 	$fromL = date ("m/d/Y", strtotime("+1 day", strtotime($fromL)));
                        // 	continue;
                        // }
                    }	

                    $counter++;
                    /* STYLE
                    */
                    
                    $style = array(
                        'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                        )
                    );

                
                    
                    
                    $objPHPExcel->createSheet($i);

                    // TOP DETAIL
                    $objPHPExcel->setActiveSheetIndex($i)
                            ->setCellValue('A1', 'CONCESSIONAIRE SUMMARY SALES REPORT')
                            ->setCellValue('A2','Period : '.date("F", strtotime($fromS)).' '.date("d", strtotime($fromS)).'-'.date("d", strtotime($toS)).', '.date("Y", strtotime($fromS)))
                            ->setCellValue('I1', date('m/d/Y H:i:s'));  
                            
                    $objPHPExcel->setActiveSheetIndex($i)
                            ->getStyle('A1')->getFont()->setBold(true);                    

                    // SET TOP DETAIL
                    $objPHPExcel->setActiveSheetIndex($i)
                                ->setCellValue('A3','Company: '.$str[0]["glcnam"].'')
                                ->setCellValue('A4','Store : '.$str[0]["strnum"].'-'.$str[0]["strnam"].'')
                                ->setCellValue('A5','Concessionaire : '.number_format($fetchs[$ii]["ccprte"],0).'')
                                ->setCellValue('A6','Contract # : '.$cslloc[$i]["cslcct"].'-'.number_format($fetchs[$ii]["ccprte"],0).'')
                                ->setCellValue('I3','TIN : '.$tin.'')
                                ->setCellValue('I4','Contract Description : '.$cslloc[$i]["cctdsc"].'');

                                    // SET HEADER
                    $objPHPExcel->setActiveSheetIndex($i)
                                ->setCellValue('A7','DATE')
                                ->setCellValue('B7','CASH SALES')
                                ->setCellValue('E7','CREDIT SALES')
                                ->setCellValue('H7','TOTAL QTY')
                                ->setCellValue('I7','TOTAL AMOUNT')
                                ->setCellValue('B8','REGULAR')
                                ->setCellValue('C8','MARKDOWN')
                                ->setCellValue('D8','TOTAL AMOUNT')
                                ->setCellValue('E8','REGULAR')
                                ->setCellValue('F8','MARKDOWN')
                                ->setCellValue('G8','TOTAL AMOUNT');

                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('A7')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('B7')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('E7')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('H7')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('I7')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('B8')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('C8')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('D8')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('E8')->getFont()->setBold(true);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('F8')->getFont()->setBold(true);  
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle('G8')->getFont()->setBold(true);  

                    $objPHPExcel->setActiveSheetIndex($i)->mergeCells('B7:D7');
                    $objPHPExcel->setActiveSheetIndex($i)->mergeCells('E7:G7');
                    $objPHPExcel->setActiveSheetIndex($i)->mergeCells('A7:A8');
                    $objPHPExcel->setActiveSheetIndex($i)->mergeCells('H7:H8');
                    $objPHPExcel->setActiveSheetIndex($i)->mergeCells('I7:I8');
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("B7:D7")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("E7:G7")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("A7:A8")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("H7:H8")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("I7:I8")->applyFromArray($style);
                    
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("B8")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("C8")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("D8")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("E8")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("F8")->applyFromArray($style);
                    $objPHPExcel->setActiveSheetIndex($i)->getStyle("G8")->applyFromArray($style);

                    //$mpdf->watermarkText =($serial[0]["serial"] ? "".$qry_ctr_fetch[0]['for_ctr']."" : "".$qry_ctr_fetch[0]['not_for_ctr']."");															
                    //$mpdf->setWatermarkText =($serial[0]["serial"] ? " For counter" : "Not for counter");
                    /*if($myFlag==0){
                        $myFlag=($mySerial[0]['serial'] ? "0" :"1");
                    }else{
                        $myFlag=1;
                    }*/

                    $gqty = 0;
                        
                    $gCashRsls = 0;
                    $gCashMsls = 0;
                    $gCash = 0;
                    
                    $gCredRsls = 0;
                    $gCredMsls = 0;
                    $gCred = 0;
                    
                    $gTotsls = 0;
                    $cash_total=0;
                    $credit_total=0;

                    $row = 9;                    

                    while (strtotime($fromL) <= strtotime($toL)) {
                    //while (strtotime($fromL) <= date("m/d/Y", strtotime("+1 day", strtotime($toL)))) {
                        // if($fetchs[$ii]["cslctp"] != $cslloc[$i]['cslctp']){
                        // 	$fromL = date ("m/d/Y", strtotime("+1 day", strtotime($fromL)));
                        // 	continue;
                        // }
                        $dtArr = explode("/", $fromL);
                        $dt = (substr($dtArr[2], 2, 2).$dtArr[0].$dtArr[1]);
                        $totQty = 0;
                        $cash_total=0;
                        $credit_total=0;
                            
                        $qry = "SELECT 
                                    csldat, sum(cslqty) as qty,	sum(csramt - cscamt) as sls
                                FROM
                                    tbl_ccn_sales_dtl
                                WHERE
                                    cslcss = ".$cslloc[$i]["cslcss"]."
                                AND
                                    cslloc = ".$str[0]["strnum"]."
                                AND
                                    cslcct = ".$cslloc[$i]["cslcct"]."
                                AND
                                    csmflg = 'R'
                                AND
                                    csldat = ".$dt."
                                and 
                                    cslctp = ".$fetchs[$ii]['cslctp']."  
                                GROUP BY
                                    csldat";
                                        
                        $res = $db->prepare($qry);
                        $res->execute();
                        $salesCashR = $res->fetchAll();
                        
                        $totQty += $salesCashR[0]["qty"];
                        //$flag += $totQty;
                        $cash_total +=$salesCashR[0]["qty"];
                        
                        
                        $qry = "SELECT 
                                    csldat, sum(cslqty) as qty,	sum(csramt - cscamt) as sls
                                FROM
                                    tbl_ccn_sales_dtl
                                WHERE
                                    cslcss = ".$cslloc[$i]["cslcss"]."
                                AND
                                    cslloc = ".$str[0]["strnum"]."
                                AND
                                    cslcct = ".$cslloc[$i]["cslcct"]."
                                AND
                                    csmflg = 'M'
                                AND
                                    csldat = ".$dt."
                                and 
                                    cslctp = ".$fetchs[$ii]['cslctp']."   						 
                                    
                                GROUP BY
                                    csldat";
                                    
                        $res = $db->prepare($qry);
                        $res->execute();
                        $salesCashM = $res->fetchAll();
                        
                        $totQty += $salesCashM[0]["qty"];
                        $cash_total += $salesCashM[0]["qty"];
                        
                        $qry = "SELECT 
                                    csldat, sum(cslqty) as qty,	sum(cscamt) as sls
                                FROM
                                    tbl_ccn_sales_dtl
                                WHERE
                                    cslcss = ".$cslloc[$i]["cslcss"]."
                                AND
                                    cslloc = ".$str[0]["strnum"]."
                                AND
                                    cslcct = ".$cslloc[$i]["cslcct"]."
                                AND
                                    csmflg = 'R'
                                AND
                                    csldat = ".$dt."
                                and 
                                    cslctp = ".$fetchs[$ii]['cslctp']."   						 
                                GROUP BY
                                    csldat";
                                        
                        $res = $db->prepare($qry);
                        $res->execute();
                        $salesCredR = $res->fetchAll();
                            
                            
                        $totQty += $salesCredR[0]["qty"];
                        $credit_total +=$salesCredR[0]["qty"];
                        $qry = "SELECT 
                                    csldat, sum(cslqty) as qty,	sum(cscamt) as sls
                                FROM
                                    tbl_ccn_sales_dtl
                                WHERE
                                    cslcss = ".$cslloc[$i]["cslcss"]."
                                AND
                                    cslloc = ".$str[0]["strnum"]."
                                AND
                                    cslcct = ".$cslloc[$i]["cslcct"]."
                                AND
                                    csmflg = 'M'
                                AND
                                    csldat = ".$dt."
                                and 
                                    cslctp = ".$fetchs[$ii]['cslctp']."   
                                                                
                                GROUP BY
                                    csldat";
                                        
                        $res = $db->prepare($qry);
                        $res->execute();
                        $salesCredM = $res->fetchAll();
                        
                        $totQty += $salesCredM[0]["qty"];
                        $credit_total += $salesCredM[0]["qty"];
                        if($cash_total>0){
                            $totQty=$cash_total;
                        }else{
                            $totQty=$credit_total; 
                        }

                        $rowb = empty($salesCashR[0]["sls"]) ? '0.00':$salesCashR[0]["sls"];
                        $rowc = empty($salesCashM[0]["sls"]) ? '0.00':$salesCashM[0]["sls"];
                        $dd = ($salesCashR[0]["sls"] + $salesCashM[0]["sls"]);
                        $rowd = empty($dd) ? '0.00':$dd;
                        $rowe = empty($salesCredR[0]["sls"]) ? '0.00':$salesCredR[0]["sls"];
                        $rowf = empty($salesCredM[0]["sls"]) ? '0.00':$salesCredM[0]["sls"];
                        $gg = ($salesCredR[0]["sls"] + $salesCredM[0]["sls"]);
                        $rowg = empty($gg) ? '0.00':$gg;
                        $rowh = empty($totQty) ? '0.00':$totQty;
        
                        $xx = ($salesCashR[0]["sls"] + $salesCashM[0]["sls"] + $salesCredR[0]["sls"] + $salesCredM[0]["sls"]);
                        $rowi = empty($xx) ? '0.00':$xx;

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->setCellValue('A'.$row.'',$fromL)
                                    ->setCellValue('B'.$row.'',$rowb)
                                    ->setCellValue('C'.$row.'',$rowc)
                                    ->setCellValue('D'.$row.'',$rowd)
                                    ->setCellValue('E'.$row.'',$rowe)
                                    //->setCellValue('E'.$row.'',$salesCredM[0]["sls"])
                                    ->setCellValue('F'.$row.'',$rowf)
                                    ->setCellValue('G'.$row.'',$rowg)
                                    ->setCellValue('H'.$row.'',$rowh)
                                    ->setCellValue('I'.$row.'',$rowi);


                        $objPHPExcel->setActiveSheetIndex($i)->getStyle('B'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
                        $objPHPExcel->setActiveSheetIndex($i)->getStyle('C'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
                        $objPHPExcel->setActiveSheetIndex($i)->getStyle('D'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
                        $objPHPExcel->setActiveSheetIndex($i)->getStyle('E'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
                        $objPHPExcel->setActiveSheetIndex($i)->getStyle('F'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
                        $objPHPExcel->setActiveSheetIndex($i)->getStyle('G'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
                        $objPHPExcel->setActiveSheetIndex($i)->getStyle('H'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');
                        $objPHPExcel->setActiveSheetIndex($i)->getStyle('I'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                            
                        
                        
                        $gqty += $totQty;
                        
                        $gCashRsls += $salesCashR[0]["sls"];
                        $gCashMsls += $salesCashM[0]["sls"];
                        $gCash += ($salesCashR[0]["sls"] + $salesCashM[0]["sls"]);
                        
                        $gCredRsls += $salesCredR[0]["sls"];
                        $gCredMsls += $salesCredM[0]["sls"];
                        $gCred += ($salesCredR[0]["sls"] + $salesCredM[0]["sls"]);
                        
                        $gTotsls += ($salesCashR[0]["sls"] + $salesCashM[0]["sls"] + $salesCredR[0]["sls"] + $salesCredM[0]["sls"]);

                        if(strtotime($fromL) == strtotime($toL)){
                            $objPHPExcel->setActiveSheetIndex($i)
                                        ->setCellValue('A'.($row + 1).'','TOTAL')
                                        ->setCellValue('B'.($row + 1).'',$gCashRsls)
                                        ->setCellValue('C'.($row + 1).'',$gCashMsls)
                                        ->setCellValue('D'.($row + 1).'',$gCash)
                                        ->setCellValue('E'.($row + 1).'',$gCredRsls)
                                        ->setCellValue('F'.($row + 1).'',$gCredMsls)
                                        ->setCellValue('G'.($row + 1).'',$gCred)
                                        ->setCellValue('H'.($row + 1).'',$gqty)
                                        ->setCellValue('I'.($row + 1).'',$gTotsls);
                            
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('B'.($row + 1).'')->getNumberFormat()->setFormatCode('#,##0.00');
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('C'.($row + 1).'')->getNumberFormat()->setFormatCode('#,##0.00');
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('D'.($row + 1).'')->getNumberFormat()->setFormatCode('#,##0.00');
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('E'.($row + 1).'')->getNumberFormat()->setFormatCode('#,##0.00');
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('F'.($row + 1).'')->getNumberFormat()->setFormatCode('#,##0.00');
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('G'.($row + 1).'')->getNumberFormat()->setFormatCode('#,##0.00');
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('H'.($row + 1).'')->getNumberFormat()->setFormatCode('#,##0.00');
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('I'.($row + 1).'')->getNumberFormat()->setFormatCode('#,##0.00');

                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('A'.($row + 1).'')->getFont()->setBold(true);
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('B'.($row + 1).'')->getFont()->setBold(true);
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('C'.($row + 1).'')->getFont()->setBold(true);
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('D'.($row + 1).'')->getFont()->setBold(true);
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('E'.($row + 1).'')->getFont()->setBold(true);
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('F'.($row + 1).'')->getFont()->setBold(true);
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('G'.($row + 1).'')->getFont()->setBold(true);
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('H'.($row + 1).'')->getFont()->setBold(true);
                            $objPHPExcel->setActiveSheetIndex($i)->getStyle('I'.($row + 1).'')->getFont()->setBold(true);
                        }else{
                            $row += 1;
                        }                 
                        $fromL = date ("m/d/Y", strtotime("+1 day", strtotime($fromL)));   
                    } /* End of while loop */		  
                    
                    $objPHPExcel->getActiveSheet()
                                ->setTitle(''.$cslloc[$i]["cslcct"].'-'.number_format($fetchs[$ii]["ccprte"],0).'');

                    foreach(range('A', 'I') as $letra) {            
                        $objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(15);
                    }
                    
                         
                }/* End For loop*/

                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="CCNSalesSummary-byDate'.date('MdY').'.xls"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

            }
        }

    }else{ // if per sku
        // Set document properties

        if(count($cslloc) == 0) {
            //no data
        }
        else {
           

            $counter=0;
            for($i = 0; $i < count($cslloc); $i ++) {
                $tin='';
                $qry2 ="SELECT distinct a.cslctp,b.ccprte FROM tbl_ccn_sales_dtl a left join tbl_cssctp".$tbl." b on a.cslctp=b.ccpcod  ";
                $qry2.="WHERE a.cslcss=".$cslloc[$i]["cslcss"]."  ";
                $qry2.=" AND a.csldat BETWEEN ".$from." AND ".$to."  ";
                $qry2.=" AND a.cslloc=".$cslloc[$i]["cslloc"]." AND a.cslcct=".$cslloc[$i]["cslcct"]."";													
                $res_com_rate = $db->prepare($qry2);
                $res_com_rate->execute();
                $fetchs = $res_com_rate->fetchAll();	
                
                $qry ="SELECT tin FROM tbl_tin".$tbl."  WHERE store=?";																			
                $pTin= $db->prepare($qry);
                $pTin->execute(array($cslloc[$i]["cslloc"]));
                $cTin = $pTin->fetchAll();		

                if(count($cTin) < 1){
                  $tin='0';
                }else{
                  $tin=$cTin[0][0];
                } 

                for($ii = 0;$ii < count($fetchs); $ii++){		
                //get serial number //tbl_ccn_sales_srl tbl_ccn_sales_srl tbl_ccn_sales_srl					 
                $fromL2 = $fromS;
                $toL2 = $toS;		
                $qry = "SELECT 
                            strnum, strnam, glcnam
                        FROM
                            tbl_store_master".$tbl."
                        WHERE
                            strnum = ".$cslloc[$i]["cslloc"]."
                        ORDER BY
                            strnum
                        ASC";								
                $res = $db->prepare($qry);
                $res->execute();
                $str = $res->fetchAll();
                if($ii == 0){					
                    $myFunct=new myClass();	
                    $my_total=$myFunct->_serialAmt($fromL2,$toL2,$dtArr,$cslloc[$i]["cslcss"],$str[0]["strnum"],$cslloc[$i]["cslcct"],$fetchs[$ii]["cslctp"],$db);	
                }
                $qry_srl = "SELECT 
                                serial, srlamt
                            FROM
                                tbl_ccn_sales_srl".$tbl."  
                            WHERE
                                srlcnv = ".$cslloc[$i]["cslcss"]."
                            AND
                                srlnum = ".$cslloc[$i]["cslloc"]."
                            AND
                                srlcnn = ".$cslloc[$i]["cslcct"]."
                            AND
                                srlfmd = ".$from."
                            AND
                                srltod = ".$to."
                            AND
                                 srlamt =".$my_total['total']."	

                            ORDER BY
                                serial DESC
                            limit 1	
                            ";									
                $res_srl = $db->prepare($qry_srl);
                $res_srl->execute();
                $serial = $res_srl->fetchAll();
                //tbl_store_master
                                
                if($counter > 0) {
                    //$html .= '<pagebreak /><br />';
                }
                $counter++;	

                $objPHPExcel->createSheet($i);



                // TOP DETAIL
                $objPHPExcel->setActiveSheetIndex($i)
                        ->setCellValue('A1', 'CONCESSIONAIRE SUMMARY SALES REPORT')
                        ->setCellValue('A2','Period : '.date("F", strtotime($fromS)).' '.date("d", strtotime($fromS)).'-'.date("d", strtotime($toS)).', '.date("Y", strtotime($fromS)))
                        ->setCellValue('F1', date('m/d/Y H:i:s'));  
                        
                $objPHPExcel->setActiveSheetIndex($i)
                        ->getStyle('A1')->getFont()->setBold(true);



                // SET TOP DETAIL
                $objPHPExcel->setActiveSheetIndex($i)
                            ->setCellValue('A3','Company: '.$str[0]["glcnam"].'')
                            ->setCellValue('C3','Store : '.$str[0]["strnum"].'-'.$str[0]["strnam"].'')
                            ->setCellValue('A4','Comm Rate : '.number_format($fetchs[$ii]["ccprte"],0).'')
                            ->setCellValue('C4','TIN : '.$tin.'')
                            ->setCellValue('A5','Supplier : '.$cslloc[$i]["cslcss"].' - '.$cslloc[$i]["v_name"].'')
                            ->setCellValue('C5','Contract Description : '.$cslloc[$i]["cctdsc"].'')
                            ->setCellValue('A6','Contract # : '.$cslloc[$i]["cslcct"].'-'.number_format($fetchs[$ii]["ccprte"],0).'');
                
                if ($_SESSION["gid"] != 2) {
                    $objPHPExcel->setActiveSheetIndex($i)
                                ->setCellValue('C6','Serial # : '.$serial[0]["serial"].'');
                }  		
                            

                // SET HEADER
                $objPHPExcel->setActiveSheetIndex($i)
                            ->setCellValue('A7','SKU')
                            ->setCellValue('B7','DESCRIPTION')
                            ->setCellValue('C7','CASH SALES')
                            ->setCellValue('D7','CREDIT SALES')
                            ->setCellValue('E7','QTY')
                            ->setCellValue('F7','TOTAL AMOUNT');            

                $objPHPExcel->setActiveSheetIndex($i)
                            ->getStyle('A7')->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex($i)
                            ->getStyle('B7')->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex($i)
                            ->getStyle('C7')->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex($i)
                            ->getStyle('D7')->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex($i)
                            ->getStyle('E7')->getFont()->setBold(true);
                
                $objPHPExcel->setActiveSheetIndex($i)
                            ->getStyle('F7')->getFont()->setBold(true);

                $gcssls = 0;
                $gcrsls = 0;
                $gqty = 0;
                $gsls = 0;
                //tbl_ccn_sales_dtl tbl_ccn_sales_dtl
                $qry = "SELECT 
                            cslsku, idescr, sum(cslqty) as qty, sum(csramt) as net
                        FROM
                            tbl_ccn_sales_dtl
                        WHERE
                            cslcss = ".$cslloc[$i]["cslcss"]."
                        AND
                            cslloc = ".$cslloc[$i]["cslloc"]."
                        AND
                            cslcct = ".$cslloc[$i]["cslcct"]."
                        AND 
                            cslctp=".$fetchs[$ii]["cslctp"]." 
                        AND
                            csldat BETWEEN ".$from." AND ".$to." 
                        GROUP BY
                            cslsku
                        ORDER BY
                            cslsku";
                            
                $res = $db->prepare($qry);
                $res->execute();
                $sales = $res->fetchAll();
                
                $row = 8;
                for($j = 0; $j <= count($sales); $j ++) {
                    //tbl_ccn_sales_dtl------------------------------------------

                    if($j < count($sales)){
                        $qry = "SELECT 
                                    cslsku,	sum(csramt - cscamt) as sls
                                FROM
                                    tbl_ccn_sales_dtl
                                WHERE
                                    cslcss = ".$cslloc[$i]["cslcss"]."
                                AND
                                    cslloc = ".$str[0]["strnum"]."
                                AND
                                    cslcct = ".$cslloc[$i]["cslcct"]."
                                AND
                                    cslsku = ".$sales[$j]["cslsku"]."
                                AND 
                                    cslctp=".$fetchs[$ii]["cslctp"]."  
                                AND
                                    csldat BETWEEN ".$from." AND ".$to."
                                GROUP BY
                                    cslsku";
                                    
                        $res = $db->prepare($qry);
                        $res->execute();
                        $salesCash = $res->fetchAll();
                        
                        
                        //tbl_ccn_sales_dtl
                        $qry = "SELECT 
                                    cslsku,	sum(cscamt) as sls
                                FROM
                                    tbl_ccn_sales_dtl
                                WHERE
                                    cslcss = ".$cslloc[$i]["cslcss"]."
                                AND
                                    cslloc = ".$str[0]["strnum"]."
                                AND
                                    cslcct = ".$cslloc[$i]["cslcct"]."
                                AND
                                    cslsku = ".$sales[$j]["cslsku"]."
                                AND 
                                    cslctp=".$fetchs[$ii]["cslctp"]."  
                                AND	
                                    csldat BETWEEN ".$from." AND ".$to."
                                GROUP BY
                                    cslsku";
                                    
                        $res = $db->prepare($qry);
                        $res->execute();
                        $salesCred = $res->fetchAll();
                        //echo $sales[$j]["cslsku"];
                        //exit;
                        //echo count($salesCred);
                        //exit;
                    
                        // SET HEADER
                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->setCellValue('A'.$row.'',$sales[$j]["cslsku"])
                                    ->setCellValue('B'.$row.'',$sales[$j]["idescr"])
                                    ->setCellValue('C'.$row.'',$salesCash[0]["sls"])
                                    ->setCellValue('D'.$row.'',$salesCred[0]["sls"])
                                    ->setCellValue('E'.$row.'',$sales[$j]["qty"])
                                    ->setCellValue('F'.$row.'',$sales[$j]["net"]);
                        
                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('C'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('D'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('E'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('F'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                        
                        $gcssls += $salesCash[0]["sls"];
                        $gcrsls += $salesCred[0]["sls"];
                        $gqty += $sales[$j]["qty"];
                        $gsls += $sales[$j]["net"];
                        $row += 1;

                    }if($j == count($sales)){
                        // FOOTER HERE
                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->setCellValue('A'.$row.'','TOTAL')
                                    ->setCellValue('C'.$row.'',$gcssls)
                                    ->setCellValue('D'.$row.'',$gcrsls)
                                    ->setCellValue('E'.$row.'',$gqty)
                                    ->setCellValue('F'.$row.'',$gsls);
                        
                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('A'.$row.'')->getFont()->setBold(true);

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('C'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('C'.$row.'')->getFont()->setBold(true);

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('D'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('D'.$row.'')->getFont()->setBold(true);

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('E'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('E'.$row.'')->getFont()->setBold(true);

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('F'.$row.'')->getNumberFormat()->setFormatCode('#,##0.00');

                        $objPHPExcel->setActiveSheetIndex($i)
                                    ->getStyle('F'.$row.'')->getFont()->setBold(true);
                    }
                    

                    // Rename worksheet
                    $objPHPExcel->getActiveSheet()
                                ->setTitle(''.$cslloc[$i]["cslcct"].'-'.number_format($fetchs[$ii]["ccprte"],0).'');
                    }

                    foreach (range('C', 'F') as $letra) {            
                        $objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(20);
                    }
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                }			
            }

        }





        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="CCNSalesSummary-DetailedBySKU'.date('MdY').'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
    }    
}






// BEGIN DOWNLOADING
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;