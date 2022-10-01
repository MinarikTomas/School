<?php
?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Glosar admin</title>
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
<div class="row">
    <h1>Nahrať csv</h1>
    <form action="csvUpload.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="file">Súbor:</label>
            <input id="file" name="file" type="file">
        </div>
        <div>
            <input type="submit" value="upload">
        </div>
    </form>
</div>
<div class="row">
    <h1>Vymazať záznam</h1>
    <form id="search-form">
        <div>
            <label for="search">Zadajte slovo:</label>
            <input id="search" name="search" type="text">
        </div>
        <button id="search-button" type="button">Vyhladaj</button>
    </form>

    <table id="result-table">
        <thead>
        <tr>
            <th>Hľadaný výraz</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
    <span id="delete-error"></span>
    <span id="search-error"></span>
</div>
<div class="row">
    <h1>Pridať záznam</h1>
    <form action="itemUpload.php" method="post" enctype="multipart/form-data">
        <table id="add-item-table">
            <thead>
                <tr>
                    <th>Výraz(Sk)</th>
                    <th>Popis(Sk)</th>
                    <th>Preklad(En)</th>
                    <th>Popis(En)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input class="add-item" id="word" type="text" name="word"></td>
                    <td><input class="add-item" id="desc" type="text" name="desc"></td>
                    <td><input class="add-item" id="translated-word" type="text" name="translated-word"></td>
                    <td><input class="add-item" id="translated-desc" type="text" name="translated-desc"></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" value="Pridať">
    </form>
</div>
<div class="row">
    <h1>Upraviť záznam</h1>
    <form id="search-form-edit">
        <div>
            <label for="search-edit">Zadajte slovo:</label>
            <input id="search-edit" name="search" type="text">
        </div>
        <button id="search-button-edit" type="button">Vyhladaj</button>
    </form>

    <table id="result-table-edit">
        <thead>
        <tr>
            <th>Hľadaný výraz(Sk)</th>
            <th>Popis(Sk)</th>
            <th>Preklad(En)</th>
            <th>Popis(En)</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
    <span id="translate-error"></span>
    <span id="update-error"></span>
</div>
<script>
    const form = document.querySelector("#search-form");
    const button = document.querySelector("#search-button");
    const table = document.querySelector("#result-table");
    const tbody = table.tBodies[0];

    button.addEventListener('click', () => {
        const data  = new FormData(form);
        tbody.innerHTML = "";
        fetch("searchWords.php?search="+data.get('search'),
            {method: "get"}
        ).then(response => response.json())
            .then(result => {
                if(result.success){
                    document.querySelector("#search-error").innerHTML = "";
                    result.data.forEach(item => {
                        createRow(item)
                    })
                }else{
                    document.querySelector("#search-error").innerHTML = result.message;
                }
            })
    })

    const createRow = (item) => {
        const row = tbody.insertRow();
        let cell = row.insertCell();
        cell.innerText = item.title;
        cell = row.insertCell();
        const delButton = document.createElement("button");
        delButton.innerText = "delete";
        delButton.addEventListener("click", () => {
            fetch("delete.php", {
                method: "POST",
                headers: {
                    'accept': 'application/json, text/plain, */*',
                    'content-type': 'application/json'
                },
                body: JSON.stringify({id: item.id})
            })
            .then(response => response.json())
            .then(result => {
                const err = document.querySelector("#delete-error");
                err.innerHTML = "";
                if(result.deleted){
                    row.remove();
                }else{
                    err.innerHTML = result.message;
                }
            })
        })
        cell.appendChild(delButton);
    }

    const editForm = document.querySelector("#search-form-edit");
    const searchButtonForEdit = document.querySelector("#search-button-edit");
    const editTable = document.querySelector("#result-table-edit");
    const editTbody = editTable.tBodies[0];

    searchButtonForEdit.addEventListener('click', () => {
        document.querySelector("#update-error").innerHTML = "";
        const data  = new FormData(editForm);
        editTbody.innerHTML = "";
        fetch("translate.php?search="+data.get('search')+"&language_code=sk",
            {method: "get"}
        ).then(response => response.json())
            .then(result => {
                console.log(result);
                if(result.success){
                    result.data.forEach(item => {
                        createRowForEditTable(item);
                    })

                }else{
                    document.querySelector("#translate-error").innerHTML = result.message;
                }

            })
    })

    const createRowForEditTable = (item) => {
        const row = editTbody.insertRow();
        addDataToRow(row, [item.searchTitle, item.searchDescription, item.translatedTitle, item.translatedDescription])
        const cell = row.insertCell();
        const editButton = document.createElement("button");
        editButton.innerText = "edit";
        editButton.addEventListener("click", (event) => {
            const row = event.path[2]; //0-button, 1-td, 2-tr
            const textarea = row.getElementsByTagName("textarea");
            const skWord = textarea[0].value;
            const skDesc = textarea[1].value;
            const enWord = textarea[2].value;
            const enDesc = textarea[3].value;
            fetch("edit.php", {
                method: "POST",
                headers: {
                    'accept': 'application/json, text/plain, */*',
                    'content-type': 'application/json'
                },
                body: JSON.stringify({id: item.id,
                                    skWord: skWord,
                                    skDesc: skDesc,
                                    skId: item.t1Id,
                                    enId: item.t2Id,
                                    enWord: enWord,
                                    enDesc: enDesc})
            })
            .then(response => response.json())
            .then(result => {
                const err = document.querySelector("#update-error");
                err.innerHTML = "";
                if(result.updated){
                    err.innerHTML = "Updated";
                }else{
                    err.innerHTML = result.message;
                }
            })
        })
        cell.appendChild(editButton);
    }

    const addDataToRow = (row, data) => {
        for(let i = 0; i < data.length; i++){
            const cell = row.insertCell();
            const text = document.createElement("textarea");
            text.value = data[i];
            text.classList.add("edit-item");
            text.rows = 4;
            cell.appendChild(text);
        }
    }
</script>
</body>
</html>
