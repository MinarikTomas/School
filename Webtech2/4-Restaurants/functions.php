<?php

function getDataFromJson($path) :array{
    $json = file_get_contents($path);

    $json_data = json_decode($json, true);
    $data['foods'] = $json_data['data'];

    $data['timeDiff'] = (new DateTime())->getTimestamp() - $json_data['timestamp'];

    return $data;
}

function initialArraySetup() :array {
    return [
        ["date"  => date( 'd.m.Y', strtotime( 'monday this week' ) ), "day" => "Pondelok", "menu" => []],
        ["date"  => date( 'd.m.Y', strtotime( 'tuesday this week' ) ), "day" => "Utorok", "menu" => []],
        ["date"  => date( 'd.m.Y', strtotime( 'wednesday this week' ) ), "day" => "Streda", "menu" => []],
        ["date"  => date( 'd.m.Y', strtotime( 'thursday this week' ) ), "day" => "Štvrtok", "menu" => []],
        ["date"  => date( 'd.m.Y', strtotime( 'friday this week' ) ), "day" => "Piatok", "menu" => []],
        ["date"  => date( 'd.m.Y', strtotime( 'saturday this week' ) ), "day" => "Sobota", "menu" => []],
        ["date"  => date( 'd.m.Y', strtotime( 'sunday this week' ) ), "day" => "Nedeľa", "menu" => []],
    ];
}
