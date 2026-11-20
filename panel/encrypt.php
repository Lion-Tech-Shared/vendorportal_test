<?PHP
require_once "../control/functions.php";
$uid="740015";
$pwd=$uid;
echo strToHex(mysql_aes_encrypt($pwd,MD5($uid)));
//strToHex(mysql_aes_encrypt($pwd, MD5($uid))
?>