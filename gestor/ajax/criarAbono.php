<?php

require '../../classes/sql.class.php';
$sql = new SQL();

session_start();
#echo "<pre>";print_r($_REQUEST);exit;


$criarAbono = $sql->insertAbono($_REQUEST);
echo json_encode($criarAbono);exit;
