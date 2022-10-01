<?php
header('Content-Type: application/json; charset=utf-8');

if(isset($_GET['address'])){
    $host = '***';
    $db   = '***';
    $user = '***';
    $pass = '***';

    $dsn = "mysql:host=$host;dbname=$db";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

    $url = "***getLocation.php?address=" . urlencode($_GET['address']);

    $locationData = json_decode(file_get_contents($url));
    $coordinates = $locationData->latitude . ',' . $locationData->longitude;
    $url = '***getWeather.php?coordinates=' . $coordinates;
    $weatherData = json_decode(file_get_contents($url));
    $data = array(
        'location' => $locationData,
        'weather' => $weatherData
    );
    save($pdo, $data['location'], $data['weather']->time);
    echo json_encode($data);
}
function save($pdo, $data, $time){
    $id = findLocation($pdo, $data->latitude, $data->longitude);
    $ip = getIPAddress();
    if(empty($id)){
        $stmt = $pdo->prepare('INSERT INTO `locations`(`query`, `country`, `latitude`, `longitude`) VALUES (?, ?, ?, ?)');
        $stmt->execute([$data->query, $data->country, $data->latitude, $data->longitude]);
        $id = $pdo->lastInsertId();
    }else{
        $id = $id['id'];
    }
    $stmt = $pdo->prepare('INSERT INTO `searches`(`ip`, `time`, `location_id`) VALUES (?, ?, ?)');
    $stmt->execute([$ip, $time, $id]);
}

function findLocation($pdo, $lat, $long){
    $stmt = $pdo->prepare('SELECT id FROM locations WHERE `latitude` = :lat AND `longitude` = :long');
    $stmt->execute(['lat' => $lat, 'long' => $long]);
    return $stmt->fetch();
}

//https://www.javatpoint.com/how-to-get-the-ip-address-in-php
function getIPAddress()
{
    //whether ip is from the share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } //whether ip is from the proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } //whether ip is from the remote address
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}