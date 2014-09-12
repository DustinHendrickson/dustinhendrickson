<?php
Functions::Check_User_Permissions_Redirect('User');
$User = new User($_SESSION['ID']);
$View = Functions::Get_View();
$Already_Redeemed = false;

switch ($_POST['Mode'])
    {
        case 'Redeem':
            if ($User->Can_Redeem_Points() == true)
            {
                $PointsToRedeem = $_POST['PointsToRedeem'];
                $User->Redeem_Points($PointsToRedeem);
                $Already_Redeemed = true;
                $User->Display_Message();
                Functions::Refresh_Page_Once();
            }
            break;
    }

echo "<div class='ContentHeader'>Points</div><hr>";
echo "You have <b>{$User->Get_Points()}</b>.";
echo "<hr>";

if ($User->Can_Redeem_Points() == true && $Already_Redeemed == false)
{
    $RandomPoints = rand(1,10);
    echo "<div class='ContentHeader'>Redeem today's points.</div><hr>";
    echo "
    <form action='?view={$View}' method='post'>
            <table>
                <tr>
                    <td>
                        Redeem randomly 1-10 points:
                    </td>
                    <td>
                        <input type='submit' value='Redeem' name='Mode'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name='userID' type='hidden' value='{$User->ID}'>
                        <input name='PointsToRedeem' type='hidden' value='{$RandomPoints}'>
                    </td>
                </tr>
            </table>
        </form>
    ";

} else {
        echo "<div class='ContentHeader'>You have already Redeemed points for this period. Please check again later.</div><br>";
        echo "Your last redeem date is <b>" .  $User->Get_Last_Recieved_Points_DateTime() . "</b><br>";
        echo "You can redeem again on <b>" .  date('F jS Y h:ia', $User->Get_Last_Recieved_Points_UnixTime() + $User->SECONDS_INTERVAL) . "</b><br>";
        echo "Please check back again in <b>" . round(($User->SECONDS_INTERVAL - (time() - $User->Get_Last_Recieved_Points_UnixTime())) / 60 / 60) . "</b> Hours.<br>";
}