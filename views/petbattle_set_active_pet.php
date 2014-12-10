<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();
$Loaded_Pet = new BattlePet($_SESSION['ID']);

if ($_POST['Pet_ID']) {
  $Loaded_Pet->Set_Active_Pet($_POST['Pet_ID']);
}

$Pets = $Loaded_Pet->Get_All_Inactive_Pets();

?>
<div class='ContentHeader'>Set your current active pet | <a href='?view=petbattle_home'><- Back to Homescreen</a></div><hr><br><br>
<form action='?view=<?php echo $View; ?>' method='post'>
<?php
foreach ($Pets as $Pet) {
    echo "<form action='?view=". $View . "' method='post'>";
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
    echo "<input type='hidden' value='".$Pet["Pet_ID"]."' name='Pet_ID' />";
    echo "<input type='submit' name='SetActive' value='Set Active'  style='height:50px; width:150px; color:green;' />";
    echo "</div></form><br>";
}
?>
