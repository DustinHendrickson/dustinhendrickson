<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();
$Loaded_Pet = new BattlePet($_SESSION['ID']);

switch ($_POST['Mode'])
    {
        case 'Release':
            if ($_POST['Pet_ID']) {
              $Loaded_Pet->Release_Pet($_POST['Pet_ID']);
            }
            break;
        case 'Level Up':
            if ($_POST['Pet_ID']) {
                $Loaded_Pet->LevelUp_Pet($_POST['Pet_ID']);
            }
            break;
        case 'Exp 50':
            if ($_POST['Pet_ID']) {
                $Loaded_Pet->Give_Exp($Loaded_Pet->User_ID, $_POST['Pet_ID'], 50);
            }
            break;
        case 'Exp 250':
            if ($_POST['Pet_ID']) {
                $Loaded_Pet->Give_Exp($Loaded_Pet->User_ID, $_POST['Pet_ID'], 250);
            }
            break;
    }

switch ($_POST['UseItem'])
    {
        case 'Pet Food':
            if ($_POST['Item_ID']) {
              $Pet_Using_Item = new BattlePet($_SESSION['ID'], $_POST['Pet_ID']);
              $Pet_Using_Item->Add_AP(10, $_POST['Pet_ID']);
              $Pet_Using_Item->Remove_Item($_POST['Item_ID']);
              $Pet_Using_Item->Event_Item_Used($_POST['Item_ID']);
              Toasts::addNewToast("You used an item [".$_POST['UseItem']."] on pet [" .$Pet_Using_Item->Pet_Name . "]" , 'petbattle');
            }
            break;
        case 'Candy':
            if ($_POST['Item_ID']) {
              $Pet_Using_Item = new BattlePet($_SESSION['ID'], $_POST['Pet_ID']);
              $Pet_Using_Item->Add_AP(20, $_POST['Pet_ID']);
              $Pet_Using_Item->Remove_Item($_POST['Item_ID']);
              $Pet_Using_Item->Event_Item_Used($_POST['Item_ID']);
              Toasts::addNewToast("You used an item [".$_POST['UseItem']."] on pet [" .$Pet_Using_Item->Pet_Name . "]" , 'petbattle');
            }
            break;
        case 'Offense Training Book':
            if ($_POST['Item_ID']) {
              $Pet_Using_Item = new BattlePet($_SESSION['ID'], $_POST['Pet_ID']);
              $Pet_Using_Item->Add_Offense_To_Pet($_POST['Pet_ID'], 1);

              $Pet_Using_Item->Remove_Item($_POST['Item_ID']);
              $Pet_Using_Item->Event_Item_Used($_POST['Item_ID']);
              Toasts::addNewToast("You used an item [".$_POST['UseItem']."] on pet [" .$Pet_Using_Item->Pet_Name . "]" , 'petbattle');
            }
            break;
        case 'Defense Training Book':
            if ($_POST['Item_ID']) {
              $Pet_Using_Item = new BattlePet($_SESSION['ID'], $_POST['Pet_ID']);
              $Pet_Using_Item->Add_Defense_To_Pet($_POST['Pet_ID'], 1);

              $Pet_Using_Item->Remove_Item($_POST['Item_ID']);
              $Pet_Using_Item->Event_Item_Used($_POST['Item_ID']);
              Toasts::addNewToast("You used an item [".$_POST['UseItem']."] on pet [" .$Pet_Using_Item->Pet_Name . "]" , 'petbattle');
            }
            break;
        case 'Health Elixer':
            if ($_POST['Item_ID']) {
              $Pet_Using_Item = new BattlePet($_SESSION['ID'], $_POST['Pet_ID']);
              $Pet_Using_Item->Add_Max_Health_To_Pet($_POST['Pet_ID'], 5);

              $Pet_Using_Item->Remove_Item($_POST['Item_ID']);
              $Pet_Using_Item->Event_Item_Used($_POST['Item_ID']);
              Toasts::addNewToast("You used an item [".$_POST['UseItem']."] on pet [" .$Pet_Using_Item->Pet_Name . "]" , 'petbattle');
            }
            break;
        case 'Book of Experience':
            if ($_POST['Item_ID']) {
              $Pet_Using_Item = new BattlePet($_SESSION['ID'], $_POST['Pet_ID']);
              $Pet_Using_Item->Give_Exp($Loaded_Pet->User_ID, $_POST['Pet_ID'], 25);

              $Pet_Using_Item->Remove_Item($_POST['Item_ID']);
              $Pet_Using_Item->Event_Item_Used($_POST['Item_ID']);
              Toasts::addNewToast("You used an item [".$_POST['UseItem']."] on pet [" .$Pet_Using_Item->Pet_Name . "]" , 'petbattle');
            }
            break;
        case 'Mystic Candy':
            if ($_POST['Item_ID']) {
              $Pet_Using_Item = new BattlePet($_SESSION['ID'], $_POST['Pet_ID']);
              $Pet_Using_Item->LevelUp_Pet($_POST['Pet_ID']);

              $Pet_Using_Item->Remove_Item($_POST['Item_ID']);
              $Pet_Using_Item->Event_Item_Used($_POST['Item_ID']);
              Toasts::addNewToast("You used an item [".$_POST['UseItem']."] on pet [" .$Pet_Using_Item->Pet_Name . "]" , 'petbattle');
            }
            break;
        case 'Evolution Stone':
            if ($_POST['Item_ID']) {
              $Pet_Using_Item = new BattlePet($_SESSION['ID'], $_POST['Pet_ID']);
              $Pet_Tier = $Pet_Using_Item->Pet_Tier;
              $Pet_Level = $Pet_Using_Item->Pet_Level;

              if ($Pet_Tier == 1) {
                if ($Pet_Level >= 20) {
                  $Chance = 100;
                } else {
                  $Chance = $Pet_Level;
                }

                $Random = rand(1,100);

                if ($Random <= $Chance) {
                  $Pet_Using_Item->Evolve_Pet($Pet_Tier+1,$_POST['Pet_ID']);
                  Toasts::addNewToast("The Stone was a success!" , 'petbattle');
                } else {
                  Toasts::addNewToast("The Stone Failed!" , 'petbattle');
                }

                $Pet_Using_Item->Remove_Item($_POST['Item_ID']);
                $Pet_Using_Item->Event_Item_Used($_POST['Item_ID']);

                Toasts::addNewToast("You used an item [".$_POST['UseItem']."] on pet [" .$Pet_Using_Item->Pet_Name . "]" , 'petbattle');
              } else {
                Toasts::addNewToast("You can only use this item on Tier 1 pets." , 'petbattle');
              }
            }
            break;
    }


