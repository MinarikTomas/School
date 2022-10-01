<?php
require_once '../Inventor.php';
header('Content-Type: application/json; charset=utf-8');


switch ($_SERVER['REQUEST_METHOD']){
    case "POST":
        $data = json_decode(file_get_contents('php://input'), true);

        if(!empty($data['name']) and !empty($data['surname']) and !empty($data['birth_date']) and !empty($data['birth_place'])
            and isset($data['description'])){
            $inventor = new Inventor();
            $inventor->setName($data['name']);
            $inventor->setSurname($data['surname']);
            $inventor->setBirthDate($data['birth_date']);
            $inventor->setBirthPlace($data['birth_place']);
            $inventor->setDescription($data['description']);
            if(!empty($data["death_date"])){
                $inventor->setDeathDate($data['death_date']);
            }
            if(!empty($data['death_place'])){
                $inventor->setDeathPlace($data['death_place']);
            }
            try {
                $inventor->save();

            }catch(PDOException $e){
                http_response_code(400);
            }
            $inventorId = MyPDO::instance()->lastInsertId();
            foreach ($data['inventions'] as $item){
                $invention = new Invention();
                $invention->setInventorId($inventorId);
                $invention->setDescription($item['description']);
                if(!empty($item['year'])){
                    $invention->setInventionDate($item['year'].'-01-01');
                }
                $invention->save();
                $inventor->addInvention($invention);
            }
            http_response_code(201);
            echo json_encode($inventor->toArray());
        }else{
            http_response_code(400);
        }
        break;
    case "DELETE":
        $id = $_GET['id'];
        $inventor = Inventor::find($id);
        if($inventor){
            $inventor->destroy();
            http_response_code(204);
        }else{
            http_response_code(404);
        }
        break;
    case "GET":
        http_response_code(200);
        $id = $_GET['id'];
        $surname = $_GET['surname'];
        if($id){
            $data = Inventor::find($id);
            if ($data){
                echo json_encode($data->toArray());
            }
        }elseif ($surname){
            $data = Inventor::findBySurname($surname);
            if($data){
                echo json_encode($data);
            }
        }else{
            echo json_encode(Inventor::all());
        }
        break;
    case "PUT":
        $id = $_GET['id'];
        $data = json_decode(file_get_contents('php://input'), true);
        if($id){
            http_response_code(200);
            $inventor = Inventor::update($id, $data);
            if($inventor){
                echo json_encode($inventor->toArray());
            }
        }
        break;
}
