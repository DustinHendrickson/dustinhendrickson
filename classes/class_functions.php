<?php
/**
 * General site functions.
 *
 * @author Dustin
 */
class Functions {

    //Strips strings. !!DEV
    public static function Make_Safe(&$string){
        return mysql_escape_string($string);
    }

    public static function Get_View() {
        if(isset($_GET['view'])){
            $view = self::Make_Safe($_GET['view']);
        } else {
            $view = "blog";
        }

        return $view;
    }

    public static function Display_View($view) {
        if(file_exists("views/".$view.".php")) {
            include("views/".$view.".php");
        } else {
            Write_Log("php", "NOTICE: Could not find the file 'views/".$view.".php'");
            include("views/404.php");
        }
    }

    public static function Verify_Session() {
        if(isset($_SESSION["ID"])) {
            return true;
        } else {
            //Write_Log("php", "SECURITY: Failed session verification.");
            return false;
        }
    }

    public static function Verify_Session_Redirect() {
        if(!isset($_SESSION["ID"])) {
            header( 'Location: ?' );
        }
    }

    public static function Check_User_Permissions($PermissionLevelRequired=""){
        if(isset($_SESSION["ID"])) {
            $User = new User($_SESSION["ID"]);

            $User_Permissions = $User->Get_Permissions('Array');

            if (in_array($PermissionLevelRequired,$User_Permissions)) {
                return true;
            } else {
                return false;
            }

        }
    }

    public static function Check_User_Permissions_Redirect($PermissionLevelRequired=""){
        if(isset($_SESSION["ID"])) {
            $User = new User($_SESSION["ID"]);

            $User_Permissions = $User->Get_Permissions('Array');

            if (!in_array($PermissionLevelRequired,$User_Permissions)) {
                header( 'Location: ?' );
            }

        }
    }

}//END CLASS