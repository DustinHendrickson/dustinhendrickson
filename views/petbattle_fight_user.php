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
                $UserPet->Create_Battle_Room('PVP', $_POST['User_ID'], $_POST['Pet_ID']);
            } else {
                Toasts::addNewToast("You don't have enough AP to fight!", 'petbattle');
            }
            break;

        case 'Skill 1':
            $Battle_Results = $UserPet->Attack($_SESSION['PVP_User_Pet_Skill_1'], 'PVP');
            break;

        case 'Skill 2':
            if ($_SESSION['PVP_User_Pet_Level'] >= 3) {
                $Battle_Results = $UserPet->Attack($_SESSION['PVP_User_Pet_Skill_2'], 'PVP');
            }
            break;

        case 'Skill 3':
            if ($_SESSION['PVP_User_Pet_Level'] >= 10) {
                $Battle_Results = $UserPet->Attack($_SESSION['PVP_User_Pet_Skill_3'], 'PVP');
            }
            break;

        case 'Retreat':
            $UserPet->Save_AP($UserPet->Pet_Current_AP - 1, $UserPet->Pet_ID);
            Toasts::addNewToast("You just ran away!<br>-1 AP", 'petbattle');
            $UserPet->Clear_Battle_Room('PVP');
            break;

        case 'Defend':
            $Battle_Results = $UserPet->Attack("Defend", 'PVP');
            break;

    }

