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
        if(isset($_GET['fulltext']) and strcmp($_GET['fulltext'], "true") == 0){
            $sql = "SELECT t1.word_id as id, t1.title as searchTitle, t1.description as searchDescription,
                                t2.title as translatedTitle, t2.description as translatedDescription
                                FROM translations t1
                                JOIN translations t2 on t1.word_id = t2.word_id
                                JOIN languages ON t1.language_id = languages.id 
                                WHERE languages.code = ? AND (t1.title LIKE ? OR t1.description LIKE ?) AND t1.id <> t2.id";
            $args = [$_GET['language_code'], $search, $search];
        }else{
            $sql = "SELECT t1.word_id as id, t1.title as searchTitle, t1.description as searchDescription,
                                t2.title as translatedTitle, t2.description as translatedDescription, t1.id as t1Id, t2.id as t2Id
                                FROM translations t1
                                JOIN translations t2 on t1.word_id = t2.word_id
                                JOIN languages ON t1.language_id = languages.id 
                                WHERE languages.code = ? AND t1.title LIKE ? AND t1.id <> t2.id";
            $args = [$_GET['language_code'], $search];
        }
        $results['data'] = $myPdo->run($sql, $args)->fetchAll();
        $results['success'] = true;
        echo json_encode($results);
    }catch (PDOException $e){
        $results['success'] = false;
        $results['message'] = $e->getMessage();
        echo json_encode($results);
    }
}
