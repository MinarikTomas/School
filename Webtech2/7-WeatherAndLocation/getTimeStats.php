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

$intervals = [[0, 5], [6, 14], [15, 20], [21, 23]];
$results = [];
foreach ($intervals as $interval){
    $stmt = $pdo->prepare('SELECT COUNT(id) as count FROM `searches` WHERE HOUR(time) BETWEEN :start and :end');
    $stmt->execute(['start' => $interval[0], 'end' => $interval[1]]);
    array_push($results, $stmt->fetch()['count']);
}
echo json_encode($results);
