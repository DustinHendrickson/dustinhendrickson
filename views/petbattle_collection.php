<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();
?>
<div class='ContentHeader'>Pet Collection | <a href='?view=petbattle_home'><- Back to Homescreen</a></div><hr><br><br>

<?php
$Loaded_Pet = new BattlePet($_SESSION['ID']);
$Pets = $Loaded_Pet->Get_All_Pets();

//var_dump($Pets);

foreach ($Pets as $Pet) {
    echo "<div class='BlackBox'>";
    echo "Name: " . $Pet["Pet_Name"] . "<br>";
    echo "Level: " . $Pet["Pet_Level"] . "<br>";
    echo "EXP: " . $Pet["Pet_EXP"] . "<br>";
    echo "Offense: " . $Pet["Pet_Offense"] . "<br>";
    echo "Defense: " . $Pet["Pet_Defense"] . "<br>";
    echo "Health : " . $Pet["Pet_Current_Health"] . " / " . $Pet["Pet_Max_Health"]  . "<br>";
    echo "Action Points : " . $Pet["Pet_Current_AP"] . " / " . $Pet["Pet_Max_AP"]  . "<br>";
    echo "Skill 1: " . $Pet["Pet_Skill_1"] . "<br>";
    echo "Skill 2: " . $Pet["Pet_Skill_2"] . "<br>";
    echo "Skill 3: " . $Pet["Pet_Skill_3"] . "<br>";
    echo "Type: " . $Pet["Pet_Type"] . "<br>";
    echo "Status: " . $Pet["Pet_Status"] . "<br>";
    echo "Is Active: " . Functions::Convert_Int_To_Boolean($Pet["Pet_Active"]) . "<br>";
    echo "</div><br>";
}
?>
