<?PHP
	function systemTimeToCode($time_to_convert) {
		$separate_time = explode('-', $time_to_convert);
		$result = '0';
		
		if(strtoupper($separate_time[1]) == "AM")
			$result = $separate_time[0].'01';
		else
			$result = $separate_time[0].'02';		

		return $result;
	}

	function numberToAlpha($number) {
		$alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		
		return substr($alpha, $number - 1, 1);
	}

	function generatePass($code) {	
		$result = $code + $sysdate + systemTimeToCode(date("hi-A"));
		$result = $result."".numberToAlpha($result % 10);
		
		return $result;
	}

	# feature to be activated for the vendor
	#      1 = vendor only
	#      2 = Principal
	#      3 = Both
	function setFeature($array_stat) {
		if(isset($array_stat)) {
			$count = 1;
			foreach($array_stat as $result) {
				$count += $result;
			}			
			return $count -= 1;
		}
	}

	function generateFileName($fname) {
		return $fname .'-'.date('YmdHis', strtotime($SYSTEM_DATETIME)).'.CSV';
	}
	function sysMsg(){
		return '<strong>This is a System Generated Email, Please do not reply</strong>.';
	}
	function sendMail($to, $title, $msg) {
		$mail = new PHPMailer();
		
		$host = "192.168.0.1";
		//$host = "mail2.lccgroup.com";	
		$from = "lccportal.admin@lccgroup.com";
		$fromName = "Vendor Portal";
		
		$subject = "LCC Portal Login Details";

		
		$message = '<html>
					<head>
					</head>
					<body style="border:1px solid; background-color:#d9edf7;font-style:tahoma;">
						<font face="Calibri"> 
						<h4>'.$title.'</h4><br>
						<p>Click or Copy and paste then browse the link below to reset passwords.</p>
						<br>
						<a href="'.$msg.'">'.$msg.'</a><br><br>
						'.sysMsg().'
						<br><br><br>
						Thank you,<br/>
						<b>LCC Vendor Portal Administrator</b>
						</font>
					</body>
					</html>';
		
		//'.$pwd.'
		
		$mail->IsSMTP();
		
		$mail->Host = $host;
		
		$mail->From = $from;
		$mail->FromName = $fromName;
		
		$mail->AddAddress($to);

		$mail->Subject = $subject;
		$mail->Body = $message;
		
		//$mail->AddAttachment("../../files/User_Manual.pptx");
		
		$mail->IsHTML(true);
		$mail->Send();
		return 1;
		exit;
		if($mail->validateAddress($to)){
			//echo $mail->preSend();
			 if($mail->Send())
			 	return 1;
			 else
			 	return 0;	
		}else{
			//echo 'Invalid Email';
			return -1;
		}
	} 
	function validate_email($to, $title){
		//header("Location:../home.php");
		$mail = new PHPMailer();		 
		$mail->IsSMTP();
		$mail->AddAddress($to); 
		
		//$mail->AddAttachment("../../files/User_Manual.pptx");
		
		$mail->IsHTML(true);
		
		if($mail->validateAddress($to)){
				
		}else{
			//invalid email
			return 3;					
		}
	}
	function sendFirstMsg($to, $title){
		//header("Location:../home.php");
		$mail = new PHPMailer();
		
		$host = "192.168.0.1";		
		//$host = "mail2.lccgroup.com";		
		$from = "lccportal.admin@lccgroup.com";
		$fromName = "Vendor Portal";
		
		$subject = "LCC Portal Login Details";
		$msg="You have successfully changed your password from LCC Vendor Portal.";
		$title='Hi!';
		$message = '<html>
					<head>					 
					<body style="border:0px solid; background-color:#d9edf7;">
						<font face="Calibri"> 
						<h4>'.$title.'</h4>
						<div class="alert alert-info">
						'.$msg.'</div><br><br>
						 
						'.sysMsg().'
						<br><br><br>
						Thank you,<br/>
						<b>LCC Vendor Portal Administrator</b>
						</font>
						<!--<div class="col-xs-12 col-md-6 col-lg-3">
							<div class="panel panel-orange panel-widget">
								<div class="row no-padding">
									<div class="col-sm-3 col-lg-5 widget-left">
										<svg class="glyph stroked empty-message"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-empty-message"></use></svg>
										<span class="glyphicon glyphicon-transfer fa-3x"></span>
									</div>
									<div class="col-sm-9 col-lg-7 widget-right">
										<div class="large">23</div>
										<div class="text-muted">Transfer</div>
									</div>
								</div>
							</div>
						</div>-->
					</body>
					</html>';		
		//'.$pwd.'		
		$mail->IsSMTP();
		//$mail->port();
		
		$mail->Host = $host;
		
		$mail->From = $from;
		$mail->FromName = $fromName;		
		$mail->AddAddress($to);
		$mail->Subject = $subject;
		$mail->Body = $message;		
		//$mail->AddAttachment("../../files/User_Manual.pptx");		
		$mail->IsHTML(true);					 
		$mail->Send();					 	
	}
	function mysql_aes_decrypt($val,$ky) {
		$key="\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
		for($a=0;$a<strlen($ky);$a++) $key[$a%16]=chr(ord($key[$a%16]) ^ ord($ky[$a]));
		$mode = MCRYPT_MODE_ECB;
		$enc = MCRYPT_RIJNDAEL_128;
		$dec = @mcrypt_decrypt($enc, $key, $val, $mode, @mcrypt_create_iv( @mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM ) );
		return rtrim($dec,(( ord(substr($dec,strlen($dec)-1,1))>=0 and ord(substr($dec, strlen($dec)-1,1))<=16)? chr(ord( substr($dec,strlen($dec)-1,1))):null));
	}

	function mysql_aes_encrypt($val,$ky) {
		$key="\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
		for($a=0;$a<strlen($ky);$a++) $key[$a%16]=chr(ord($key[$a%16]) ^ ord($ky[$a]));
		$mode=MCRYPT_MODE_ECB;
		$enc=MCRYPT_RIJNDAEL_128;
		$val=str_pad($val, (16*(floor(strlen($val) / 16)+(strlen($val) % 16==0?2:1))), chr(16-(strlen($val) % 16)));
		return mcrypt_encrypt($enc, $key, $val, $mode, mcrypt_create_iv( mcrypt_get_iv_size($enc, $mode), MCRYPT_DEV_URANDOM));
	}
	
	function strToHex($string){
		$hex='';
		for ($i=0; $i < strlen($string); $i++){
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}


	function hexToStr($hex){
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}

	function randomstring($len = 32){
		$string = "";
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for($i=0;$i<$len;$i++)
		$string.=substr($chars,rand(0,strlen($chars)),1);
		return $string;
	}
?>
