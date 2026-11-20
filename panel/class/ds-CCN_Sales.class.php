<?php
class CCNSales{
    public function __construct($db){
        $this->conn = $db;
    }
	
	public function getCslcss($asnum) {
		$out = array();
		
		$qry = "SELECT 
					LCNVDR
				FROM 
					LCSUCN
				WHERE
					LVENDR = ".$asnum."
				";
				
		$stmt = odbc_prepare($this->conn, $qry);
		odbc_execute($stmt);
		
        while(odbc_fetch_row($stmt)){
			$out[] = odbc_result($stmt, 1);
		}
		
		return $out;
	}
    
    public function getLocSales($cslcss, $perloc = false, $peryear = false, $year = 0){
		$out = array();
		$addFld = array();
		$numFlds = 2;
		
		if($perloc) {
			$addFld[] = "CSLLOC";
			$numFlds ++;
		}
		
		if($peryear) {
			$addFld[] = "CSLFYR";
			$numFlds ++;
		}
		
		$qry = "SELECT 
					".($numFlds > 2 ? implode(", ", $addFld).", " : "")."SUM(CSRAMT) AS NET_SALE, SUM(CSGAMT) AS GROSS_SALE
				FROM 
					CSSCSL
				WHERE
					CSLCSS = ".$cslcss;
					
		if($numFlds > 2) {
			$qry .= "GROUP BY
						".implode(", ", $addFld)."
					ORDER BY
						".implode(", ", $addFld)."
					ASC";
		}
				
		$stmt = odbc_prepare($this->conn, $qry);
		odbc_execute($stmt);
     
        while(odbc_fetch_row($stmt)){
			$col = array();
			
			for($i = 0; $i < $numFlds; $i++) {
				$col[] = trim(odbc_result($stmt, $i + 1));
			}
			
			$out[] = $col;
		}
		
		return $out;
    }
	
	public function getYears($cslcss) {
		$qry = "SELECT 
					SUM(CSRAMT) AS NET_SALE, SUM(CSGAMT) AS GROSS_SALE
				FROM 
					CSSCSL
				WHERE
					CSLCSS = ".$cslcss;
		
		$stmt = odbc_prepare($this->conn, $qry);
		odbc_execute($stmt);
     
        while(odbc_fetch_row($stmt)){
			$col = array();
			
			for($i = 0; $i < $numFlds; $i++) {
				$col[] = trim(odbc_result($stmt, $i + 1));
			}
			
			$out[] = $col;
		}
		
		return $out;
	}
}