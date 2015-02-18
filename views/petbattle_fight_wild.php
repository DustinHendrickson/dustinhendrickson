<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$UserPet = new BattlePet($_SESSION['ID']);
$View = Functions::Get_View();

    switch ($_POST['Action'])
    {
        case 'StartBattle':
            if ($UserPet->Pet_Current_AP > 0) {
                $UserPet->Save_AP($UserPet->Pet_Current_AP - 1, $UserPet->Pet_ID);
                $UserPet->Create_Battle_Room('PVE');
            } else {
                Toasts::addNewToast("You don't have enough AP to fight!", 'petbattle');
            }
            break;

        case 'Skill 1':
            $Battle_Results = $UserPet->Attack($_SESSION['PVE_User_Pet_Skill_1'], 'PVE');
            break;

        case 'Skill 2':
            if ($_SESSION['PVE_User_Pet_Level'] >= 3) {
                $Battle_Results = $UserPet->Attack($_SESSION['PVE_User_Pet_Skill_2'], 'PVE');
            }
            break;

        case 'Skill 3':
            if ($_SESSION['PVE_User_Pet_Level'] >= 10) {
                $Battle_Results = $UserPet->Attack($_SESSION['PVE_User_Pet_Skill_3'], 'PVE');
            }
            break;

        case 'Retreat':
            $UserPet->Save_AP($UserPet->Pet_Current_AP - 1, $UserPet->Pet_ID);
            Toasts::addNewToast("You just ran away!<br>-1 AP", 'petbattle');
            $UserPet->Clear_Battle_Room('PVE');
            break;

        case 'Defend':
            $Battle_Results = $UserPet->Attack("Defend", 'PVE');
            break;

        case 'Catch':
            $UserPet->PVE_Catch_Pet();
            break;
    }

