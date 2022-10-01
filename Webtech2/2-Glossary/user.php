<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Glosar user</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Admin</a></li>
            <li><a href="user.php">User</a></li>
        </ul>
    </nav>
</header>
<form id="search-form">
    <div>
        <label for="search">Zadajte slovo:</label>
        <input id="search" name="search" type="text">
    </div>
    <div>
        <label for="language">Vyberte jazyk:</label>
        <select name="language_code" id="language">
            <option value="sk">Slovenčina</option>
            <option value="en">English</option>
        </select>
    </div>
    <div>
        <p>Preložiť výraz:</p>
        <input type="radio" id="yes-checkbox" name="translate" checked>
        <label for="yes-checkbox">Áno</label><br>
        <input type="radio" id="no-checkbox" name="translate">
        <label for="no-checkbox">Nie</label>
    </div>
    <div>
        <p>Fulltext vyhľadávanie:</p>
        <input type="radio" id="yes-checkbox-full" name="fulltext" value="true">
        <label for="yes-checkbox-full">Áno</label><br>
        <input type="radio" id="no-checkbox-full" name="fulltext" value="false" checked>
        <label for="no-checkbox-full">Nie</label>
    </div>
    <button id="search-button" type="button">Vyhladaj</button>
</form>

<table id="result-table">
    <thead>
        <tr>
            <th>Hľadaný výraz</th>
            <th>Popis</th>
            <th>Preklad</th>
            <th>Popis</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<span id="translate-error"></span>

<script>
    const form = document.querySelector("#search-form");
    const button = document.querySelector("#search-button");
    const table = document.querySelector("#result-table");
    const tbody = table.tBodies[0];
    const translateCheckbox = document.querySelector("#yes-checkbox");

    button.addEventListener('click', () => {
        const data  = new FormData(form);
        tbody.innerHTML = "";
        fetch("translate.php?search="+data.get('search')+"&language_code="+data.get('language_code')+"&fulltext="+data.get('fulltext'),
            {method: "get"}
        ).then(response => response.json())
        .then(result => {
            if(result.success){
                document.querySelector("#translate-error").innerHTML = "";
                result.data.forEach(item => {
                    const row = tbody.insertRow();
                    if(translateCheckbox.checked){
                        addDataToRow(row, [item.searchTitle, item.searchDescription, item.translatedTitle, item.translatedDescription]);
                    }else{
                        addDataToRow(row, [item.searchTitle, item.searchDescription, "", ""]);
                    }
                })
            }else{
                document.querySelector("#translate-error").innerHTML = result.message;
            }

        })
    })

    const addDataToRow = (row, data) => {
        for(let i = 0; i < data.length; i++){
            const cell = row.insertCell();
            cell.innerText = data[i];
        }

    }
</script>
</body>
</html>
