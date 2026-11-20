<?php

$val=$_GET['val'];

if (filter_var($val, FILTER_VALIDATE_INT) === false) {
  -1;
}

?>