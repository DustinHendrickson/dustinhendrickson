<div class='ContentHeader'>Logs</div><hr>
<?php
//Apply any page restrictions.
Functions::Check_User_Permissions_Redirect('Admin');

//Get which log is going to be displayed
$log = $_GET['log'];

//Populate an array of files in the logs folder.
$logFiles = scandir($GLOBALS['Path'] . '/logs/');

//We loop through each file and echo it's link.
foreach($logFiles as $file) {
    if ($file != '.' && $file != '..' && $file != '.htaccess') {
        echo "<a href='?view=logs&log={$file}'>";
        echo $file;
        echo "</a> | ";
    }
}

echo '<hr>';

//Check if a log has been selected to view, if so start refreshing.
//if ( isset($log) ) { Functions::Refresh_Page(10); }

//Make sure a log was clicked.
if ( isset($log) ) {

    //Here we setup our scrolling text box so the log doesn't take up to much space.
    echo '<div id="LogBox" name="logs" style="overflow:auto; height:60%; width:100%">';
    echo '<img src="../img/ajax-loader.gif"> <b>Loading Logs...</b>';
    echo '</div>';
}
?>
