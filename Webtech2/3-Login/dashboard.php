<?php
session_start();
if(!isset($_SESSION['name'])){
    header("Location: index.php");
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
    <title>Dashboard</title>
</head>
<body>
<header>
    <h1>Zadanie 3</h1>
</header>
<div class="container">
    Ste prihlásený ako <?php echo $_SESSION['name'];?>. <a href="logout.php">Odhlásiť sa</a>
</div>
<div class="container">
    <button onclick="location.href='stats.php'">Minulé prihlásenia</button>
</div>

</body>
</html>
