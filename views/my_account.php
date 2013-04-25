<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);

    echo "<b>Username:</b> " . $User->Username . "<br />";
    echo "<b>Real Name:</b> " . $User->Get_Full_Name() . "<br />";
    echo "<b>Permissions:</b> " . $User->Get_Permissions()  . "<br />";
    echo "<b>Last Login:</b> " . $User->Account_Last_Login. "<br />";
    echo "<b>Account Creation:</b> " . $User->Account_Created . "<br />";
    echo "<b>Account Status:</b> " .  $User->Get_Account_Status() . "<br />";