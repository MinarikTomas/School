<?php
header('Content-Type: application/json; charset=utf-8');
if(isset($_GET['coordinates'])){
    $data = array(
        'key' => '***',
        'q' => $_GET['coordinates'],
        'lang' => 'sk'
    );
    $query = http_build_query($data, '', '&');
    $url = 'https://api.weatherapi.com/v1/forecast.json?' . $query;
    $response = json_decode(file_get_contents($url));
    $data = array(
        'date' => $response->forecast->forecastday[0]->date,
        'min' => $response->forecast->forecastday[0]->day->mintemp_c,
        'max' => $response->forecast->forecastday[0]->day->maxtemp_c,
        'text' => $response->forecast->forecastday[0]->day->condition->text,
        'img' => $response->forecast->forecastday[0]->day->condition->icon,
        'time' => $response->location->localtime
    );
    echo json_encode($data);
}
