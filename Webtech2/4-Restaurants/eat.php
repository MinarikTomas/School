<?php
require_once 'functions.php';
if(file_exists('./storage/restaurant2.json')){
    $data = getDataFromJson('./storage/restaurant2.json');
    $jedla = $data['foods'];


    if($data['timeDiff'] > 18000){
        $jedla = getDataEat();
    }
}else{
    $jedla = getDataEat();
}

function getDataEat() :array {
    $ch = curl_init();

// set ur
    curl_setopt($ch, CURLOPT_URL, "http://eatandmeet.sk/tyzdenne-menu");

//return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
    $output = curl_exec($ch);

// close curl resource to free up system resources
    curl_close($ch);

    $dom = new DOMDocument();

    @$dom->loadHTML($output);
    $dom->preserveWhiteSpace = false;

    $parseNodes = ["day-1", "day-2", "day-3", "day-4", "day-5", "day-6", "day-7"];

    $jedla = initialArraySetup();

    $grillMenu = getMenu($dom, 3);
    $liveMenu = getMenu($dom, 5);

    foreach ($parseNodes as $index => $nodeId) {

        $node = $dom->getElementById($nodeId);

        foreach ($node->childNodes as $menuItem)
        {
            if($menuItem && $menuItem->childNodes->item(1) && $menuItem->childNodes->item(1)->childNodes->item(3)){
                $nazov = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue);
                $cena = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(3)->nodeValue);
                $popis = trim($menuItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(3)->nodeValue);
                array_push($jedla[$index]["menu"], "$nazov: $popis: $cena");
            }
        }

        array_push($jedla[$index]["menu"], ...$grillMenu);
        array_push($jedla[$index]["menu"], ...$liveMenu);
    }

    $data = ["timestamp" => (new DateTime())->getTimestamp(), "data" => $jedla];
    $data['name'] = 'Eat & Meet';

    $fp = fopen('./storage/restaurant2.json', 'w');
    fwrite($fp, json_encode($data));
    fclose($fp);

    return $data;
}

function getMenu($dom, $index) :array {
    $section = $dom->getElementsByTagName('section')->item($index);
    $nodes = $section->childNodes->item(1)->childNodes->item(1)->childNodes;
    $menu = [];

    foreach ($nodes as $grillItem)
    {
        if($grillItem && $grillItem->childNodes->item(1) && $grillItem->childNodes->item(1)->childNodes->item(3)){
            $nazov = trim($grillItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(1)->nodeValue);
            $cena = trim($grillItem->childNodes->item(1)->childNodes->item(3)->childNodes->item(1)->childNodes->item(3)->nodeValue);
            array_push($menu, "$nazov: $cena");
        }
    }

    return $menu;
}