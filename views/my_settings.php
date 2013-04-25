<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();

switch ($_POST['Mode'])
    {
        case 'Save':
            $User->Save_Configuration($User->ID,$_POST['Items_Per_Page'],$_POST['Theme'],$_POST['Show_Help']);
            break;
    }

    //Display any messages from the logic.
    if (isset($User->Message)) { echo "<div class='{$User->Message_Type}'>".$User->Message."</div>"; unset($User->Message); }

    echo "
    <form action='?view={$View}' method='post'>
            <table>
                <tr>
                    <td>
                        Items Per Page: 
                    </td>
                    <td>
                        <input name='Items_Per_Page' type='text' value='{$User->Config_Settings['Items_Per_Page']}'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Theme: 
                    </td>
                    <td>
                        <input name='Theme' type='text' value='{$User->Config_Settings['Theme']}'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Show_Help: 
                    </td>
                    <td>
                        <input name='Show_Help' type='text' value='{$User->Config_Settings['Show_Help']}'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name='userID' type='hidden' value='{$User->ID}}'>
                    </td>
                </tr>
            </table>
            <input type='submit' value='Save' name='Mode'>
        </form>
    ";