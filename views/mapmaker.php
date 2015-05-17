<?php


function Create_Random_Maps() {
    $CellArray = array();

    $MAX_X = 24; //24
    $MAX_Y = 14; //14
    $CURRENT_X = 0;
    $CURRENT_Y = 0;
    $TOTAL_CELLS = ($MAX_X + 1) * ($MAX_Y + 1);
    $Cell = 1;
    $World = 1;
    $ITERATIONS = 0;

    $MAX_WORLDS = 160;

    while ($World <= $MAX_WORLDS) {
        $Cell = 1;
        $CURRENT_X = 0;
        $CURRENT_Y = 0;

        while ($Cell <= $TOTAL_CELLS) {
            while ($CURRENT_X <= $MAX_X) {

                while ($CURRENT_Y <= $MAX_Y) {

                    $Random = rand(0,2);

                    if ($Random == 0) { // Open Space
                        $CellArray["Cell"][$Cell]["X"] = $CURRENT_X;
                        $CellArray["Cell"][$Cell]["Y"] = $CURRENT_Y;
                        $CellArray["Cell"][$Cell]["Pathing"] = "Open";
                        $CellArray["Cell"][$Cell]["Type"] = "Empty";
                    }

                    if ($Random == 1) { // Tall Grass
                        $CellArray["Cell"][$Cell]["X"] = $CURRENT_X;
                        $CellArray["Cell"][$Cell]["Y"] = $CURRENT_Y;
                        $CellArray["Cell"][$Cell]["Pathing"] = "Open";
                        $CellArray["Cell"][$Cell]["Type"] = "TallGrass";
                    }

                    if ($Random == 2) { // Rock
                        $CellArray["Cell"][$Cell]["X"] = $CURRENT_X;
                        $CellArray["Cell"][$Cell]["Y"] = $CURRENT_Y;
                        $CellArray["Cell"][$Cell]["Pathing"] = "Blocked";
                        $CellArray["Cell"][$Cell]["Type"] = "Rock";
                    }
                    $CURRENT_Y++;
                    $Cell++;
                    $ITERATIONS++;
                }

                $CURRENT_X++;
                $CURRENT_Y = 0;
            }
        }

        $fp = fopen('petbattles/maps/map' . $World . '.json', 'w');
        fwrite($fp, json_encode($CellArray));
        fclose($fp);
        $World++;
    }
    echo "Maps Created." . $MAX_WORLDS . "<br>" . " Total Iterations: " . $ITERATIONS;
}

Create_Random_Maps();
