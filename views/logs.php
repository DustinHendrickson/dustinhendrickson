<?php

$log = $_GET['log'];

$logFiles = scandir($GLOBALS['Path'] . '/logs/');

foreach($logFiles as $file) {
    echo "<a href='?log={$file}'>";
    echo $file;
    echo "</a> |";
}


if (isset($log)) {
    $logText = file_get_content($log);

    echo $logText;
}