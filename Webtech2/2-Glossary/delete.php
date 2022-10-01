<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config.php';
require_once 'MyPdo.php';
/**
 * @var $host
 * @var $db
 * @var $user
 * @var $password
 */
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data)){
    try{
        $dsn = "mysql:host=$host;dbname=$db";
        $myPdo = new MyPDO($dsn, $user, $password);


        $myPdo->run("DELETE FROM words WHERE id = ?", [$data['id']]);

        $results = ["deleted" => true, "message" => "Deleted successfully"];
        echo json_encode($results);
    }catch(PDOException $e){
        $results = ["deleted" => false, "message" => $e->getMessage()];
        echo json_encode($results);
    }
}