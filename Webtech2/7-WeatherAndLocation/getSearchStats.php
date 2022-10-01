<?php

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

$stmt = $pdo->prepare('SELECT country, COUNT(searches.id) as count FROM locations JOIN searches ON locations.id = searches.location_id GROUP BY country');
$stmt->execute();
$results = $stmt->fetchAll();
echo json_encode($results);