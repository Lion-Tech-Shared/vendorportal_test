<?PHP
	$array  = array('microsoft','micromax', 'miniclip','michael jackson','million','milky way');
	$input  = urldecode($_GET['word']); //Get input word/phrase (decode in case of spaces etc.)
	$length = strlen($input);           //Get length of input word
	// $min    = $length - 1;              //Length of word - 1
	// $max    = $length + 1;              //Length of word + 1

	$returned = preg_grep('/^(['.$input.']{'.$length.'}.*)$/i', $array); //Find matches in $array and return as array
	$returned = array_values($returned);                                //Re-index from 0

	echo json_encode($returned); //Return json string to ajax call
?>