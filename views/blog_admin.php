<?php
Functions::Verify_Session_Redirect();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$Blog = new Blog();

$Blog_Posts = $Blog->Get_Posts();

foreach ($Blog_Posts as $Blog_Post)
{
    $User = new User($Blog_Post['UserID']);

    echo "<b>ID:</b> " . $Blog_Post['ID'] . "<br />";
    echo "<b>Username:</b> " . $User->Username . "<br />";
    echo "<b>Title:</b> " . $Blog_Post['Title']. "<br />";
    echo "<b>Body</b> " . $Blog_Post['Body'] . "<br />";
    echo "<b>Creation Date</b> " .  $Blog_Post['Creation_Date'] . "<br />";
    echo "-------------<br>";
}

if (isset($_GET['id'])){$ID=$_GET['id'];}{$ID=1;};

switch ($_GET['mode']){

    case 'edit':
        $Blog->Edit_Post($ID,1,'New Blog Entry Edit','BODY!');
        break;

    case 'add':
        $Blog->Add_Post(1,'New Post Default','This is some body');
        break;

    case 'delete':
        $Blog->Delete_Post($ID);
        break;

}


?>
