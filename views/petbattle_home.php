<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$Pet = new BattlePet($_SESSION['ID']);

$View = Functions::Get_View();

$Pet->Give_Daily_Quest();


switch ($_POST['Action'])
    {
        case 'Fight Wild Pet':
            header('Location: ?view=petbattle_fight_wild');
            break;
        case 'Fight Boss':
            header('Location: ?view=petbattle_fight_boss');
            break;
        case 'Fight User Pet':
            header('Location: ?view=petbattle_fight_user');
            break;
        case 'Set Active Pet':
            header('Location: ?view=petbattle_set_active_pet');
            break;
        case 'Pet Shop':
            header('Location: ?view=petbattle_pet_shop');
            break;
        case 'Pet Collection':
            header('Location: ?view=petbattle_collection');
            break;
        case 'Leaderboards':
            header('Location: ?view=petbattle_leaderboards');
            break;
        case 'Daily Quests':
            header('Location: ?view=petbattle_dailyquests');
            break;
        case 'Give New Pet':
            $Pet->Give_Random_Pet();
            break;
    }

?>
<div class='ContentHeader'>Pet Battles Homescreen</div>
<hr>
<br>
 <b>Current Active Pet</b><br>

<?php
    $ActivePet = $Pet->Get_Active_Pet();
    if ($ActivePet) {
        echo "<b>[ " . $ActivePet["Pet_Name"] . " ]</b>";
        echo "<div class='PetBlackBox'>";
            echo "<table width='100%'>";
              echo "<tr>";
                echo "<td>";
                  echo "<img height='65' width='65' src='petbattles/images/".$ActivePet["Pet_Image"] ."'>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Level</b>: " . $ActivePet["Pet_Level"] . "<br>";
                  echo "<b>EXP</b>: " . $ActivePet["Pet_Exp"] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Offense</b>: " . $ActivePet["Pet_Offense"] . "<br>";
                  echo "<b>Defense</b>: " . $ActivePet["Pet_Defense"] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Health</b>: " . $ActivePet["Pet_Current_Health"] . " / " . $ActivePet["Pet_Max_Health"]  . "<br>";
                  echo "<b>Action Points</b>: " . $ActivePet["Pet_Current_AP"] . " / " . $ActivePet["Pet_Max_AP"]  . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Skill</b> 1: " . $ActivePet["Pet_Skill_1"] . "<br>";
                  echo "<b>Skill</b> 2: " . $ActivePet["Pet_Skill_2"] . "<br>";
                  echo "<b>Skill</b> 3: " . $ActivePet["Pet_Skill_3"] . "<br>";
                echo "</td>";
                echo "<td>";
                  echo "<b>Type</b>: " . $ActivePet["Pet_Type"] . "<br>";
                  echo "<b>Tier</b>: " . $ActivePet["Pet_Tier"] . "<br>";
                echo "</td>";
              echo "</tr>";

                $UserPetAPBarSize = ($ActivePet["Pet_Current_AP"] / $ActivePet["Pet_Max_AP"]) * 100;
                $UserPetAPBarSize = number_format($UserPetAPBarSize);
                if ($UserPetAPBarSize > 100) { $UserPetAPBarSize = 100; }

                $UserPetExpBarSize = ($ActivePet["Pet_Exp"] / 100) * 100;
                $UserPetExpBarSize = number_format($UserPetExpBarSize);

            echo "<tr>";
                echo "<td><b>AP</b></td>";
                echo "<td colspan='5' bgcolor='#000000'>";
                echo "<img height='16' width='{$UserPetAPBarSize}%' src='../img/orangesquare.png'>";
                echo "</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td><b>EXP</b></td>";
                echo "<td colspan='5' bgcolor='#000000'>";
                echo "<img height='16' width='{$UserPetExpBarSize}%' src='../img/purplesquare.png'>";
                echo "</td>";
            echo "</tr>";


            echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='BlackBox'>";
        echo "Looks like you don't have a pet set as your active pet yet, look below to set your active pet.<br> You will be unable to fight until you do so.";
        echo "</div>";
    }
?>
<br>

<form action='?view=<?php echo $View; ?>' method='post'>
<table style="width:100%">
    <tr>
        <td>
            <center>
                <input <?php if (!$ActivePet) {echo "disabled";} ?> type="submit" name="Action" value="Fight Wild Pet"  style="height:100px; width:30%" />
                <input <?php if (!$ActivePet) {echo "disabled";} ?> type="submit" name="Action" value="Fight User Pet"  style="height:100px; width:30%" />
                <input type="submit" name="Action" value="Set Active Pet"  style="height:100px; width:30%" />
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
                <input type="submit" name="Action" value="Pet Shop"  style="height:100px; width:30%" />
                <input type="submit" name="Action" value="Pet Collection"  style="height:100px; width:30%" />
                <input type="submit" name="Action" value="Leaderboards"  style="height:100px; width:30%" />
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
                <input type="submit" name="Action" value="Daily Quests"  style="height:100px; width:91%" />
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
            <?php
            if ($_SESSION["ID"] == 1) {
                echo '<input type="submit" name="Action" value="Give New Pet"  style="height:50px; width:150px; color:red; " />';
                //echo '<input type="submit" name="Action" value="Give New Daily"  style="height:50px; width:150px; color:red; " />';
            }
            ?>
            <?php
            if ($Pet->Pet_Level == 20) {
                echo '<input type="submit" name="Action" value="Fight Boss"  style="height:50px; width:150px; color:red; " />';
            }
            ?>
            </center>
        </td>
    </tr>


</table>

<br>

<center>
<table style="width:50%">
    <tr>
        <td>
            <center>
                <tr>
                    <td>
                        Number of pets owned:
                    </td>
                    <td>
                        <?php echo $Pet->Get_Total_Pet_Count(); ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        Number of pets alive:
                    </td>
                    <td>
                        <?php echo $Pet->Get_Total_Alive_Pet_Count(); ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        Number of pets caught:
                    </td>
                    <td>
                        <?php echo $User->Pets_Caught; ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        Number of pet battle wins:
                    </td>
                    <td>
                        <?php echo $User->Pet_Battles_Won; ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        Number of pet battle losses:
                    </td>
                    <td>
                        <?php echo $User->Pet_Battles_Lost; ?>
                    </td>
                </tr>

            </center>
        </td>
    </tr>
</table>
</center>
<br>
