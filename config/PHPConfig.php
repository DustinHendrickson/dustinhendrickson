<?php
//Instead of Changing the PHP file, we make any global Adjustments here.
error_reporting(E_ALL ^ E_NOTICE);

ini_set("log_errors" , "1");
ini_set("error_log" , $GLOBALS['Path'] ."logs/php.log");
ini_set("display_errors" , "1");