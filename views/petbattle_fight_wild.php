<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$UserPet = new BattlePet($_SESSION['ID']);
$View = Functions::Get_View();

    switch ($_POST['Action'])
    {
        case 'StartBattle':
            if ($UserPet->Pet_Current_AP > 0) {
                $UserPet->PVE_Save_AP($UserPet->Pet_Current_AP - 1);
                $UserPet->Create_Battle_Room_PVE();
            } else {
                Toasts::addNewToast("You don't have enough AP to fight!", 'petbattle');
            }
            break;

        case 'Skill 1':
            $Battle_Results = $UserPet->PVE_Attack($_SESSION['PVE_User_Pet_Skill_1']);
            break;

        case 'Skill 2':
            if ($_SESSION['PVE_User_Pet_Level'] >= 7) {
                $Battle_Results = $UserPet->PVE_Attack($_SESSION['PVE_User_Pet_Skill_2']);
            }
            break;

        case 'Skill 3':
            if ($_SESSION['PVE_User_Pet_Level'] >= 15) {
                $Battle_Results = $UserPet->PVE_Attack($_SESSION['PVE_User_Pet_Skill_3']);
            }
            break;

        case 'Retreat':
            $UserPet->Clear_Battle_Room_PVE();
            break;

        case 'Defend':
            $Battle_Results = $UserPet->PVE_Attack("Defend");
            break;

        case 'Catch':
            $UserPet->PVE_Catch_Pet();
            break;
    }

?>
<div class='ContentHeader'>Fight a Wild Pet <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div><br><hr><br>

