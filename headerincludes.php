<?php
//Start Session
session_start();

//Setup Globals
$GLOBALS['Path'] = $_SERVER["DOCUMENT_ROOT"] . '/'; //Site Path for anything that needs it.
$GLOBALS['Query_String'] = $_SERVER['REQUEST_URI'];

//Write_Log('debug', '<pre>'.print_r($_SERVER, TRUE).'</pre>');


//Setup requirements
require_once("config/PHPConfig.php");               //php.ini config
require_once("config/tracking.php");                //Google Analytics
require_once("classes/class_logging.php");          //Logging
require_once("classes/class_connection.php");       //Connection.
require_once("classes/class_functions.php");        //Functions.
require_once("classes/class_toasts.php");           //Pop up Toasts
require_once("classes/class_navigation.php");       //Navigation.
require_once("classes/class_user.php");             //User.
require_once("classes/class_authentication.php");   //Authentication.
require_once("classes/class_blog.php");             //Blog.
require_once("classes/class_battlepets.php");       //Battle Pets.
require_once("/home/dustin/spyc/Spyc.php");         //YAML Library.

Functions::Setup_Div_Toggle();