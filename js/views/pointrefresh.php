<?PHP
include('../../headerincludes.php');

if(Functions::Check_User_Permissions('User')) {
    $User = new User($_SESSION['ID']);
    echo "[ <b><a href='?view=points'>{$User->Get_Points()}</a></b> ] <a href='?view=my_account'>" . $_SESSION['Name'] . "</a> | <a href='?view=logout'>Logout</a>";
}