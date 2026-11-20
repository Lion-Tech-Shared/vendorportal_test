<?PHP
	/*set_time_limit(10);

	if (! defined ( "olMailItem" ))
	{
		define ("olMailItem", 0 );
	}*/

	//try {
		/*$objApp = new COM ( "Outlook.Application" ) or die ( "Cannot Load Outlook.Application" );
		$namespace = $objApp->GetNamespace("MAPI");  // or MAPI.Session
		$namespace->Logon();
		$myItem = $objApp->CreateItem ( olMailItem );
		$myItem->To = "itis@lccta.lccgroup.com";
		//$myItem->SentOnBehalfOfName = "yyy@xxyyx.com";
		$myItem->Subject = "This is a test";
		$myItem->Body = "This is a Body Section now…..!";
		$myItem->Display ();*/

	/*} catch ( Exception $e ) {
		var_dump ( $e->getMessage () );
		debug_print_backtrace ();
	}*/
	
	$to = "itis@lccta.lccgroup.com";
	$subject = "TEST SUBJECT";
	
	$message = '<html>
				<head>
				</head>
				<body>
					<font face="Calibri"> 
					Hello <b>_____</b>,<br/><br/>
					
					Welcome to Supplier Portal. You can now access the system using your access details:<br/><br/>
					
					Your Username : <b>____</b><br/>
					Your Password : <b>____</b><br/><br/>
					
					Please keep your access details confidential at all times.<br/>
					Visit our portal at _____ <br/><br/>
					
					Thanks,<br/>
					<b>LCC</b>
					</font>
				</body>
				</html>';
				
	$headers = 'MIME-Version 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
	
	$headers .= 'From: LCC<itis@lccta.lccgroup.com>'."\r\n";
	
	mail($to, $subject, $message, $headers);
?>