<?php
Functions::Check_User_Permissions_Redirect('User');
$User = new User($_SESSION['ID']);

echo "<div class='ContentHeader'>Points</div><hr>";
echo "You have {$User->Get_Points()}.";
