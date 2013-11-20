<?php

/**
 * Description of Navigation
 *
 * @author Dustin
 */

class Navigation {

    public static function write_Private()
    {
        if(Functions::Check_User_Permissions('User')){
            $Nav_Items = array();

            //Build up the Login Navigation Array.
            //These will display for any user logged in unless
            //you do a permission check before adding the nav item.
            array_push($Nav_Items, "<div class='PrivateNavItem'><a href='?view=my_settings'>My Settings<a/></div>\n");
            array_push($Nav_Items, "<div class='PrivateNavItem'><a href='?view=my_points'>My Points</a></div>\n");
            if (Functions::Check_User_Permissions('Staff')){
                array_push($Nav_Items, "<div class='PrivateNavItem'><a href='?view=blog_admin'>Blog Admin</a></div>\n");
                array_push($Nav_Items, "<div class='PrivateNavItem'><a href='?view=logs'>Logs</a></div>\n");
            }

            foreach ($Nav_Items as $Nav_Item){
                echo $Nav_Item;
            }
        }
    }

    public static function write_Login()
    {
            if(Functions::Check_User_Permissions('User')) {
                echo "<a href='?view=my_account'>" . $_SESSION['Name'] . "</a> | <a href='?view=logout'>Logout</a>";
            } else {
                echo "
                    <form action='?view=login' method='post'>
                        <input name='Username' type='text' size='10'>
                        <input name='Password' type='password' size='10'>
                        <input name='Login' type='hidden' value='true'>
                        <input type='submit' value='Login'>
                        | <a href='?view=register'>Register</a>
                    </form>";
            }
    }

    public static function write_Public()
    {
        $Nav_Items = array();

        array_push($Nav_Items, "<div class='NavItem'><a href='?view=blog'><img width='75' height='75' src='img/News.png'></img> Blog</a></div>\n");
        array_push($Nav_Items, "<div class='NavItem'><a href='?view=aboutme'><img width='75' height='75' src='img/Staff.png'></img> About Me</a></div>\n");
        array_push($Nav_Items, "<div class='NavItem'><a href='?view=projects'><img width='75' height='75' src='img/Products.png'></img> Projects</a></div>\n");
        array_push($Nav_Items, "<div class='NavItem'><a href='?view=resume'><img width='75' height='75' src='img/Services.png'></img> Resume</a></div>\n");
        array_push($Nav_Items, "<div class='NavItem'><a href='?view=contact'><img width='75' height='75' src='img/Contact.png'></img> Contact</a></div>\n");
        array_push($Nav_Items, "<div class='NavItem'><a href='?view=media'><img width='75' height='75' src='img/Gallery.png'></img> Media</a></div>\n");

        foreach($Nav_Items as $Nav_Item){
            echo $Nav_Item;
        }
    }

}//END OF CLASS
