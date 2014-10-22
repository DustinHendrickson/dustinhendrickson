<?php
Functions::Check_User_Permissions_Redirect("Staff");
$User = new User($_SESSION['ID']);
$User->Add_Achievement("View Servers");
?>
<div class='ContentHeader'>Server Status Listings</div><hr>
<div id='ServerList'>
<img src="../img/ajax-loader.gif"> <b>Loading Servers...</b>
</div>

