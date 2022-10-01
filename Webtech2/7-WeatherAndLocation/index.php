
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Zadanie 7</title>
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
<form id="form">
    <label for="address">Adresa:</label>
    <input id="address" type="text" name="address" placeholder="Nitrianska 11,  Šurany">
    <input type="button" value="submit" id="submit">
</form>
<div>
    <p id="capital-country"></p>
    <p id="coordinates"></p>
</div>
<div id="weather-container">
    <p id="location" class="weather"></p>
    <p id="date" class="weather"></p>
    <div id="icon-container">
        <img id="icon">
    </div>
    <p id="text" class="weather"></p>
    <p id="temp" class="weather"></p>
</div>

<script>
    const btn = document.querySelector('#submit');
    const input = document.querySelector('#address');
    const loc = document.querySelector('#location');
    const date = document.querySelector('#date');
    const icon = document.querySelector('#icon');
    const text = document.querySelector('#text');
    const temp = document.querySelector('#temp');
    const capital = document.querySelector('#capital-country');
    const coordinates = document.querySelector('#coordinates');

    btn.addEventListener('click', () => {
        fetch('getData.php?address='+input.value,
            {method: 'get'}
        ).then(response => response.json())
            .then(result => {
                clear();
                capital.textContent = result.location.capital + ', ' + result.location.country;
                coordinates.textContent = result.location.latitude + ', ' + result.location.longitude;
                displayWeather(result);

            })
    })

    const clear = () => {
        capital.textContent = '';
        coordinates.textContent = '';
        loc.textContent = '';
        date.textContent = '';
        icon.src = '';
        icon.alt = '';
        text.textContent = '';
    }

    const displayWeather = (data) => {
        loc.textContent = data.location.name + ', ' + data.location.region;
        date.textContent = data.weather.date;
        icon.src = data.weather.img;
        icon.alt = 'weather-icon';
        text.textContent = data.weather.text;
        temp.textContent = data.weather.max + '°/ ' + data.weather.min + '°';
    }
</script>

</body>
</html>
