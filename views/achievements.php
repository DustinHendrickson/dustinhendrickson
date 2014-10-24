<?php
Functions::Check_User_Permissions_Redirect("User");
?>

<div class='ContentHeader'>All Achievements</div>
<i>Green = Unlocked</i><br>
<i>Red = Locked</i>
<hr>

<?php
$User = new User($_SESSION['ID']);
$Achievements = explode(",", $User->Get_List_Of_All_Achievements());

foreach ($Achievements as $Achievement) {
    if ($User->Is_Achievement_Unlocked($User->Get_Achievement_Name_By_ID($Achievement)) == true) {
        $Achievement_Info = $User->Get_Achievement_Info_By_ID($Achievement);
        if (isset($Achievement_Info['ID'])){
            echo "<div class='Success'>";
            echo "<b>Name:</b> " . $Achievement_Info['Name'];
            echo "<br>";
            echo "<b>Description:</b> " . $Achievement_Info['Description'];
            echo "<br>";
            echo "<b>Points:</b> " . $Achievement_Info['Points'];
            echo "</div>";
        }
    } else {
        $Achievement_Info = $User->Get_Achievement_Info_By_ID($Achievement);
        if (isset($Achievement_Info['ID'])){
            echo "<div class='Error'>";
            echo "<b>Name:</b> " . $Achievement_Info['Name'];
            echo "<br>";
            echo "<b>Description:</b> " . $Achievement_Info['Description'];
            echo "<br>";
            echo "<b>Points:</b> " . $Achievement_Info['Points'];
            echo "</div>";
        }
    }
}