<?php
require_once 'GoogleAuthenticator-master/PHPGangsta/GoogleAuthenticator.php';

session_start();
if(!isset($_SESSION['acc_id'])){
    header("Location: register.php");
}
$configs = include ('config.php');

$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();
$qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);

try{
    $conn = new PDO("mysql:host=$configs->host;dbname=$configs->db", $configs->user, $configs->password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE accounts SET auth_secret = :secret WHERE id = :id");
    $stmt->bindParam(":secret", $secret);
    $stmt->bindParam(":id", $_SESSION['acc_id'], PDO::PARAM_INT);
    $stmt->execute();
}catch(PDOException $e){
    echo '<br>' . $e->getMessage();
}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="style.css">
    <title>Overenie</title>
</head>
<body>
<header>
    <h1>Zadanie 3</h1>
</header>
<div class="container">
    <span>Stiahnite si aplikáciu google authenticator a naskenujte qr kód alebo zadajte secret.</span>
</div>
<div class="container">
    <?php echo "<img src='$qrCodeUrl'>";?>
</div>
<div class="container">
    <span><?php echo "Secret is: ".$secret."";?></span>
</div>
<div class="container">
    <span>Zadajte kód z aplikácie</span>
    <input type="text" name="code" id="code">
    <input type="submit" id="submit-code" value="Overiť">
</div>
<div class="container">
    <span id="failed"></span>
</div>

<script>

    $('input#submit-code').on('click', function () {
        const code = $('input#code').val();
        if($.trim(code) != ''){
            $.post('check.php', {code: code}, function (data){
                if(data == 1){
                    window.location.replace('dashboard.php');
                }else{
                    $('span#failed').text('Overenie zlyhalo');
                }
            })
        }
    })
</script>
</body>
</html>
