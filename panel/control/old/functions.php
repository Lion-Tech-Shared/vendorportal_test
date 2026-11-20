<?PHP
	switch($_GET["lctr"]) {
		case 1:
			header("Location: ../home.php?active=".md5(6)."&id=".$_GET["id"]);
			break;
		case 2:
			header("Location: ../home.php?active=".md5(8)."&id=".$_GET["id"]);
			break;
	}
?>