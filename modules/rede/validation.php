<?php
include(dirname(__FILE__) . '/../../config/config.inc.php');
include(dirname(__FILE__) . '/rede.php');

$rede = new Rede();

try {
    $rede->validate($_POST);
    $rede->pay($_POST);
} catch (Exception $e) {
    $error = new stdClass();
    $error->error = $e->getMessage();

    echo json_encode($error);
}