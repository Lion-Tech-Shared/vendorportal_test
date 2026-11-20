<?PHP
function SQL($Query) {
	global $db;
	$args = func_get_args();
	
	if (count($args) == 1) {
		$result = $db->query($Query);
		if ($result->num_rows) {
			$out = array();
			while (null != ($r = $result->fetch_array(MYSQLI_ASSOC))) {
				$out [] = $r;
			}
			return $out;
		}
		return null;
	} 
	else {
		if (!$stmt = $db->prepare($Query)) {
				trigger_error("Unable to prepare statement: {$Query}, reason: " . $db->error . "");
			}
			array_shift($args); //remove $Query from args
			$a = array();
			foreach ($args as $index => &$value) {
				$a[$index] = &$value;
			}
			$types = str_repeat("s", count($args));
			array_unshift($a, $types);
			call_user_func_array(array($stmt, 'bind_param'), $a);
			$stmt->execute();
			$metadata = $stmt->result_metadata();
			$out = array();
			$fields = array();
			while ($field = mysqli_fetch_field($metadata)) {
				$fields [] = &$out [$field->name];
			}
			call_user_func_array(array($stmt, "bind_result"), $fields);
			$output = array();
			$count = 0;
			while ($stmt->fetch()) {
				foreach ($out as $index => $value) {
					$output [$count] [$index] = $value;
				}
				$count++;
			}
		$stmt->free_result();
		return ($count == 0) ? null : $output;
	}
}
?>