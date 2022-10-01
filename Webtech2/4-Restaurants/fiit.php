<?php
require_once 'functions.php';

if(file_exists('./storage/restaurant3.json')){
    $data = getDataFromJson('./storage/restaurant3.json');
    $foods = $data['foods'];
    if($data['timeDiff'] > 18000) {
        $foods = getDataFiit();
    }
}else{
    $foods = getDataFiit();
}

function getDataFiit() :array{
    $ch = curl_init();

// set ur
    curl_setopt($ch, CURLOPT_URL, "http://www.freefood.sk/menu/#fiit-food");

//return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
    $output = curl_exec($ch);

// close curl resource to free up system resources
    curl_close($ch);

    $dom = new DOMDocument();

    @$dom->loadHTML($output);
    $dom->preserveWhiteSpace = false;

    $foods = initialArraySetup();

    $node = $dom->getElementById('fiit-food')->getElementsByTagName('li');
    $staticMenu = [];
    for($i = 25; $i <= 28; $i++){
        array_push($staticMenu, $node->item($i)->nodeValue);
    }

    for ($i = 0; $i < 5; $i++){
        for ($j = 1; $j < 5; $j++){
            $menuItem = trim($node->item(5*$i+$j)->nodeValue);
            array_push($foods[$i]['menu'], $menuItem);
        }
        array_push($foods[$i]['menu'], ...$staticMenu);
    }

    $data = ["timestamp" => (new DateTime())->getTimestamp(), "data" => $foods];
    $data['name'] = 'FIITFOOD';

    $fp = fopen('./storage/restaurant3.json', 'w');
    fwrite($fp, json_encode($data));
    fclose($fp);

    return $data;
}