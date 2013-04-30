<?php
//Writes to a specified LOG file.
function Write_Log($log, $logentry, $logpath='logs/', $showIP='True')
{
    $NOW = date("h:ia m-d-Y");
    $logpath = $_SERVER['DOCUMENT_ROOT'] . "/" . $logpath;

    if($showIP='True') { $ip = $_SERVER['REMOTE_ADDR']; } else { $ip = "System Process"; }

    if (!file_exists($logpath.$log.'.log')){fopen($logpath . $log . '.log', 'w+');}

    $logfile = fopen($logpath . $log . '.log', 'a');

    fwrite($logfile,"[".$NOW."] | " . $ip . " | " . $logentry . " >> " . $_SERVER['REQUEST_URI'] ."\n");

    fclose($logfile);
    

}