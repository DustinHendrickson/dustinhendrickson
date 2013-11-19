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
            Write_Log('php', "NOTICE: Could not find the file 'views/'.$view.'.php'");
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
        <script type='text/javascript' src='js/tiny_mce/tiny_mce.js'></script>

        <script type='text/javascript'>
            tinyMCE.init({
                // General options
                mode : 'textareas',
                theme : 'advanced',
                plugins : 'autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist',

                // Theme options
                theme_advanced_buttons1 : 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',
                theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor',
                theme_advanced_buttons3 : 'hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen,insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft',                theme_advanced_toolbar_location : 'top',
                theme_advanced_toolbar_align : 'left',
                theme_advanced_statusbar_location : 'bottom',
                theme_advanced_resizing : true,

                // Drop lists for link/image/media/template dialogs
                template_external_list_url : 'lists/template_list.js',
                external_link_list_url : 'lists/link_list.js',
                external_image_list_url : 'lists/image_list.js',
                media_external_list_url : 'lists/media_list.js',

                // Style formats
                style_formats : [
                    {title : 'Bold text', inline : 'b'},
                    {title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
                    {title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
                    {title : 'Example 1', inline : 'span', classes : 'example1'},
                    {title : 'Example 2', inline : 'span', classes : 'example2'},
                    {title : 'Table styles'},
                    {title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
                ],
            });
        </script>
        ";
    }

}//END CLASS
