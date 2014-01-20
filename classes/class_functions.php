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
                       theme : 'modern',
                        mode : 'textareas'
            });
        </script>
        ";
    }

}//END CLASS
