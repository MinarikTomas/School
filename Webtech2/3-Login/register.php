<?php
$configs = include ('config.php');

function startSession($conn, $acc_id, $user_id){
    session_start();
    $_SESSION['acc_id'] = $acc_id;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['name'] = $_POST['name'];
    header("Location: registerAuth.php");
}

if(isset($_POST['name'])){
    try{
        $conn = new PDO("mysql:host=$configs->host;dbname=$configs->db", $configs->user, $configs->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(":email", $_POST['email']);
        $stmt->execute();
        $user_id = $stmt->fetch();
        if($user_id != false){
            $stmt = $conn->prepare("SELECT id FROM accounts WHERE user_id = :user_id AND `type` = 'classic'");
            $stmt->bindParam(":user_id", $user_id['id']);
            $stmt->execute();
            if($stmt->fetch() != false){
                echo "Email je už použitý.";
            }else{
                $stmt = $conn->prepare("INSERT INTO accounts (user_id, type, password) VALUES (".$user_id['id'].",'classic', :password)");
                $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->execute();
                startSession($conn, $conn->lastInsertId(), $user_id['id']);
            }
        }else{
            $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stmt->bindParam(":name", $_POST['name']);
            $stmt->bindParam(":email", $_POST['email']);
            $stmt->execute();

            $user_id = $conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO accounts (user_id, type, password) VALUES (".$conn->lastInsertId().",'classic', :password)");
            $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(":password", $passwordHash);
            $stmt->execute();
            startSession($conn, $conn->lastInsertId(), $user_id);
        }


    }catch (PDOException $e){
        echo "<br>" . $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Registrácia</title>
</head>
<body>
<header>
    <h1>Zadanie 3</h1>
</header>
<div class="container">
    <form action="register.php" method="post">
        <div>
            <label for="name">Meno:</label>
            <input id="name" name="name" type="text">
        </div>
        <div>
            <label for="email">Email:</label>
            <input id="email" name="email" type="email">
        </div>
        <div>
            <label for="password">Heslo:</label>
            <input id="password" name="password" type="password">
        </div>
        <div class="container">
            <input type="submit" value="Zaregistruj sa">
        </div>
    </form>
</div>
<div class="container">
    <span>Máš už účet? <a href="index.php">Prihlás sa</a></span>
</div>


</body>
</html>