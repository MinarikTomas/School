<?php
$configs = include ('config.php');

if(isset($_POST['email'])) {
    try {
        $conn = new PDO("mysql:host=$configs->host;dbname=$configs->db", $configs->user, $configs->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT `name`, password, user_id, accounts.id AS id FROM users JOIN accounts ON users.id = accounts.user_id WHERE email = :email AND `type` = 'classic'");
        $stmt->bindParam(":email", $_POST['email']);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();
        $account_id = $user['id'];

        if (password_verify($_POST['password'], $user['password'])) {
            session_start();
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['acc_id'] = $account_id;
            header("Location: loginAuth.php");
        }else{
            header("Location: index.php");
        }
    } catch (PDOException $e) {
        echo '<br>' . $e->getMessage();
    }
}