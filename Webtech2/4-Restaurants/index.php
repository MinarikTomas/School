<?php
//update if data are older than 5hours
require_once 'prifuk.php';
require_once 'eat.php';
require_once 'fiit.php';

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Zadanie 4</title>
</head>
<body>
<header>
    <h1>Zadanie 4</h1>
</header>
<div>
    <label for="display">Zobraziť: </label>
    <select id="display"></select>
</div>
<div class="day">
    <h2 class="date"></h2>
    <table class="restaurant-1"></table>
    <table class="restaurant-2"></table>
    <table class="restaurant-3"></table>
</div>
<div class="day">
    <h2 class="date"></h2>
    <table class="restaurant-1"></table>
    <table class="restaurant-2"></table>
    <table class="restaurant-3"></table>
</div>
<div class="day">
    <h2 class="date"></h2>
    <table class="restaurant-1"></table>
    <table class="restaurant-2"></table>
    <table class="restaurant-3"></table>
</div>
<div class="day">
    <h2 class="date"></h2>
    <table class="restaurant-1"></table>
    <table class="restaurant-2"></table>
    <table class="restaurant-3"></table>
</div>
<div class="day">
    <h2 class="date"></h2>
    <table class="restaurant-1"></table>
    <table class="restaurant-2"></table>
    <table class="restaurant-3"></table>
</div>
<div class="day">
    <h2 class="date"></h2>
    <table class="restaurant-1"></table>
    <table class="restaurant-2"></table>
    <table class="restaurant-3"></table>
</div>
<div class="day">
    <h2 class="date"></h2>
    <table class="restaurant-1"></table>
    <table class="restaurant-2"></table>
    <table class="restaurant-3"></table>
</div>

<script>

    let select = document.querySelector('#display');
    let restaurant1Tables = document.querySelectorAll('.restaurant-1');
    let restaurant2Tables = document.querySelectorAll('.restaurant-2');
    let restaurant3Tables = document.querySelectorAll('.restaurant-3');
    let dayDivs = document.querySelectorAll('.day');
    let dateHeadings = document.querySelectorAll('.date');


    fetch('storage/restaurant3.json')
        .then(response => response.json())
        .then(data => {
            setupSelect(data);
            setupHeadings(data.data);
            setupTables(restaurant3Tables, data);
        });

    const setupHeadings = (data) => {
        for (let i = 0; i < dayDivs.length; i++){
            dateHeadings[i].innerHTML = data[i].date + ' ' + data[i].day;
        }
    }

    const setupSelect = (data) => {
        select.innerHTML = '<option>Celý týždeň</option>'
        addDaysToSelect(data);
    }

    const addDaysToSelect = (data) => {
        data.data.forEach(addOption);
    }

    const addOption = (item) => {
        const option = document.createElement('option');
        option.text =item.date + ' ' + item.day;
        select.add(option);
    }

    fetch('storage/restaurant2.json')
        .then(response => response.json())
        .then(data => {
            setupTables(restaurant2Tables, data);
        });

    fetch('storage/restaurant1.json')
        .then(response => response.json())
        .then(data => {
            setupTables(restaurant1Tables, data);
        });

    const setupTables = (tables, data) => {
        for (let i = 0; i < 7; i++){
            if(data.data[i].menu.length > 0){
                addName(data.name, tables[i]);
                addMenu(data.data[i].menu, tables[i]);
            }
        }
    }

    const addName = (name, table) => {
        const row = table.insertRow();
        const cell = document.createElement('th');
        cell.innerText = name;
        row.appendChild(cell);
    }

    const addMenu = (data, table) => {
        for(let i = 0; i < data.length; i++){
            const row = table.insertRow();
            const cell = row.insertCell();
            cell.innerText = data[i];
        }
    }

    select.addEventListener('change', () => {
        if(select.selectedIndex === 0){
            showAllDays();
        }else{
            hideAllDays();
            dayDivs[select.selectedIndex - 1].style.display = 'block';
        }
    })

    const hideAllDays = () => {
        for (let i = 0; i < dayDivs.length; i++){
            dayDivs[i].style.display = 'none';
        }
    }

    const showAllDays = () => {
        for (let i = 0; i < dayDivs.length; i++){
            dayDivs[i].style.display = 'block';
        }
    }

</script>
</body>
</html>