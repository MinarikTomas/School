<?php
require_once 'vendor/autoload.php';
$configs = include ('config.php');


$client = new Google\Client();
$client->setAuthConfig($configs->jsonPath);
if(isset($_GET['code'])){
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token["access_token"]);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $id = $google_account_info->getId();

    try{
        $conn = new PDO("mysql:host=$configs->host;dbname=$configs->db", $configs->user, $configs->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT id, user_id FROM `accounts` WHERE google_id = :google_id");
        $stmt->bindParam(":google_id", $id);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $account_id = $stmt->fetch();
        $user_id = $account_id['user_id'];
        if($account_id != false){
            $account_id = $account_id['id'];
            $stmt = $conn->prepare("INSERT INTO logins (account_id) VALUES (".$account_id.")");
            $stmt->execute();
        }else{
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user_id = $stmt->fetch();
            if($user_id != false){
                $user_id = $user_id['id'];
                $stmt = $conn->prepare("INSERT INTO accounts (user_id, type, google_id) VALUES (".$user_id.", 'google', '".$id."')");
                $stmt->execute();
                $account_id = $conn->lastInsertId();

                $stmt = $conn->prepare("INSERT INTO logins (account_id) VALUES (".$account_id.")");
                $stmt->execute();
            }else{
                $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
                $stmt->bindParam(":name", $name);
                $stmt->bindParam(":email", $email);
                $stmt->execute();

                $user_id = $conn->lastInsertId();

                $stmt = $conn->prepare("INSERT INTO accounts (user_id, type, google_id) VALUES (".$user_id.",'google', '".$id."')");
                $stmt->execute();
                $account_id = $conn->lastInsertId();

                $stmt = $conn->prepare("INSERT INTO logins (account_id) VALUES (".$account_id.")");
                $stmt->execute();
            }
        }
        session_start();
        $_SESSION['name'] = $name;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['acc_id'] = $account_id;
        header("Location: dashboard.php");
    }catch (PDOException $e){
        echo '<br>' . $e->getMessage();
    }
}