<?php
require_once 'vendor/autoload.php';
$configs = include ('config.php');

$client = new Google\Client();
$client->setAuthConfig($configs->jsonPath);
$redirect_uri = '***';
$client->addScope("email");
$client->addScope("profile");
$client->setRedirectUri($redirect_uri);

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Prihlásenie</title>
</head>
<body>
    <header>
        <h1>Zadanie 3</h1>
    </header>
    <div class="container">
        <form action="login.php" method="post">
            <div>
                <label for="email">Email:</label>
                <input id="email" name="email" type="email">
            </div>
            <div>
                <label for="password">Heslo:</label>
                <input id="password" name="password" type="password">
            </div>
            <div class="container">
                <input type="submit" value="Prihlásiť sa">
            </div>
        </form>
    </div>
    <div class="container">
        <span>Nemáš ešte účet? <a href="register.php">Zaregistruj sa</a></span>
    </div>
    <div class="container">
        <?php echo "<a href='".$client->createAuthUrl()."'>Prihlásenie sa pomocou google.</a>"?>
    </div>
</body>
</html>