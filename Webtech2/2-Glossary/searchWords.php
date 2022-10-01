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

$dsn = "mysql:host=$host;dbname=$db";
$myPdo = new MyPDO($dsn, $user, $password);

if(isset($_GET['search'])){
    $search = "%".$_GET['search']."%";
    try{
        $results['data'] = $myPdo->run("SELECT * FROM words WHERE title Like ?", [$search])->fetchAll();
        $results['success'] = true;
        echo json_encode($results);
    }catch(PDOException $e){
        $results['success'] = false;
        $results['message'] = $e->getMessage();
        echo json_encode($results);
    }
}