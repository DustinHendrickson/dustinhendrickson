<?php
//Start Session
session_start();

//Setup Globals
$GLOBALS['Path'] = '/var/www/dustin/';              //Site Path for anything that needs it.

//Setup requirements
require_once("config/PHPConfig.php");               //php.ini config
require_once("classes/class_logging.php");          //Logging
require_once("classes/class_connection.php");       //Connection.
require_once("classes/class_functions.php");        //Functions.
require_once("classes/class_navigation.php");       //Navigation.
require_once("classes/class_user.php");             //User.
require_once("classes/class_authentication.php");   //Authentication.
require_once("classes/class_blog.php");             //Blog.

?>
<HTML>
    <HEAD>
        <link href="css/frontend.css" rel="stylesheet" type="text/css">
        <TITLE>
        Dustin Hendrickson.com - Official Site
        </TITLE>
    </HEAD>

    <BODY>

        <div id="Login-Navigation">
            <div class="WelcomeText">
                <?php Navigation::Login_Write_Welcome_Message(); ?>
            </div>
                <?php Navigation::Login_Write(); ?>
        </div>

        <div id="BodyWrapper">

        <div id="Header">
            <div class="Logo"></div>
        </div>


        <div id="Public-Navigation">
            <?php Navigation::Public_Write(); ?>
        </div>

        <div id="Content">
            <?php Functions::Display_View(Functions::Get_View()); ?>
        </div>

        <div id="BottomContentWrapper">
            <div class="BottomLeftContent">
            Left Box
            </div>

            <div class="BottomRightContent">
            Right Box
            </div>
        </div>

        <div class="Push"></div>
        </div>

        <div id="Footer">

        <table width=100% padding=10px>
            <tr>
                <td><b>Friends</b></td>
                <td><b>About</b></td>
                <td><b>Contact</b></td>
                <td><b>Community</b></td>
            </tr>
            <tr>
                <td><a href='http://omfg.fm' target='_blank'>OMFG.fm</a></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr>
                <td><a href='http://kylemccarley.com' target='_blank'>KyleMcCarley.com</a></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr>
                <td><a href='http://fake.com' target='_blank'>Fake Site.com</a></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            <tr>
                <td><a href='http://blarg.net' target='_blank'>BLARG.net</a></td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
        </table>

        </div>

    </BODY>
</HTML>