if (!isset($_SESSION['PVP_User_Pet_ID'])) {
    echo "<div class='ContentHeader'>Fight User Pet <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div><br><hr><br>";

    $Results = $UserPet->Get_All_Enemy_Pets();

    if (!$Results) {
        echo "There are currently no User Pets able to be attacked. Try again later.<br>";
    } else {

    foreach ($Results as $Pet) {
        $AIUser = new User($Pet['User_ID']);
        echo "<b>" . $AIUser->Username . " - ". $Pet["Pet_Name"] . "</b>";
        echo "<div class='BlackBox'>";
        echo '<form action="?view='. $View . '" method="post">';
        echo "<table width='100%'>";
          echo "<tr>";
            echo "<td>";
              echo "<img height='65' width='65' src='petbattles/images/".$Pet["Pet_Image"] ."'>";
            echo "</td>";
            echo "<td>";
              echo "<b>Level</b>: " . $Pet["Pet_Level"] . "<br>";
              echo "<b>EXP</b>: " . $Pet["Pet_Exp"] . "<br>";
            echo "</td>";
            echo "<td>";
              echo "<b>Offense</b>: " . $Pet["Pet_Offense"] . "<br>";
              echo "<b>Defense</b>: " . $Pet["Pet_Defense"] . "<br>";
            echo "</td>";
            echo "<td>";
              echo "<b>HP</b>: " . $Pet["Pet_Current_Health"] . " / " . $Pet["Pet_Max_Health"]  . "<br>";
              echo "<b>AP</b>: " . $Pet["Pet_Current_AP"] . " / " . $Pet["Pet_Max_AP"]  . "<br>";
            echo "</td>";
            echo "<td>";
              echo "<b>Skill</b> 1: " . $Pet["Pet_Skill_1"] . "<br>";
              echo "<b>Skill</b> 2: " . $Pet["Pet_Skill_2"] . "<br>";
              echo "<b>Skill</b> 3: " . $Pet["Pet_Skill_3"] . "<br>";
            echo "</td>";
            echo "<td>";
              echo "<b>Type</b>: " . $Pet["Pet_Type"] . "<br>";
              echo "<b>Tier</b>: " . $Pet["Pet_Tier"] . "<br>";
              echo "<b>Status</b>: " . $Pet["Pet_Status"] . "<br>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='6' bgcolor='black'>";
            echo "<center>";
              echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
              echo "<input type='hidden' value='".$Pet["User_ID"]."' name='User_ID' />";
              echo '<center><button title="This will start a new battle." type="submit" name="Action" value="StartBattle" style="height:25px; width:20%">Start Battle</button></center>';
            echo "</center>";
            echo "</td>";
          echo "</tr>";
        echo "</table>";
        echo '</form>';
        echo '</div>';
    }
    }

} else {

?>

<?php
        echo "<table width='100%'>";
        echo "<tr>";
        echo "<td width='3px;' style='background-color: green;'> </td>";
            echo "<td colspan='6'>";
                echo "<b>[ " . $_SESSION['PVP_User_Pet_Name'] . " ] ".$User->Username."</b>";
            echo "</td>";
            echo "<td width='3px;' style='background-color: red;'> </td>";
            echo "<td colspan='6'>";
                echo "<b>[ " . $_SESSION['PVP_AI_Pet_Name'] . " ] ".$_SESSION['PVP_AI_Username']."</b>";
            echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td width='3px;' style='background-color: green;'> </td>";
            echo "<td>";
                echo "<img height='65' width='65' src='".$_SESSION['PVP_User_Pet_Image'] ."'>";
            echo "</td>";
            echo "<td>";
                echo "<b>Level</b>: " . $_SESSION['PVP_User_Pet_Level'] . "<br>";
                echo "<b>EXP</b>: " . $_SESSION['PVP_User_Pet_Exp'] . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Offense</b>: " . $_SESSION['PVP_User_Pet_Offense'] . "<br>";
                echo "<b>Defense</b>: " . $_SESSION['PVP_User_Pet_Defense'] . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>HP</b>: " . $_SESSION['PVP_User_Pet_Current_Health'] . " / " . $_SESSION['PVP_User_Pet_Max_Health']  . "<br>";
                echo "<b>AP</b>: " . $_SESSION['PVP_User_Pet_Current_AP'] . " / " . $_SESSION['PVP_User_Pet_Max_AP']  . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Skill</b> 1: " . $_SESSION['PVP_User_Pet_Skill_1'] . "<br>";
                echo "<b>Skill</b> 2: " . $_SESSION['PVP_User_Pet_Skill_2'] . "<br>";
                echo "<b>Skill</b> 3: " . $_SESSION['PVP_User_Pet_Skill_3'] . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Type</b>: " . $_SESSION['PVP_User_Pet_Type'] . "<br>";
            echo "</td>";

echo "<td width='3px;' style='background-color: red;'> </td>";

            echo "<td>";
                echo "<img height='65' width='65' src='".$_SESSION['PVP_AI_Pet_Image'] ."'>";
            echo "</td>";
            echo "<td>";
                echo "<b>Level</b>: " . $_SESSION['PVP_AI_Pet_Level'] . "<br>";
                echo "<b>EXP</b>: " . $_SESSION['PVP_AI_Pet_Exp'] . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Offense</b>: " . $_SESSION['PVP_AI_Pet_Offense'] . "<br>";
                echo "<b>Defense</b>: " . $_SESSION['PVP_AI_Pet_Defense'] . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Health</b>: " . $_SESSION['PVP_AI_Pet_Current_Health'] . "/" . $_SESSION['PVP_AI_Pet_Max_Health']  . "<br>";
                echo "<b>AP</b>: " . $_SESSION['PVP_AI_Pet_Current_AP'] . "/" . $_SESSION['PVP_AI_Pet_Max_AP']  . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Skill</b> 1: " . $_SESSION['PVP_AI_Pet_Skill_1'] . "<br>";
                echo "<b>Skill</b> 2: " . $_SESSION['PVP_AI_Pet_Skill_2'] . "<br>";
                echo "<b>Skill</b> 3: " . $_SESSION['PVP_AI_Pet_Skill_3'] . "<br>";
            echo "</td>";
            echo "<td>";
                echo "<b>Type</b>: " . $_SESSION['PVP_AI_Pet_Type'] . "<br>";
            echo "</td>";

        // HERE WE SHOW THE HEALTHBARS
        $UserPetHealthBarSize = ($_SESSION['PVP_User_Pet_Current_Health'] / $_SESSION['PVP_User_Pet_Max_Health']) * 100;
        $AIPetHealthBarSize = ($_SESSION['PVP_AI_Pet_Current_Health'] / $_SESSION['PVP_AI_Pet_Max_Health']) * 100;
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

            if (isset($_SESSION['PVP_User_Pet_Buffs'])) {
                echo "<td><b>Buffs |</b></td>";
                echo "<td colspan='5'>";
                foreach($_SESSION['PVP_User_Pet_Buffs'] as $Buff ) {
                    $Duration = $_SESSION['PVP_User_Pet_Buffs_'.$Buff.'_Duration'];
                    echo  "<img height='25px' width='25px' alt='{$Buff} has {$Duration} turns left.' src='petbattles/images/icons/" . $Buff . ".png'>{$Duration}</img>";
                }
                echo "</td>";
            }

              echo "</td>";
              echo "<td width='3px;' style='background-color: red;'> </td>";

              if (isset($_SESSION['PVP_AI_Pet_Buffs'])) {
                    echo "<td><b>Buffs |</b></td>";
                    echo "<td colspan='5'>";
                    foreach($_SESSION['PVP_AI_Pet_Buffs'] as $Buff ) {
                        $Duration = $_SESSION['PVP_AI_Pet_Buffs_'.$Buff.'_Duration'];
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
        <img height='120px' width='120px' src='<?php echo $_SESSION['PVP_User_Pet_Image'] ?>'>
    </div>
    <div class='PetBattleDefender'>
        <img src='<?php echo $_SESSION['PVP_AI_Pet_Image'] ?>'>
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
                <button  <?php if ($_SESSION['PVP_User_Pet_Skill_1_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVP_User_Pet_Skill_1_Cooldown'];} else { echo 'title="This will trigger your 1st skill."'; }?> type="submit" name="Action" value="Skill 1" style="height:100px; width:20%"><?php echo $_SESSION['PVP_User_Pet_Skill_1'] . " <br> " . $_SESSION['PVP_User_Pet_Skill_1_Cooldown'] . "<br>" . $_SESSION['PVP_User_Pet_Skill_1_Type'] . "<br>" . $_SESSION['PVP_User_Pet_Skill_1_Effect']; ?></button>
                <button  <?php if ($_SESSION['PVP_User_Pet_Level'] < 3) { echo "disabled "; echo 'title="This is disabled until your pet gets to level 3."'; } else { if ($_SESSION['PVP_User_Pet_Skill_2_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVP_User_Pet_Skill_2_Cooldown'];} else { echo 'title="This will trigger your 2nd skill."'; }}?> type="submit" name="Action" value="Skill 2" style="height:100px; width:20%"><?php echo $_SESSION['PVP_User_Pet_Skill_2'] . "<br>" . $_SESSION['PVP_User_Pet_Skill_2_Cooldown'] . "<br>" . $_SESSION['PVP_User_Pet_Skill_2_Type'] . "<br>" . $_SESSION['PVP_User_Pet_Skill_2_Effect'];; ?></button>
                <button  <?php if ($_SESSION['PVP_User_Pet_Level'] < 10) { echo "disabled "; echo 'title="This is disabled until your pet gets to level 10"'; } else { if ($_SESSION['PVP_User_Pet_Skill_3_Cooldown'] > 0) { echo "disabled "; echo 'title="This is still on cooldown - "' . $_SESSION['PVP_User_Pet_Skill_3_Cooldown'];} else {  echo 'title="This will trigger your 3rd skill."'; }}?> type="submit" name="Action" value="Skill 3" style="height:100px; width:20%"><?php echo $_SESSION['PVP_User_Pet_Skill_3'] . "<br>" . $_SESSION['PVP_User_Pet_Skill_3_Cooldown'] . "<br>" . $_SESSION['PVP_User_Pet_Skill_3_Type'] . "<br>" . $_SESSION['PVP_User_Pet_Skill_3_Effect'];; ?></button>
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
                <input title="Raises your defense by 50% for the next round." type="submit" name="Action" value="Defend"  style="height:100px; width:20%" />
                <input disabled title="You cannot catch a fellow trainers pet." type="submit" name="" value="Catch"  style="height:100px; width:20%" />
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