if (!isset($_SESSION['PVE_User_Pet_ID'])) {
    echo "<div class='ContentHeader'>Fight Wild Pet <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div><br><hr><br>";
    echo '<form action="?view='. $View . '" method="post">';
    echo '<center><button title="This will start a new battle." type="submit" name="Action" value="StartBattle" style="height:100px; width:20%">Start Battle</button></center>';
    echo '</form>';
    echo '</div>';

} else {

?>

<?php
        echo "<table background='img/SubtleGrey_Light.png' width='100%'>";
        echo "<tr>";
        echo "<td width='3px;' style='background-color: green;'> </td>";
            echo "<td colspan='6'>";
                echo "<b>[ " . $_SESSION['PVE_User_Pet_Name'] . " ] ".$User->Username."</b>";
            echo "</td>";
            echo "<td width='3px;' style='background-color: red;'> </td>";
            echo "<td colspan='6'>";
                echo "<b>[ " . $_SESSION['PVE_AI_Pet_Name'] . " ] AI</b>";
            echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td width='3px;' style='background-color: green;'> </td>";
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
                echo "<b>HP</b>: " . $_SESSION['PVE_User_Pet_Current_Health'] . "/" . $_SESSION['PVE_User_Pet_Max_Health']  . "<br>";
                echo "<b>AP</b>: " . $_SESSION['PVE_User_Pet_Current_AP'] . "/" . $_SESSION['PVE_User_Pet_Max_AP']  . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Skill</b> 1: " . $_SESSION['PVE_User_Pet_Skill_1'] . "<br>";
                echo "<b>Skill</b> 2: " . $_SESSION['PVE_User_Pet_Skill_2'] . "<br>";
                echo "<b>Skill</b> 3: " . $_SESSION['PVE_User_Pet_Skill_3'] . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Type</b>: " . $_SESSION['PVE_User_Pet_Type'] . "<br>";
            echo "</td>";

echo "<td width='3px;' style='background-color: red;'> </td>";

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
                echo "<b>HP</b>: " . $_SESSION['PVE_AI_Pet_Current_Health'] . "/" . $_SESSION['PVE_AI_Pet_Max_Health']  . "<br>";
                echo "<b>AP</b>: " . $_SESSION['PVE_AI_Pet_Current_AP'] . "/" . $_SESSION['PVE_AI_Pet_Max_AP']  . "<br>";
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

        // HERE WE SHOW THE HEALTHBARS
        $UserPetHealthBarSize = ($_SESSION['PVE_User_Pet_Current_Health'] / $_SESSION['PVE_User_Pet_Max_Health']) * 100;
        $AIPetHealthBarSize = ($_SESSION['PVE_AI_Pet_Current_Health'] / $_SESSION['PVE_AI_Pet_Max_Health']) * 100;
        $UserPetHealthBarSize = number_format($UserPetHealthBarSize);
        $AIPetHealthBarSize = number_format($AIPetHealthBarSize);

        echo "<tr>";
            echo "<td width='3px;' style='background-color: green;'> </td>";
            echo "<td align='right'> <b>{$UserPetHealthBarSize}%</b> </td>";
            echo "<td colspan='5' bgcolor='#000000'>";
            echo "<img height='16' width='{$UserPetHealthBarSize}%' src='../img/greensquare.png'>";
            echo "</td>";

            echo "<td width='3px;' style='background-color: red;'> </td>";
            echo "<td align='right'> <b>{$AIPetHealthBarSize}%</b>  </td>";
            echo "<td colspan='5' bgcolor='#000000'>";
            echo "<img height='16' width='{$AIPetHealthBarSize}%' src='../img/redsquare.png'>";
            echo "</td>";
        echo "</tr>";

        echo "<tr height='35px'>";
          echo "<td width='3px;' style='background-color: green;'> </td>";

            if (isset($_SESSION['PVE_User_Pet_Buffs'])) {
                echo "<td><b>Buffs |</b></td>";
                echo "<td colspan='5'>";
                foreach($_SESSION['PVE_User_Pet_Buffs'] as $Buff ) {
                    $Duration = $_SESSION['PVE_User_Pet_Buffs_'.$Buff.'_Duration'];
                    echo  "<img height='25px' width='25px' alt='{$Buff} has {$Duration} turns left.' src='petbattles/images/icons/" . $Buff . ".png'>{$Duration}</img>";
                }
                echo "</td>";
            }

              echo "</td>";
              echo "<td width='3px;' style='background-color: red;'> </td>";

              if (isset($_SESSION['PVE_AI_Pet_Buffs'])) {
                    echo "<td><b>Buffs |</b></td>";
                    echo "<td colspan='5'>";
                    foreach($_SESSION['PVE_AI_Pet_Buffs'] as $Buff ) {
                        $Duration = $_SESSION['PVE_AI_Pet_Buffs_'.$Buff.'_Duration'];
                        echo  "<img height='25px' width='25px' alt='{$Buff} has {$Duration} turns left.' src='petbattles/images/icons/" . $Buff . ".png'>{$Duration}</img>";
                    }
                    echo "</td>";
              }

                echo "</td>";
                echo "</tr>";
        echo "</table>";
?>


<!-- <div class='PetBattleScreen'>
    <div class='PetBattleAttacker'>
        <img height='120px' width='120px' src='<?php echo $_SESSION['PVE_User_Pet_Image'] ?>'>
    </div>
    <div class='PetBattleDefender'>
        <img src='<?php echo $_SESSION['PVE_AI_Pet_Image'] ?>'>
    </div>
</div> -->



<br><br>

<?php
if ($Battle_Results) {
    echo "<b>Round Results</b>";
    echo "<div class='BlackBox'>";
        echo "<div class='LogGreen'>";
            echo $Battle_Results['UserAction'];
        echo "</div>";
        echo "<br>";
        echo "<div class='LogRed'>";
            echo $Battle_Results['AIAction'];
        echo "</div>";
    echo "</div>";
}
?>

<br>

<form action='?view=<?php echo $View; ?>' method='post'>
<table style="width:100%">
    <tr>
        <td>
            <center>
                <button  <?php if ($_SESSION['PVE_User_Pet_Skill_1_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVE_User_Pet_Skill_1_Cooldown'];} else { echo 'title="This will trigger your 1st skill."'; }?> type="submit" name="Action" value="Skill 1" style="height:100px; width:20%"><?php echo $_SESSION['PVE_User_Pet_Skill_1'] . " <br> " . $_SESSION['PVE_User_Pet_Skill_1_Cooldown'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_1_Type'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_1_Effect']; ?></button>
                <button  <?php if ($_SESSION['PVE_User_Pet_Level'] < 3) { echo "disabled "; echo 'title="This is disabled until your pet gets to level 3."'; } else { if ($_SESSION['PVE_User_Pet_Skill_2_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVE_User_Pet_Skill_2_Cooldown'];} else { echo 'title="This will trigger your 2nd skill."'; }}?> type="submit" name="Action" value="Skill 2" style="height:100px; width:20%"><?php echo $_SESSION['PVE_User_Pet_Skill_2'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_2_Cooldown'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_2_Type'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_2_Effect'];; ?></button>
                <button  <?php if ($_SESSION['PVE_User_Pet_Level'] < 10) { echo "disabled "; echo 'title="This is disabled until your pet gets to level 10"'; } else { if ($_SESSION['PVE_User_Pet_Skill_3_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVE_User_Pet_Skill_3_Cooldown'];} else {  echo 'title="This will trigger your 3rd skill."'; }}?> type="submit" name="Action" value="Skill 3" style="height:100px; width:20%"><?php echo $_SESSION['PVE_User_Pet_Skill_3'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_3_Cooldown'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_3_Type'] . "<br>" . $_SESSION['PVE_User_Pet_Skill_3_Effect'];; ?></button>
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
                <input title="Raises your defense by 50% for the next round." type="submit" name="Action" value="Defend"  style="height:100px; width:20%" />
                <input title="Trys to catch the pet, higher chance the lower the pets life. If you don't catch it the battle ends." type="submit" name="Action" value="Catch"  style="height:100px; width:20%" />
                <input title="Leaves the battle, no exp is gained and you lose 1 AP." type="submit" name="Action" value="Retreat"  style="height:100px; width:20%" />
            </center>
        </td>
    </tr>
</table>

<br>
<b>Effects Legend</b>
<a id="ToggleLegend" href="javascript:ToggleDiv('Legend','ToggleLegend');" >+ Show Contents</a>
<div id="Legend" style='display: none;'>
<div class='BlackBox'>
    <table width='100%'>
        <tr>
            <td>
                <b>Beneficial Buffs</b>
            </td>
            <td>
                <b>Name</b>
            </td>
            <td>
                <b>Duration</b>
            </td>
            <td>
                <b>Description</b>
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Thorns.png'>
            </td>
            <td>
                Thorns
            </td>
            <td>
                2 turns
            </td>
            <td>
                You return 10% of damage taken. Ignores armor.
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Focus.png'>
            </td>
            <td>
                Focus
            </td>
            <td>
                2 turns
            </td>
            <td>
                Increases your pet's chance to hit by 20%.
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Heal.png'>
            </td>
            <td>
                Heal
            </td>
            <td>
                2 turns
            </td>
            <td>
                Heals your pet for 5% of it's max life per turn.
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Frenzy.png'>
            </td>
            <td>
                Frenzy
            </td>
            <td>
                2 turns
            </td>
            <td>
                Increases your offense by 25%.
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Armor.png'>
            </td>
            <td>
                Armor
            </td>
            <td>
                2 turns
            </td>
            <td>
                Increases defense by 20%.
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Evasion.png'>
            </td>
            <td>
                Evasion
            </td>
            <td>
                2 turns
            </td>
            <td>
                Decreases your chance to be hit by 20%.
            </td>
        </tr>

        <tr>
            <td>
                <b>Harmful Buffs</b>
            </td>
            <td>
                <b>Name</b>
            </td>
            <td>
                <b>Duration</b>
            </td>
            <td>
                <b>Description</b>
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Wound.png'>
            </td>
            <td>
                Wound
            </td>
            <td>
                2 turns
            </td>
            <td>
                Your pet takes 35% more damage from attacks.
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Blind.png'>
            </td>
            <td>
                Blind
            </td>
            <td>
                2 turns
            </td>
            <td>
                Your pet's chance to hit is decreased by 20%
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Poison.png'>
            </td>
            <td>
                Poison
            </td>
            <td>
                2 turns
            </td>
            <td>
                Your pet takes 15% of their Current Health as damage every turn.
            </td>
        </tr>
        <tr>
            <td>
                <img src='petbattles/images/icons/Stun.png'>
            </td>
            <td>
                Stun
            </td>
            <td>
                1 turns
            </td>
            <td>
                Your pet cannot perform any actions.
            </td>
        </tr>

    </table>
    </div>
</div>
<?php } ?>
