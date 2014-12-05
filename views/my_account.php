<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();

switch ($_POST['Mode'])
    {
        case 'Save':
            $User_Config = array();

            // Here we buildup an array of items we want to change for the user, if nothing is set we don't send that item.
            if ($_POST['First_Name']!='' && $_POST['First_Name']!=$User->First_Name){ $User_Config['First_Name'] = Functions::Make_Safe($_POST['First_Name']); }
            if ($_POST['Last_Name']!='' && $_POST['Last_Name']!=$User->Last_Name){ $User_Config['Last_Name'] = Functions::Make_Safe($_POST['Last_Name']); }
            if ($_POST['Password']!=''){ $Password = md5(Functions::Make_Safe($_POST['Password'])); $User_Config['Password'] = $Password; }
            if ($_POST['FightBot_Name']!='' && $_POST['FightBot_Name']!=$User->FightBot_Name){ $User_Config['FightBot_Name'] = Functions::Make_Safe($_POST['FightBot_Name']); }

            // Make sure we actually changed something before submit.
            if ($User_Config) {
                $User->Edit_User($User_Config);
                if(isset($_POST['FightBot_Name']) && $_POST['FightBot_Name'] != '') {
                    $User->Add_Achievement("Set FightBot Name");
                }
            }
            break;
    }
?>
<div class='ContentHeader'>Account Details</div><hr>

<form action='?view=<?php echo $View; ?>' method='post'>
        <table>

            <tr>
                <td>
                    First Name:
                </td>
                <td>
                    <input size='50' type='text' value='<?php echo $User->First_Name; ?>' name='First_Name'>
                </td>
            </tr>

            <tr>
                <td>
                    Last Name:
                </td>
                <td>
                    <input size='50' type='text' value='<?php echo $User->Last_Name; ?>' name='Last_Name'>
                </td>
            </tr>

            <tr>
                <td>
                    Fight Name:
                </td>
                <td>
                    <input size='50' type='text' value='<?php echo $User->FightBot_Name; ?>' name='FightBot_Name'> <br />
                </td>
            </tr>

            <tr>
                <td>
                    Email:
                </td>
                <td>
                    <?php echo $User->EMail; ?>
                </td>
            </tr>

            <tr>
                <td>
                    Password:
                </td>
                <td>
                    <input size='50' type='text' value='' name='Password'>
                </td>
            </tr>

            <tr>
                <td>
                    Permissions:
                </td>
                <td>
                    <?php echo $User->Get_Permissions(); ?>
                </td>
            </tr>

            <tr>
                <td>
                    Last Login:
                </td>
                <td>
                    <?php echo $User->Account_Last_Login; ?>
                </td>
            </tr>

            <tr>
                <td>
                    Account Creation:
                </td>
                <td>
                    <?php echo $User->Account_Created; ?>
                </td>
            </tr>

            <tr>
                <td>
                    Account Status:
                </td>
                <td>
                    <?php echo $User->Get_Account_Status(); ?>
                </td>
            </tr>

            <tr>
                <td>
                    <input name='userID' type='hidden' value='<?php echo $User->ID; ?>'>
                </td>
            </tr>

        </table>
        <input size='10' type='submit' value='Save' name='Mode'>
    </form>