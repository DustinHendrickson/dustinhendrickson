<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();
$Loaded_Pet = new BattlePet($_SESSION['ID']);

if ($_POST['Pet_ID']) {
  $Loaded_Pet->Release_Pet($_POST['Pet_ID']);
}

$Pets = $Loaded_Pet->Get_All_Pets();

?>
<div class='ContentHeader'>Pet Collection <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div><br><hr><br>

<?php

foreach ($Pets as $Pet) {
    echo "<b>" . $Pet["Pet_Name"] . "</b>";
    echo "<div class='BlackBox'>";
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
              echo "<b>Status</b>: " . $Pet["Pet_Status"] . "<br>";
            echo "</td>";
            echo "<td>";
              echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
              echo "<input type='submit' name='SetActive' value='Release Pet'  style='height:30px; width:100px; color:red;' />";
            echo "</td>";
          echo "</tr>";
        echo "</table>";
      echo "</form>";
    echo "</div>";
    echo "<br>";
}
?>
