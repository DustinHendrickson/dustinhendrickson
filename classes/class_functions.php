<?php
/**
 * General site functions.
 *
 * @author Dustin
 */
class Functions {

    //Strips strings.
    public static function Make_Safe(&$string)
    {
        return $string;
    }

    //Returns the current view.
    public static function Get_View()
    {
        $DEFAULT_VIEW = 'blog';

        if(isset($_GET['view'])){
            $view = self::Make_Safe($_GET['view']);
        } else {
            $view = $DEFAULT_VIEW;
        }

        return $view;
    }

    //Checks to make sure the content view exists and displays it.
    public static function Display_View($view)
    {
        if(file_exists('views/'.$view.'.php')) {
            include('views/'.$view.'.php');
        } else {
            Write_Log('php', "NOTICE: Could not find the file 'views/'. {$view} .'.php'");
            include('views/404.php');
        }
    }

    //Returns true if the user's permissions array contains the entered string.
    public static function Check_User_Permissions($PermissionLevelRequired='')
    {
        if(isset($_SESSION['ID'])) {
            $User = new User($_SESSION['ID']);

            $User_Permissions = $User->Get_Permissions('Array');

            if (in_array($PermissionLevelRequired,$User_Permissions)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    //Checks the users permissions against the entered string, if there's no match it redirects.
    public static function Check_User_Permissions_Redirect($PermissionLevelRequired='')
    {
        if(isset($_SESSION['ID'])) {
            $User = new User($_SESSION['ID']);

            $User_Permissions = $User->Get_Permissions('Array');

            if (!in_array($PermissionLevelRequired,$User_Permissions)) {
                Write_Log('php','NOTICE: Attempt to access a page without permissions.');
                header( 'Location: ?' );
            }
        } else {
            Write_Log('php','NOTICE: Attempt to access a page without logging in.');
            header( 'Location: ?' );
        }
    }

    //Rereshes page.
    public static function Refresh_Page($Interval_Seconds)
    {
        $Refresh_Interval = $Interval_Seconds * 1000;

        echo "
        <head>
        <script type='text/JavaScript'>
        <!--
        function timedRefresh(timeoutPeriod) {
            setTimeout('location.reload(true);',timeoutPeriod);
        }
        //   -->
        </script>
        </head>
        <body onload='JavaScript:timedRefresh({$Refresh_Interval});'>
        ";
    }

    //Anytime you need fancy text inputs, call this at the top of the page.
    //This replaces any <textarea> with a fancy editor.
    public static function Prepare_TinyMCE()
    {
        echo"
        <script type='text/javascript' src='js/tinymce/tinymce.min.js'></script>

        <script type='text/javascript'>
            tinyMCE.init({
                    	theme: 'modern',
                        mode: 'textareas',
                        width: '100%',
					    height: 300,
					    plugins: [
					         'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
					         'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
					         'save table contextmenu directionality emoticons template paste textcolor'
					   ],
					   toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons',
					   style_formats: [
					        {title: 'Bold text', inline: 'b'},
					        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
					        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
					        {title: 'Example 1', inline: 'span', classes: 'example1'},
					        {title: 'Example 2', inline: 'span', classes: 'example2'},
					        {title: 'Table styles'},
					        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
					    ]

            });
        </script>
        ";
    }

    // This function calls custom divs to refresh their PHP content from js/views/ this can be pass variables through GET or POST
    // Each page called from .load must include "include('../../headerincludes.php');" so that the page can reference librarys.
    public static function RefreshDivs()
    {
        if (self::Get_View() == 'fightbot') {
            echo '<script type="text/javascript">';
            echo '$(document).ready(function() {';
            echo '$.ajaxSetup({ cache: false });';
            echo 'setInterval(function() {';
            echo '$("#FightBotStats").load("js/views/fightbot.php", {UserSessionID:'. $_SESSION["ID"] .'});';
            echo 'if (status == "error") {';
            echo 'console.log(msg + xhr.status + " " + xhr.statusText);';
            echo '}';
            echo '}, 3000);';
            echo '});';
            echo '</script>';
        }

        if (self::Get_View() == 'logs') {
            echo '<script type="text/javascript">';
            echo '$(document).ready(function() {';
            echo '$.ajaxSetup({ cache: false });';
            echo 'setInterval(function() {';
            echo '$("#LogBox").load("js/views/logs.php?log='.$_GET["log"].'");';
            echo 'if (status == "error") {';
            echo 'console.log(msg + xhr.status + " " + xhr.statusText);';
            echo '}';
            echo '}, 3000);';
            echo '});';
            echo '</script>';
        }

        if (self::Get_View() == 'servers') {
            echo '<script type="text/javascript">';
            echo '$(document).ready(function() {';
            echo '$.ajaxSetup({ cache: false });';
            echo 'setInterval(function() {';
            echo '$("#ServerList").load("js/views/servers.php");';
            echo 'if (status == "error") {';
            echo 'console.log(msg + xhr.status + " " + xhr.statusText);';
            echo '}';
            echo '}, 3000);';
            echo '});';
            echo '</script>';
        }

    }

    public static function getServerStatus($ServerName)
    {
      exec("ps aux | pgrep " . $ServerName, $ServerStatus);

       if ($ServerStatus[0] != "") {
         return "Up";
       } else {
         return "Down";
       }
    }

    public static function displayServerStatus($ServerStatus)
    {
      switch ($ServerStatus){
            case "Up":
              echo "<div class='success'> {$ServerStatus} </div>";
              break;
            case "Down":
              echo "<div class='error'> {$ServerStatus} </div>";
              break;
          }
    }

    public static function getRemoteSeverStatusFromPort($RemoteSite, $PortNumber)
    {
      // Here we do a basic connection test to see if this port is reachable on the network, times out in 1 seconds,
      // if the timeout succeeds, we know that server is down. Otherwise it's up.
      $socket = @fsockopen($RemoteSite, $PortNumber, $errorNumber, $errorString, 1);
      if ($socket){
        fclose($socket);
        return "Up";
      } else {
        return "Down";
      }
    }

    public static function getNumberOfConnectionsOnPort($PortNumber)
    {
      // Only works on internal connections, returns the number of connections to the specific port.
      $count = shell_exec("netstat -an | grep :{$PortNumber} | grep ESTABLISHED | wc --lines");
      return $count;
    }

}//END CLASS
