<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();

switch ($_POST['Mode'])
    {
        case 'Save':
            $User = new User($_POST['userID']);
            $User->Edit_User(Functions::Make_Safe($_POST['First_Name']), Functions::Make_Safe($_POST['Last_Name']), Functions::Make_Safe($User->EMail), Functions::Make_Safe($User->Permissions), Functions::Make_Safe($_POST['Password']), Functions::Make_Safe($_POST['FightBot_Name']));
            $User->Display_Message();
            break;
        
    }

echo "<form action='?view={$View}' method='post'>";
echo "<b>Username:</b> " . $User->Username . "<br />";
echo "<b>First Name:</b> <input size='50' type='text' value='{$User->First_Name}' name='First_Name'> <br />";
echo "<b>Last Name:</b> <input size='50' type='text' value='{$User->Last_Name}' name='Last_Name'> <br />";
echo "<b>Fight Name:</b> <input size='50' type='text' value='{$User->FightBot_Name}' name='FightBot_Name'> <br />";
echo "<b>Email:</b> {$User->EMail} <br />";
echo "<b>Password:</b> <input size='50' type='text' value='' name='Password'> <br />";
echo "<b>Permissions:</b> " . $User->Get_Permissions()  . "<br />";
echo "<b>Last Login:</b> " . $User->Account_Last_Login. "<br />";
echo "<b>Account Creation:</b> " . $User->Account_Created . "<br />";
echo "<b>Account Status:</b> " .  $User->Get_Account_Status() . "<br />";
echo "<input name='userID' type='hidden' value='{$User->ID}'>";
echo "<input size='10' type='submit' value='Save' name='Mode'>";
echo "</form>";
