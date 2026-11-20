<?php

if(isset($_POST['vcode'])){

    $host = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";

    $vcode = $_POST['vcode'];
    $store = $_POST['store'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    $sbu = $_POST['sbu'];
    $format = $_POST['format'];
    $per = $_POST['per'];
    $isv = $_POST['isv'];
    
    
    
    echo json_encode(array(
        'host'      => $host,
        'vcode'     => $vcode,
        'store'     => $store,
        'from'      => $from,
        'to'        => $to,
        'sbu'       => $sbu,
        'format'    => $format,
        'per'       => $per,
        'isv'       => $isv
    ));

}













