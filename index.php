<?php
//Start Session
session_start();

//Setup Globals
$GLOBALS['Path'] = $_SERVER["DOCUMENT_ROOT"] . '/'; //Site Path for anything that needs it.

//Setup requirements
require_once("config/PHPConfig.php");               //php.ini config
require_once("config/tracking.php");                //Google Analytics
require_once("classes/class_logging.php");          //Logging
require_once("classes/class_connection.php");       //Connection.
require_once("classes/class_functions.php");        //Functions.
require_once("classes/class_navigation.php");       //Navigation.
require_once("classes/class_user.php");             //User.
require_once("classes/class_authentication.php");   //Authentication.
require_once("classes/class_blog.php");             //Blog.

//Log each index visit.
Write_Log('views',"Site has logged a page view.");
?>
<HTML>
    <HEAD>
        <link href="css/frontend.css" rel="stylesheet" type="text/css">
        <?php $User = new User($_SESSION['ID']); $User->Display_Theme(); ?>
        <TITLE>
        DustinHendrickson.com - Official Site
        </TITLE>
    </HEAD>

    <BODY>

        <div id="Top-Bar">
            <div class="Login_Area">
                <?php Navigation::write_Login(); ?>
            </div>
                <?php Navigation::write_Private(); ?>
        </div>

        <div id="BodyWrapper">

        <?php Navigation::write_Login_Error(); ?>

        <div id="Header">
            <a href='/' class="Logo"></a>
        </div>

        <div id="Public-Navigation">
            <?php Navigation::write_Public(); ?>
        </div>

        <div id="Content">
            <?php Functions::Display_View(Functions::Get_View()); ?>
        </div>

        <div id="Footer">

        <table width=100% padding=10px>
            <tr>
                <td width="25%" height="25px"><b>Friends</b></td>
                <td width="25%" height="25px"><b>Social</b></td>
                <td width="25%" height="25px"><b>Community</b></td>
                <td width="25%" height="25px"><b></b></td>
            </tr>
            <tr>
                <td width="25%" height="25px"><a href='http://donutboys.com' target='_blank'>Dontboys.com</a></td>
                <td width="25%" height="25px"><a href='http://github.com/dustinhendrickson' target='_blank'>Github</a></td>
                <td width="25%" height="25px"><a href='http://dustinhendrickson.proboards.com/' target='_blank'>Forums</a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
            </tr>
            <tr>
                <td width="25%" height="25px"><a href='http://kylemccarley.com' target='_blank'>KyleMcCarley.com</a></td>
                <td width="25%" height="25px"><a href='https://www.facebook.com/dustinhendrickson' target='_blank'>Facebook</a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
            </tr>
            <tr>
                <td width="25%" height="25px"><a href='http://sc2mapster.com' target='_blank'>SC2Mapster.com</a></td>
                <td width="25%" height="25px"><a href='https://twitter.com/DustinTheBadass' target='_blank'>Twitter</a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
            </tr>
            <tr>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
            </tr>
            <tr>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
            </tr>
            <tr>
                <td width="25%" height="25px"><i>&copy; 2012-<?php echo date("Y"); ?> - Dustin Hendrickson</a></i></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
            </tr>
        </table>

        </div>
    </BODY>
</HTML>
