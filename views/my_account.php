<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();

switch ($_POST['Mode'])
    {
        case 'Save':
            $User_Config = array();

            if ($_POST['First_Name']!='' && $_POST['First_Name']!=$User->First_Name){ $User_Config['First_Name'] = Functions::Make_Safe($_POST['First_Name']); }
            if ($_POST['Last_Name']!='' && $_POST['Last_Name']!=$User->Last_Name){ $User_Config['Last_Name'] = Functions::Make_Safe($_POST['Last_Name']); }
            if ($_POST['Password']!=''){ $Password = md5(Functions::Make_Safe($_POST['Password'])); $User_Config['Password'] = $Password; }
            if ($_POST['FightBot_Name']!='' && $_POST['FightBot_Name']!=$User->FightBot_Name){ $User_Config['FightBot_Name'] = Functions::Make_Safe($_POST['FightBot_Name']); }

            if ($User_Config) {
                $User->Edit_User($User_Config);
                if(isset($_POST['FightBot_Name']) && $_POST['FightBot_Name'] != '') {
                    $User->Add_Achievement("Set FightBot Name");
                }
            }
            break;
    }
?>

<form action='?view=<?php echo $View; ?>' method='post'>
<b>Username:</b> <?php echo $User->Username; ?><br /><br />
<b>First Name:</b> <input size='50' type='text' value='<?php echo $User->First_Name; ?>' name='First_Name'> <br />
<b>Last Name:</b> <input size='50' type='text' value='<?php echo $User->Last_Name; ?>' name='Last_Name'> <br />
<b>Fight Name:</b> <input size='50' type='text' value='<?php echo $User->FightBot_Name; ?>' name='FightBot_Name'> <br />
<b>Email:</b> <?php echo $User->EMail; ?> <br />
<b>Password:</b> <input size='50' type='text' value='' name='Password'> <br /><br />
<b>Permissions:</b> <?php echo $User->Get_Permissions(); ?> <br />
<b>Last Login:</b> <?php echo $User->Account_Last_Login; ?> <br /><br />
<b>Account Creation:</b> <?php echo $User->Account_Created; ?> <br />
<b>Account Status:</b> <?php echo $User->Get_Account_Status(); ?> <br /><br />
<input name='userID' type='hidden' value='<?php echo $User->ID; ?>'>
<input size='10' type='submit' value='Save' name='Mode'>
</form>
