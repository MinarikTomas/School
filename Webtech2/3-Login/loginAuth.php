<?php

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
    <div class="container">
        <span>Zadajte kód z aplikácie</span>
        <input type="text" name="code" id="code">
        <input type="submit" id="submit-code" value="Overiť">
    </div>
    <div class="container">
        <span id="failed"></span>
    </div>
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
