<?php
session_start();
if(!isset($_SESSION['acc_id'])){
    header("Location: index.php");
    exit;
}
require_once 'GoogleAuthenticator-master/PHPGangsta/GoogleAuthenticator.php';
$configs = include ('config.php');
try{
    $conn = new PDO("mysql:host=$configs->host;dbname=$configs->db", $configs->user, $configs->password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT auth_secret FROM accounts WHERE id = :id");
    $stmt->bindParam(":id", $_SESSION['acc_id'], PDO::PARAM_INT);
    $stmt->execute();
    $secret = $stmt->fetch()['auth_secret'];
}catch (PDOException $e){
    echo $e->getMessage();
    exit;
}

if(isset($_POST['code'])){
    $code = $_POST['code'];

    $ga = new PHPGangsta_GoogleAuthenticator();
    $result = $ga->verifyCode($secret, $code);
    if($result == 1){
        try {
            $conn = new PDO("mysql:host=$configs->host;dbname=$configs->db", $configs->user, $configs->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("INSERT INTO logins (account_id) VALUES (".$_SESSION['acc_id'].")");
            $stmt->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        echo $result;
    }else{
        echo 'Login failed';
    }

}
