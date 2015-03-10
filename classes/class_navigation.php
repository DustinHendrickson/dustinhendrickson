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

            //Build up the Login Navigation Array.
            //These will display for any user logged in unless
            //you do a permission check before adding the nav item.
            echo "<nav><ul>";

            if (Functions::Check_User_Permissions('Admin')){
            echo "<li><a href='#'>Admin</a>";
            echo "<ul>";
            echo "<li><a href='?view=logs'>Logs</a></li>";
            echo "<li><a href='?view=console'>Console</a></li>";
            echo "<li><a target='_blank' href='mysql/'>Mysql</a></li>";
            echo "</ul></li>";
            }

            if (Functions::Check_User_Permissions('Staff')){
            echo "<li><a href='#'>Staff</a>";
            echo "<ul>";
            echo "<li><a href='?view=blog_admin'>Blog Admin</a></li>";
            echo "<li><a href='?view=servers'>View Servers</a></li>";
            echo "<li><a href='?view=edit_user'>Edit Users</a></li>";
            echo "<li><a href='?view=upload'>Upload File</a></li>";
            echo "</ul></li>";
            }

            echo "<li><a href='#'>User</a>";
            echo "<ul>";
            echo "<li><a href='?view=settings'>Settings</a></li>";
            echo "<li><a href='?view=fightbot'>FightBot</a></li>";
            echo "<li><a href='?view=points'>Points</a></li>";
            echo "<li><a href='?view=bnet'>Battle.Net Status</a></li>";
            echo "<li><a href='?view=achievements'>Achievements</a></li>";
            echo "<li><a href='?view=emulator'>Web Emulator</a></li>";
            echo "<li><a href='?view=petbattle_home'>Pet Battles</a></li>";
            echo "<li><a href='?view=snake'>Snake</a></li>";
            echo "</ul></li>";

            echo "</ul></nav>";
        }
    }

    public static function write_Login()
    {
            if(Functions::Check_User_Permissions('User')) {
                $User = new User($_SESSION['ID']);
                echo "<div id='pointrefresh'><img height='20px' width='20px' src='../img/ajax-loader.gif'> <b>Loading User...</b></div>";
            } else {
                echo "
                    <form action='/' method='post'>
                        <input name='Username' type='text' size='10'>
                        <input name='Password' type='password' size='10'>
                        <input name='Login' type='hidden' value='true'>
                        <input type='submit' value='Login'>
                        | <a href='?view=register'>Register</a>
                    </form>";

            }
    }

    public static function catch_Login()
    {
		if(isset($_POST['Login'])) {
            $Auth = new Authentication;
            $Auth->Login($_POST['Username'],$_POST['Password']);
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
