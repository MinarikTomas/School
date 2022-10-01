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
    <title>Minulé prihlásenia</title>
</head>
<body>
<header>
    <h1>Zadanie 3</h1>
</header>
<div class="container">
    Ste prihlásený ako <?php echo $_SESSION['name'];?>. <a href="logout.php">Odhlásiť sa</a>
</div>
<div class="container">
    <button onclick="location.href='dashboard.php'">Domov</button>
</div>
<div class="container">
    <table id="last-login-table">
        <caption>Posledné prihlásenia používateľa <?php echo $_SESSION['name'];?></caption>
        <thead>
        <tr>
            <th>Dátum a čas</th>
            <th>Typ</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<div class="container">
    <table id="type-table">
        <caption>Štatistika prihlásení všetkých používateľov</caption>
        <thead>
        <tr>
            <th>Typ</th>
            <th>Počet prihlásení</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>


<script>
    const lastTable = document.querySelector("#last-login-table").tBodies[0];
    const typeTable = document.querySelector("#type-table").tBodies[0];
    fetch("getStats.php")
        .then(response => response.json())
        .then(result => {
            if(result.success){
                result.last_login.forEach(item => {
                    const row = lastTable.insertRow();
                    addDataToRow([item[0], item[1]], row);
                })
                let row = typeTable.insertRow();
                addDataToRow(["classic", result.classic], row);
                row = typeTable.insertRow();
                addDataToRow(['google', result.google], row);
            }
        })


    const addDataToRow = (data, row) => {
        for(let i = 0; i < data.length; i++){
            const cell = row.insertCell();
            cell.innerText = data[i];
        }
    }
</script>
</body>
</html>
