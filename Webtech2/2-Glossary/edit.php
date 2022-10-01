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

if (isset($data)){
    try{
        $dsn = "mysql:host=$host;dbname=$db";
        $myPdo = new MyPDO($dsn, $user, $password);


        $myPdo->run("UPDATE words SET title = ? WHERE id = ?", [$data['skWord'], $data['id']]);
        $myPdo->run("UPDATE translations SET title = ?, description = ? WHERE id = ?", [$data['skWord'], $data['skDesc'], $data['skId']]);
        $myPdo->run("UPDATE translations SET title = ?, description = ? WHERE id = ?", [$data['enWord'], $data['enDesc'], $data['enId']]);

        $results = ["updated" => true, "message" => "updated successfully"];
        echo json_encode($results);
    }catch(PDOException $e){
        $results = ["updated" => false, "message" => $e->getMessage()];
        echo json_encode($results);
    }
}