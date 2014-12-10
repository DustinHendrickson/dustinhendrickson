<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();
?>
<div class='ContentHeader'>Leaderboards | <a href='?view=petbattle_home'><- Back to Homescreen</a></div><hr><br><br>