<?php
if (!isset($_SESSION['PVE_User_Pet_ID'])) {

    echo '<form action="?view='. $View . '" method="post">';
    echo '<center><button title="This will start a new battle." type="submit" name="Action" value="StartBattle" style="height:100px; width:20%">Start Battle</button></center>';
    echo '</form>';

} else {

?>

<?php
        echo "<b>[ " . $_SESSION['PVE_AI_Pet_Name'] . " ] AI</b>";
        echo "<div class='BlackBox'>";
            echo "<table width='100%'>";
              echo "<tr>";
                echo "<td>";
                  echo "<img height='65' width='65' src='".$_SESSION['PVE_AI_Pet_Image'] ."'>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Level</b>: " . $_SESSION['PVE_AI_Pet_Level'] . "<br>";
                  echo "<b>EXP</b>: " . $_SESSION['PVE_AI_Pet_Exp'] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Offense</b>: " . $_SESSION['PVE_AI_Pet_Offense'] . "<br>";
                  echo "<b>Defense</b>: " . $_SESSION['PVE_AI_Pet_Defense'] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Health</b>: " . $_SESSION['PVE_AI_Pet_Current_Health'] . " / " . $_SESSION['PVE_AI_Pet_Max_Health']  . "<br>";
                  echo "<b>Action Points</b>: " . $_SESSION['PVE_AI_Pet_Current_AP'] . " / " . $_SESSION['PVE_AI_Pet_Max_AP']  . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Skill</b> 1: " . $_SESSION['PVE_AI_Pet_Skill_1'] . "<br>";
                  echo "<b>Skill</b> 2: " . $_SESSION['PVE_AI_Pet_Skill_2'] . "<br>";
                  echo "<b>Skill</b> 3: " . $_SESSION['PVE_AI_Pet_Skill_3'] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Type</b>: " . $_SESSION['PVE_AI_Pet_Type'] . "<br>";
                echo "</td>";
              echo "</tr>";
            echo "</table>";
        echo "</div>";
        echo "<br>";
?>

<div class='PetBattleScreen'>
    <div class='PetBattleAttacker'>
        <img src='<?php echo $_SESSION['PVE_User_Pet_Image'] ?>'>
    </div>
    <div class='PetBattleDefender'>
        <img src='<?php echo $_SESSION['PVE_AI_Pet_Image'] ?>'>
    </div>
</div>

<?php
        echo "<b>[ " . $_SESSION['PVE_User_Pet_Name'] . " ] ".$User->Username."</b>";
        echo "<div class='BlackBox'>";
            echo "<table width='100%'>";
              echo "<tr>";
                echo "<td>";
                  echo "<img height='65' width='65' src='".$_SESSION['PVE_User_Pet_Image'] ."'>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Level</b>: " . $_SESSION['PVE_User_Pet_Level'] . "<br>";
                  echo "<b>EXP</b>: " . $_SESSION['PVE_User_Pet_Exp'] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Offense</b>: " . $_SESSION['PVE_User_Pet_Offense'] . "<br>";
                  echo "<b>Defense</b>: " . $_SESSION['PVE_User_Pet_Defense'] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Health</b>: " . $_SESSION['PVE_User_Pet_Current_Health'] . " / " . $_SESSION['PVE_User_Pet_Max_Health']  . "<br>";
                  echo "<b>Action Points</b>: " . $_SESSION['PVE_User_Pet_Current_AP'] . " / " . $_SESSION['PVE_User_Pet_Max_AP']  . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Skill</b> 1: " . $_SESSION['PVE_User_Pet_Skill_1'] . "<br>";
                  echo "<b>Skill</b> 2: " . $_SESSION['PVE_User_Pet_Skill_2'] . "<br>";
                  echo "<b>Skill</b> 3: " . $_SESSION['PVE_User_Pet_Skill_3'] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Type</b>: " . $_SESSION['PVE_User_Pet_Type'] . "<br>";
                echo "</td>";
              echo "</tr>";
            echo "</table>";
        echo "</div>";
?>

<br>

<?php
if ($Battle_Results) {
    echo "<div class='LogGreen'>";
    echo $Battle_Results['UserAction'];
    echo "</div>";
    echo "<br>";
    echo "<div class='LogRed'>";
    echo $Battle_Results['AIAction'];
    echo "</div>";
}
?>

<br>

<form action='?view=<?php echo $View; ?>' method='post'>
<table style="width:100%">
    <tr>
        <td>
            <center>
                <button  <?php if ($_SESSION['PVE_User_Pet_Skill_1_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVE_User_Pet_Skill_1_Cooldown'];} else { echo 'title="This will trigger your 1st skill."'; }?> type="submit" name="Action" value="Skill 1" style="height:100px; width:20%"><?php echo $_SESSION['PVE_User_Pet_Skill_1'] . " <br> " . $_SESSION['PVE_User_Pet_Skill_1_Cooldown'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_1_Type']; ?></button>
                <button  <?php if ($_SESSION['PVE_User_Pet_Level'] < 7) { echo "disabled "; echo 'title="This is disabled until your pet gets to level 7."'; } else { if ($_SESSION['PVE_User_Pet_Skill_2_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVE_User_Pet_Skill_2_Cooldown'];} else { echo 'title="This will trigger your 2nd skill."'; }}?> type="submit" name="Action" value="Skill 2" style="height:100px; width:20%"><?php echo $_SESSION['PVE_User_Pet_Skill_2'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_2_Cooldown'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_2_Type'];; ?></button>
                <button  <?php if ($_SESSION['PVE_User_Pet_Level'] < 15) { echo "disabled "; echo 'title="This is disabled until your pet gets to level 15."'; } else { if ($_SESSION['PVE_User_Pet_Skill_3_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVE_User_Pet_Skill_3_Cooldown'];} else {  echo 'title="This will trigger your 3rd skill."'; }}?> type="submit" name="Action" value="Skill 3" style="height:100px; width:20%"><?php echo $_SESSION['PVE_User_Pet_Skill_3'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_3_Cooldown'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_3_Type'];; ?></button>
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
                <input title="Raises your defense by 50% for the next round." type="submit" name="Action" value="Defend"  style="height:100px; width:20%" />
                <input title="Trys to catch the pet, higher chance if the pet is under 45% life. If you don't catch it the battle ends." type="submit" name="Action" value="Catch"  style="height:100px; width:20%" />
                <input title="Leaves the battle, no exp is gained." type="submit" name="Action" value="Retreat"  style="height:100px; width:20%" />
            </center>
        </td>
    </tr>
</table>
<?php } ?>
