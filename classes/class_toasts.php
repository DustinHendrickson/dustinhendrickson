<?php
/**
 * General site functions.
 *
 * @author Dustin
 */
class Toasts {

    public static function addNewToast($Message, $Type)
    {
        // Here we check to make sure these variables have been setup before trying to push to them.
         $AllToastTypes = array("achievement", "error", "notice", "success", "petbattle");
        foreach ($AllToastTypes as $ToastType){
            if (!isset($_SESSION[$ToastType])){ $_SESSION[$ToastType] = array();}
        }

        // Loop through the type of toast to display and add it to the array.
        switch ($Type) {
            case 'achievement':
                array_push($_SESSION['achievement'], $Message);
                break;

            case 'petbattle':
                array_push($_SESSION['petbattle'], $Message);
                break;

            case 'error':
                array_push($_SESSION['error'], $Message);
                break;

            case 'success':
                array_push($_SESSION['success'], $Message);
                break;

            case 'notice':
                array_push($_SESSION['notice'], $Message);
                break;
        }
    }

    public static function clearAllToasts()
    {
        $_SESSION['achievement'] = array();
        $_SESSION['petbattle'] = array();
        $_SESSION['error'] = array();
        $_SESSION['success'] = array();
        $_SESSION['notice'] = array();
    }

    //Strips strings.
    public static function displayAllToasts()
    {
        // Here we loop through
        $AllToastTypes = array("achievement", "error", "notice", "success", "petbattle");
        foreach ($AllToastTypes as $ToastType){
            if(isset($_SESSION[$ToastType])){
                foreach ($_SESSION[$ToastType] as $Toast){
                    Toasts::displayToast($Toast,$ToastType);
                }

                unset($_SESSION[$ToastType]);
            }
        }
    }

    public static function displayToast($Message,$Type="notice")
    {
        echo '<script type="text/javascript">';
        switch ($Type) {
            case 'error':
                echo '$.growl.error({ message: "' . $Message . '" });';
                break;
            case 'notice':
                echo '$.growl.notice({ message: "' . $Message . '" });';
                break;
            case 'success':
                echo '$.growl.success({ message: "' . $Message . '" });';
                break;
            case 'achievement':
                echo '$.growl.achievement({ message: "' . $Message . '" });';
                break;
            case 'petbattle':
                echo '$.growl.petbattle({ message: "' . $Message . '" });';
                break;

            default:
                 echo '$.growl.notice({ message: "' . $Message . '" });';
                break;
        }
        echo '</script>';
    }



} // End Class
