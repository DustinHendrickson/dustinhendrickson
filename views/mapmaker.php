<?php

$CellArray = array();

$I = 0;
$W = 1;

$MAX_X = 15;
$MAX_Y = 9;

$MAX_WORLDS = 160;

while ($W <= $MAX_WORLDS) {

    $MAX_GRASS = rand(5,15);
    $MAX_RESTRICTED = rand(2,9);
    $I = 0;
    $R = 0;
    while ($I <= $MAX_GRASS) {
        $CellArray["Grass"][$I]["GrassX"] = rand(0,15);
        $CellArray["Grass"][$I]["GrassY"] = rand(0,9);
        $I++;
    }
    while ($R <= $MAX_RESTRICTED) {
        $CellArray["Restricted"][$R]["BlockX"] = rand(0,15);
        $CellArray["Restricted"][$R]["BlockY"] = rand(0,9);
        $R++;
    }

    $fp = fopen('petbattles/maps/map' . $W . '.json', 'w');
    fwrite($fp, json_encode($CellArray));
    fclose($fp);
    $W++;
}


