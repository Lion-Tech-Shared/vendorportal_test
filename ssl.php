<?php # OpenSSL_HTTPS_file_get_contents.php
#- Copyright (c) 2011, HerongYang.com, All Rights Reserved.
#
$script = array_shift($argv);
$url = array_shift($argv);

$html = file_get_contents($url);
echo $html;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>