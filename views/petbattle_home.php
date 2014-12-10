<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$Pet = new BattlePet($_SESSION['ID']);

$View = Functions::Get_View();

switch ($_POST['Action'])
    {
        case 'Fight Wild Pet':
            header('Location: ?view=petbattle_fight_wild');
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
        case 'Give New Pet':
            $Pet->Give_Random_Pet();
            break;
    }

?>
<div class='ContentHeader'>Pet Battles Homescreen</div>
<hr>
<br>
 <b>Current Active Pet</b>
<div class='BlackBox'>
<?php
    $ActivePet = $Pet->Get_Active_Pet();
    if ($ActivePet) {
        echo "Name: " . $ActivePet["Pet_Name"] . "<br>";
        echo "Level: " . $ActivePet["Pet_Level"] . "<br>";
        echo "Health : " . $ActivePet["Pet_Current_Health"] . " / " . $ActivePet["Pet_Max_Health"]  . "<br>";
        echo "Action Points : " . $ActivePet["Pet_Current_AP"] . " / " . $ActivePet["Pet_Max_AP"]  . "<br>";
    } else {
        echo "Looks like you don't have a pet set as your active pet yet, look below to set your active pet.";
    }
?>
</div>
<br>

<form action='?view=<?php echo $View; ?>' method='post'>
<table style="width:100%">
    <tr>
        <td>
            <center>
                <input type="submit" name="Action" value="Fight Wild Pet"  style="height:200px; width:30%" />
                <input type="submit" name="Action" value="Fight User Pet"  style="height:200px; width:30%" />
                <input type="submit" name="Action" value="Set Active Pet"  style="height:200px; width:30%" />
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
                <input type="submit" name="Action" value="Pet Shop"  style="height:200px; width:30%" />
                <input type="submit" name="Action" value="Pet Collection"  style="height:200px; width:30%" />
                <input type="submit" name="Action" value="Leaderboards"  style="height:200px; width:30%" />
            </center>
        </td>
    </tr>
    <tr>
        <td>
            <center>
            <?php
            if ($_SESSION["ID"] == 1) {
                echo '<input type="submit" name="Action" value="Give New Pet"  style="height:50px; width:150px; color:red; " />';
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
                        0
                    </td>
                </tr>

                <tr>
                    <td>
                        Number of pet battle wins:
                    </td>
                    <td>
                        0
                    </td>
                </tr>

                <tr>
                    <td>
                        Number of pet battle losses:
                    </td>
                    <td>
                        0
                    </td>
                </tr>

            </center>
        </td>
    </tr>
</table>
</center>
<br>