$Pets = $Loaded_Pet->Get_All_Pets();

?>
<div class='ContentHeader'>Pet Collection <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div><br><hr><br>

<?php

foreach ($Pets as $Pet) {
    echo "<b>" . $Pet["Pet_Name"] . "</b>";
    echo "<div class='PetBlackBox'>";
      echo "<form action='?view=". $View . "' method='post'>";
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
              echo "<b>Health</b>: " . $Pet["Pet_Current_Health"] . " / " . $Pet["Pet_Max_Health"]  . "<br>";
              echo "<b>Action Points</b>: " . $Pet["Pet_Current_AP"] . " / " . $Pet["Pet_Max_AP"]  . "<br>";
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
              if ($Loaded_Pet->Get_Total_Pet_Count() > 1 ) { echo "<input type='submit' name='Mode' value='Release'  style='height:30px; width:24.5%; color:red;' /> "; }
              if ($_SESSION["ID"] == 1) {echo "<input type='submit' name='Mode' value='Level Up'  style='height:30px; width:24.5%; color:blue;' /> ";}
              if ($_SESSION["ID"] == 1) {echo "<input type='submit' name='Mode' value='Exp 50'  style='height:30px; width:24.5%; color:purple;' /> ";}
              if ($_SESSION["ID"] == 1) {echo "<input type='submit' name='Mode' value='Exp 250'  style='height:30px; width:24.5%; color:purple;' /> ";}
            echo "</center>";
            echo "</td>";
            echo "</form>";

              if($Loaded_Pet->Get_All_Item_Count() > 0) {
                echo "<tr>";
                echo "<td colspan='6' bgcolor='black'>";

                if ($Loaded_Pet->Get_Item_Count("Pet Food") > 0) {
                  echo "<form style='display:inline' action='?view=". $View . "' method='post'>";
                  echo "<input type='hidden' value='".$Loaded_Pet->Get_Item_ID('Pet Food')."' name='Item_ID' />";
                  echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
                  echo "<input width='35px' height='35px' type='image' src='petbattles/images/items/".$Loaded_Pet->Get_Item_Image("Pet Food")."' name='UseItem' value='Pet Food' title='[Pet Food] - ".$Loaded_Pet->Get_Item_Description("Pet Food")." - You have ".$Loaded_Pet->Get_Item_Count("Pet Food")." of this item.' />";
                  echo "</form>";
                }

                if ($Loaded_Pet->Get_Item_Count("Candy") > 0) {
                  echo "<form style='display:inline' action='?view=". $View . "' method='post'>";
                  echo "<input type='hidden' value='".$Loaded_Pet->Get_Item_ID('Candy')."' name='Item_ID' />";
                  echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
                  echo "<input width='35px' height='35px' type='image' src='petbattles/images/items/".$Loaded_Pet->Get_Item_Image("Candy")."' name='UseItem' value='Candy' title='[Candy] - ".$Loaded_Pet->Get_Item_Description("Candy")." - You have ".$Loaded_Pet->Get_Item_Count("Candy")." of this item.' />";
                  echo "</form>";
                }

                if ($Loaded_Pet->Get_Item_Count("Offense Training Book") > 0) {
                  echo "<form style='display:inline' action='?view=". $View . "' method='post'>";
                  echo "<input type='hidden' value='".$Loaded_Pet->Get_Item_ID('Offense Training Book')."' name='Item_ID' />";
                  echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
                  echo "<input width='35px' height='35px' type='image' src='petbattles/images/items/".$Loaded_Pet->Get_Item_Image("Offense Training Book")."' name='UseItem' value='Offense Training Book' title='[Offense Training Book] - ".$Loaded_Pet->Get_Item_Description("Offense Training Book")." - You have ".$Loaded_Pet->Get_Item_Count("Offense Training Book")." of this item.' />";
                  echo "</form>";
                }

                if ($Loaded_Pet->Get_Item_Count("Defense Training Book") > 0) {
                  echo "<form style='display:inline' action='?view=". $View . "' method='post'>";
                  echo "<input type='hidden' value='".$Loaded_Pet->Get_Item_ID('Defense Training Book')."' name='Item_ID' />";
                  echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
                  echo "<input width='35px' height='35px' type='image' src='petbattles/images/items/".$Loaded_Pet->Get_Item_Image("Defense Training Book")."' name='UseItem' value='Defense Training Book' title='[Defense Training Book] - ".$Loaded_Pet->Get_Item_Description("Defense Training Book")." - You have ".$Loaded_Pet->Get_Item_Count("Defense Training Book")." of this item.' />";
                  echo "</form>";
                }

                if ($Loaded_Pet->Get_Item_Count("Health Elixer") > 0) {
                  echo "<form style='display:inline' action='?view=". $View . "' method='post'>";
                  echo "<input type='hidden' value='".$Loaded_Pet->Get_Item_ID('Health Elixer')."' name='Item_ID' />";
                  echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
                  echo "<input width='35px' height='35px' type='image' src='petbattles/images/items/".$Loaded_Pet->Get_Item_Image("Health Elixer")."' name='UseItem' value='Health Elixer' title='[Health Elixer] - ".$Loaded_Pet->Get_Item_Description("Health Elixer")." - You have ".$Loaded_Pet->Get_Item_Count("Health Elixer")." of this item.' />";
                  echo "</form>";
                }

                if ($Loaded_Pet->Get_Item_Count("Book of Experience") > 0) {
                  echo "<form style='display:inline' action='?view=". $View . "' method='post'>";
                  echo "<input type='hidden' value='".$Loaded_Pet->Get_Item_ID('Book of Experience')."' name='Item_ID' />";
                  echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
                  echo "<input width='35px' height='35px' type='image' src='petbattles/images/items/".$Loaded_Pet->Get_Item_Image("Book of Experience")."' name='UseItem' value='Book of Experience' title='[Book of Experience] - ".$Loaded_Pet->Get_Item_Description("Book of Experience")." - You have ".$Loaded_Pet->Get_Item_Count("Book of Experience")." of this item.' />";
                  echo "</form>";
                }

                if ($Loaded_Pet->Get_Item_Count("Mystic Candy") > 0) {
                  echo "<form style='display:inline' action='?view=". $View . "' method='post'>";
                  echo "<input type='hidden' value='".$Loaded_Pet->Get_Item_ID('Mystic Candy')."' name='Item_ID' />";
                  echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
                  echo "<input width='35px' height='35px' type='image' src='petbattles/images/items/".$Loaded_Pet->Get_Item_Image("Mystic Candy")."' name='UseItem' value='Mystic Candy' title='[Mystic Candy] - ".$Loaded_Pet->Get_Item_Description("Mystic Candy")." - You have ".$Loaded_Pet->Get_Item_Count("Mystic Candy")." of this item.' />";
                  echo "</form>";
                }

                if ($Loaded_Pet->Get_Item_Count("Evolution Stone") > 0) {
                  echo "<form style='display:inline' action='?view=". $View . "' method='post'>";
                  echo "<input type='hidden' value='".$Loaded_Pet->Get_Item_ID('Evolution Stone')."' name='Item_ID' />";
                  echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
                  echo "<input width='35px' height='35px' type='image' src='petbattles/images/items/".$Loaded_Pet->Get_Item_Image("Evolution Stone")."' name='UseItem' value='Evolution Stone' title='[Evolution Stone] - ".$Loaded_Pet->Get_Item_Description("Evolution Stone")." - You have ".$Loaded_Pet->Get_Item_Count("Evolution Stone")." of this item.' />";
                  echo "</form>";
                }

                echo "</td>";
                echo "</tr>";
              }
          echo "</tr>";
        echo "</table>";
    echo "</div>";
    echo "<br>";
}
?>
