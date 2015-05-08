<?php


function Create_Random_Maps() {
    $CellArray = array();

    $I = 0;
    $W = 1;

    $MAX_X = 24;
    $MAX_Y = 14;

    $MAX_WORLDS = 160;

    while ($W <= $MAX_WORLDS) {

        $MAX_GRASS = rand(40,70);
        $MAX_RESTRICTED = rand(2,15);
        $I = 0;
        $R = 0;
        while ($I <= $MAX_GRASS) {
            $CellArray["Grass"][$I]["GrassX"] = rand(0,$MAX_X);
            $CellArray["Grass"][$I]["GrassY"] = rand(0,$MAX_Y);
            $I++;
        }
        while ($R <= $MAX_RESTRICTED) {
            $CellArray["Restricted"][$R]["BlockX"] = rand(0,$MAX_X);
            $CellArray["Restricted"][$R]["BlockY"] = rand(0,$MAX_Y);
            $R++;
        }

        $fp = fopen('petbattles/maps/map' . $W . '.json', 'w');
        fwrite($fp, json_encode($CellArray));
        fclose($fp);
        $W++;
    }
}

Create_Random_Maps();