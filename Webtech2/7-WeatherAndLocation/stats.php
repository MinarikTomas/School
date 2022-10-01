<?php
$url = '****';
$timeSats = json_decode(file_get_contents($url));
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
          integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
            integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
            crossorigin=""></script>
    <title>Štatistika</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Domov</a></li>
            <li><a href="stats.php">Štatistika</a></li>
        </ul>
    </nav>
</header>
<div id="table-container">
    <div>
        <table id="search-stats">
            <thead>
            <tr>
                <th>Vlajka</th>
                <th>Krajina</th>
                <th>Počet</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div>
        <table>
            <thead>
            <tr>
                <th>Čas</th>
                <th>Počet</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>00:00-06:00</td>
                <td><?php echo $timeSats[0]?></td>
            </tr>
            <tr>
                <td>06:00-15:00</td>
                <td><?php echo $timeSats[1]?></td>
            </tr>
            <tr>
                <td>15:00-21:00</td>
                <td><?php echo $timeSats[2]?></td>
            </tr>
            <tr>
                <td>21:00-24:00</td>
                <td><?php echo $timeSats[3]?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="country-stats-container">
        <table id="country-stats">
            <thead>
            <tr>
                <th>Miesto</th>
                <th>Počet</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div id="map">

</div>
<script>
const searchTbody = document.querySelector('#search-stats').tBodies[0];
const countryTbody = document.querySelector('#country-stats').tBodies[0];
const countryStatsContainer = document.querySelector('#country-stats-container');

fetch('getSearchStats.php')
    .then(response => response.json())
    .then(result => {
        for(let i = 0; i < result.length; i++){
            const row = searchTbody.insertRow();
            addDataToSearchStatsTableRow(row, result[i]);
            row.addEventListener('click', () => {
                displayCountryStatsTable(result[i].country);
            })
        }
    })

const addDataToSearchStatsTableRow = (row, data) => {
    let cell = row.insertCell();
    addFlagToCell(cell, data.country);
    cell = row.insertCell();
    cell.innerText = data.country;
    cell = row.insertCell();
    cell.innerText = data.count;
}

const addFlagToCell = (cell, country) => {
    const flag = document.createElement('img');
    flag.alt = country;
    flag.src = 'https://countryflagsapi.com/png/' + country;
    cell.appendChild(flag);
}

const displayCountryStatsTable = (country) => {
    if(countryTbody.getAttribute('country') === country && countryStatsContainer.style.display === 'block'){
        countryStatsContainer.style.display = 'none';
    }else {
        countryTbody.innerHTML = '';
        addDataToCountryStatsTable(country);
        countryTbody.setAttribute('country', country);
        countryStatsContainer.style.display  = 'block';
    }
}

const addDataToCountryStatsTable = (country) => {
    console.log(country);
    fetch('getCountryStats.php?country=' + country)
        .then(response => response.json())
        .then(result => {
            addRowsToCountryTable(result)
        })
}

const addRowsToCountryTable = (data) => {
    for (let i = 0; i < data.length; i++){
        const row = countryTbody.insertRow();
        let cell = row.insertCell();
        cell.innerText = data[i].query;
        cell = row.insertCell();
        cell.innerText = data[i].count;
    }
}

const gps = {lon: 17.073, lat: 48.153};
var map = L.map('map').setView(gps, 2);

// add the OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
}).addTo(map);

// show the scale bar on the lower left corner
L.control.scale({imperial: true, metric: true}).addTo(map);

fetch('getAllLocations.php')
    .then(response => response.json())
    .then(result => {
        result.forEach(location => {
            L.marker([location.latitude, location.longitude]).addTo(map);
        })
    })
</script>
</body>
</html>
