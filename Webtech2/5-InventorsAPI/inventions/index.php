<?php
require_once '../Inventor.php';
require_once '../Invention.php';
header('Content-Type: application/json; charset=utf-8');

switch($_SERVER['REQUEST_METHOD']){
    case "POST":
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['inventor_id']) and isset($data['year']) and isset($data['description'])){
            http_response_code(201);
            $invention = new Invention();
            $invention->setInventorId($data['inventor_id']);
            if(!empty($data['year'])) {
                $invention->setInventionDate($data['year'] . '-01-01');
            }
            $invention->setDescription($data['description']);
            try{
                $invention->save();
            }catch(PDOException $e){
                http_response_code(400);
            }
            echo json_encode($invention->toArray());
        }else{
            http_response_code(400);
        }
        break;
    case "GET":
        $century = $_GET['century'];
        http_response_code(200);
        if($century){
            $data = Invention::findByCentury($century);
            if($data){
                echo json_encode($data);
            }
        }else{
            echo json_encode(Invention::all());
        }
        break;
}


