<?php
//Apply any page restrictions.
Functions::Check_User_Permissions_Redirect('Admin');
Functions::Refresh_Page(10);

//Get which log is going to be displayed
$log = $_GET['log'];

//Populate an array of files in the logs folder.
$logFiles = scandir($GLOBALS['Path'] . '/logs/');

//We loop through each file and export it's link.
foreach($logFiles as $file) {
    if ($file != '.' && $file != '..') {
        echo "<a href='?view=logs&log={$file}'>";
        echo $file;
        echo "</a> |";
    }
}

echo '<br/><br/>';

//Make sure a log was clicked.
if (isset($log)) {

    //Here we setup our scrolling text box so the log doesn't take up to much space.
    echo '<div style="overflow:auto; height:400px; width:100%">';

    //Read in the Log file to an array and reverse the order for html display.
    $logLines = array_reverse(file($GLOBALS['Path'] . '/logs/' . $log));

    //Loop through each line and echo the string.
    foreach ($logLines as $Line) {
        echo $Line . '<br/>';
    }

    echo '</div>';
}