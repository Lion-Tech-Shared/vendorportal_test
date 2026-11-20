<?php
$to = 'destination-address@somewhere.com';
$from = 'source@somewhere.com';
$subject = 'See Attachments';
$message = 'Please review the following attachments.';

// Define a list of FILES to send along with the e-mail. Key = File to be sent. Value = Name of file as seen in the e-mail.
$attachments = $_SERVER["DOCUMENT_ROOT"].'/ccnportal/files/User_Manual.pptx' => 'first-attachment.png';

// Define any additional headers you may want to include
$headers = array(
	'Reply-to' => 'source@somewhere.com',
	'Some-Other-Header-Name' => 'Header Value'
);

$status = mailAttachments($to, $from, $subject, $message, $attachments, $headers);
if($status === True) {
	print 'Successfully mailed!';
} else {
	print 'Unable to send e-mail.';
}

function mailAttachments($to, $from, $subject, $message, $attachments = array(), $headers = array(), $additional_parameters = '') {
	$headers['From'] = $from;

	// Define the boundray we're going to use to separate our data with.
	$mime_boundary = '==MIME_BOUNDARY_' . md5(time());

	// Define attachment-specific headers
	$headers['MIME-Version'] = '1.0';
	$headers['Content-Type'] = 'multipart/mixed; boundary="' . $mime_boundary . '"';

	// Convert the array of header data into a single string.
	$headers_string = '';
	foreach($headers as $header_name => $header_value) {
		if(!empty($headers_string)) {
			$headers_string .= "\r\n";
		}
		$headers_string .= $header_name . ': ' . $header_value;
	}

	// Message Body
	$message_string  = '--' . $mime_boundary;
	$message_string .= "\r\n";
	$message_string .= 'Content-Type: text/plain; charset="iso-8859-1"';
	$message_string .= "\r\n";
	$message_string .= 'Content-Transfer-Encoding: 7bit';
	$message_string .= "\r\n";
	$message_string .= "\r\n";
	$message_string .= $message;
	$message_string .= "\r\n";
	$message_string .= "\r\n";

	// Add attachments to message body
	foreach($attachments as $local_filename => $attachment_filename) {
		if(is_file($local_filename)) {
			$message_string .= '--' . $mime_boundary;
			$message_string .= "\r\n";
			$message_string .= 'Content-Type: application/octet-stream; name="' . $attachment_filename . '"';
			$message_string .= "\r\n";
			$message_string .= 'Content-Description: ' . $attachment_filename;
			$message_string .= "\r\n";

			$fp = @fopen($local_filename, 'rb'); // Create pointer to file
			$file_size = filesize($local_filename); // Read size of file
			$data = @fread($fp, $file_size); // Read file contents
			$data = chunk_split(base64_encode($data)); // Encode file contents for plain text sending

			$message_string .= 'Content-Disposition: attachment; filename="' . $attachment_filename . '"; size=' . $file_size.  ';';
			$message_string .= "\r\n";
			$message_string .= 'Content-Transfer-Encoding: base64';
			$message_string .= "\r\n\r\n";
			$message_string .= $data;
			$message_string .= "\r\n\r\n";
		}
	}

	// Signal end of message
	$message_string .= '--' . $mime_boundary . '--';

	// Send the e-mail.
	return mail($to, $subject, $message_string, $headers_string, $additional_parameters);
}
?>