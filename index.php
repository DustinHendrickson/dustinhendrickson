<?php
include('headerincludes.php');

//Log each index visit.
Write_Log('views',"Site has logged a page view.");
?>
<HTML>
    <HEAD>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js" type="text/javascript"></script>
        <script src="js/jquery/jquery.growl.js" type="text/javascript"></script>
        <link href="css/jquery.growl.css" rel="stylesheet" type="text/css" />

        <?php Functions::RefreshDivs(); Functions::RefreshDivs('showtime'); Functions::RefreshDivs('pointrefresh'); ?>
        <link href="css/frontend.css" rel="stylesheet" type="text/css">
        <?php $User = new User($_SESSION['ID']); $User->Display_Theme(); ?>
        <TITLE>
        DustinHendrickson.com - Official Site
        </TITLE>
    </HEAD>

    <BODY>

        <div id="Top-Bar">
            <div class="Login_Area">
                <?php
                Navigation::write_Login();
                Navigation::catch_Login();
                ?>
            </div>
                <?php Navigation::write_Private(); ?>
        </div>

        <div id="BodyWrapper">

<?PHP
    $View = explode("_", $_GET['view']);
    if ($View[0] != "petbattle") { ?>

        <div id="Header">
            <a href='/' class="Logo"></a>
        </div>

        <div id="Public-Navigation">
            <?php Navigation::write_Public(); ?>
        </div>
<?PHP } ?>
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
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
                <td width="25%" height="25px"><a href='' target='_blank'></a></td>
            </tr>
        </table>
        <table>
        <tr><td width="15%" height="25px" align="center">
        <i>&copy; 2012-<?php echo date("Y"); ?> - Dustin Hendrickson</a></i>
        <div id='showtime'>
        <img src="../img/ajax-loader.gif"> <b>Loading Time...</b>
        </div>
        </td></tr>
        </table>

        </div>
    </BODY>
</HTML>
<?php
//Daily Quests Completion
$Pet = new BattlePet($_SESSION['ID']);
$Pet->Check_If_Daily_Quest_Completed();

//Toasts::displayAllToasts();
if (!empty($User->Config_Settings["Show_Toasts"])) {
    if ($User->Config_Settings["Show_Toasts"] == 1) {
        Toasts::displayAllToasts();
    } else {
        Toasts::clearAllToasts();
    }
} else {
    Toasts::displayAllToasts();
}

?>
