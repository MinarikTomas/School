<?php

if(isset($_GET['country'])) {
    $host = '***';
    $db = '***';
    $user = '***';
    $pass = '***';

    $dsn = "mysql:host=$host;dbname=$db";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);

    $stmt = $pdo->prepare('SELECT locations.query, COUNT(locations.id) AS count FROM locations 
    JOIN searches ON locations.id = searches.location_id WHERE locations.country = :country
    GROUP BY locations.query');
    $stmt->execute(['country' => $_GET['country']]);
    echo json_encode($stmt->fetchAll());
}