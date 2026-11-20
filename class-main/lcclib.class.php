<?PHP
	class lcclib{
		public function linkCompo() {
			$out = "http://";
			$out .= $_SERVER["SERVER_ADDR"];
			$out .= "/lcclib";
			
			return $out;
		}
		
		public function linkMain() {
			$out = "http://";
			$out .= $_SERVER["SERVER_ADDR"];
			$out .= "/supplierportal";
			
			return $out;
		}
	}
?>