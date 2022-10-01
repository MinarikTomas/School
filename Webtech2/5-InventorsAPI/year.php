<?php
require_once 'Invention.php';
require_once 'Inventor.php';
require_once 'MyPdo.php';
header('Content-Type: application/json; charset=utf-8');

$year = $_GET['year'];
http_response_code(200);
if ($year) {
    $inventors = Inventor::findByYear($year);
    $inventions = Invention::findByYear($year);
    if(!empty($inventors)){
        $data['inventors'] = $inventors;
    }
    if(!empty($inventions)){
        $data['inventions'] = $inventions;
    }
    if(empty($data)){
        http_response_code(404);
    }else{
        echo json_encode($data);
    }
}