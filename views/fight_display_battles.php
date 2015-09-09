<?php
//session_start();
// GRAB ALL EVENTS FROM THE DATABASE AND DISPLAY THEM


$_SESSION["FightLines"][] = count($_SESSION["FightLines"]);
foreach (array_reverse($_SESSION["FightLines"]) as $Line) {
    echo $Line . " <br>";
}

// foreach ($RandomArray as $Line) {
//     echo $Line . " <br>";
// }