<?php
include('../../headerincludes.php');

$log = $_GET['log'];

//Read in the Log file to an array and reverse the order for html display.
$logLines = array_reverse(file($GLOBALS['Path'] . '/logs/' . $log));

//Loop through each line and echo the string.
foreach ($logLines as $Index => $Line) {
    echo Color_Log_Entry($Index . ". " . $Line);
}
?>
