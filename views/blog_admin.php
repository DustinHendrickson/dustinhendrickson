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
    echo "<b>ID:</b> " . $Blog_Post['ID'] . "<br />";
    echo "<b>UserID:</b> " .$Blog_Post['UserID']  . "<br />";
    echo "<b>Title:</b> " . $Blog_Post['Title']. "<br />";
    echo "<b>Body</b> " . $Blog_Post['Body'] . "<br />";
    echo "<b>Creation Date</b> " .  $Blog_Post['Creation_Date'] . "<br />";
    echo "<br>-------------<br>";
}
?>