<?php
header('Content-Type: application/json; charset=utf-8');

if(isset($_GET['address'])){
    $address = $_GET['address'];
    $data = array(
        'access_key' => '***',
        'query' => $address,
        'country_module' => 1
    );

    $query = http_build_query($data, '', '&');
    $url = 'http://api.positionstack.com/v1/forward?' . $query;
    $response = json_decode(file_get_contents($url));
    foreach ($response->data as $item){
        if($item->type == 'locality' or $item->type == 'address'){
            $response = $item;
            break;
        }
    }
    $data = array(
        'query' => $address,
        'latitude' => $response->latitude,
        'longitude' => $response->longitude,
        'country' => $response->country,
        'capital' => $response->country_module->capital,
        'name' => (!empty($response->locality)) ? $response->locality : $response->name,
        'region' => $response->region
    );
    echo json_encode($data);
//    print_r($response);
}
