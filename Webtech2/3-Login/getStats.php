<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}
header('Content-Type: application/json; charset=utf-8');

$configs = include ('config.php');

try{
    $conn = new PDO("mysql:host=$configs->host;dbname=$configs->db", $configs->user, $configs->password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT logins.created_at, accounts.type FROM accounts 
                                    JOIN logins ON accounts.id = logins.account_id WHERE accounts.user_id = :id");
    $stmt->bindParam(":id", $_SESSION['user_id']);
    $stmt->execute();
    $results['last_login'] = $stmt->fetchAll();

    $stmt = $conn->prepare("SELECT COUNT(accounts.type) AS google_count FROM accounts JOIN logins ON accounts.id = logins.account_id WHERE accounts.type = 'google';");
    $stmt->execute();
    $results['google'] = $stmt->fetch()['google_count'];

    $stmt = $conn->prepare("SELECT COUNT(accounts.type) AS classic_count FROM accounts JOIN logins ON accounts.id = logins.account_id WHERE accounts.type = 'classic';");
    $stmt->execute();
    $results['classic'] = $stmt->fetch()['classic_count'];
    $results['success'] = true;
    echo json_encode($results);
}catch (PDOException $e){
    $results['success'] = false;
    $results['message'] = $e->getMessage();
    echo json_encode($results);
}
