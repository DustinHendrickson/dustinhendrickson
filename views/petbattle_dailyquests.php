<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$UserPet = new BattlePet($_SESSION['ID']);
$View = Functions::Get_View();

switch ($_POST['Mode'])
    {
        case 'Abandon Quest':
            $UserPet->Remove_Daily_Quest($_POST['QuestID']);
            break;
    }

$Quests = $UserPet->Get_All_Daily_Quests();

?>
<div class='ContentHeader'>Daily Quests <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div>
<br>
<hr>
<?php

if (!$Quests) {
    echo "You have no Quests currently. Please come back tomorrow!";
} else {
    echo "<form action='?view={$View}' method='post'>";
}

foreach($Quests as $Quest) {
    $QuestProgressBarSize = 0;
    $QuestBaseInfo = $UserPet->Get_Base_Daily_Quest_Info($Quest['QuestID']);
    $QuestDailyInfo = $UserPet->Get_One_Daily_Quest($QuestBaseInfo['ID']);

    if ($QuestDailyInfo['NeededObjective'] > 0) {
        $QuestProgressBarSize = ($QuestDailyInfo['CurrentObjective'] / $QuestDailyInfo['NeededObjective']) * 100;
        $QuestProgressBarSize = number_format($QuestProgressBarSize);
    }

    echo "<b>" . $QuestBaseInfo['Name'] . "</b> - " . $QuestBaseInfo['Points'] . " points";
    echo "<br>";
    echo $QuestBaseInfo['Description'];
    echo "<br>";
    echo "<b>Progress: </b>" . $QuestDailyInfo['CurrentObjective'] . " / " . $QuestDailyInfo['NeededObjective'] . " - " . $QuestProgressBarSize . "%";
    echo "<table width=100%>";
    echo "<tr>";
    echo "<td bgcolor='black'> <img height='15' width='{$QuestProgressBarSize}%' src='../img/greensquare.png'> </td>";
    echo "</tr>";
    echo "</table>";
    echo "<input type='submit' name='Mode' value='Abandon Quest'  style='height:25px; color:red;' /> ";
    echo "<input type='hidden' value='".$QuestDailyInfo['QuestID']."' name='QuestID' />";
    echo "<hr>";
}

if ($Quests) {
    echo "</form>";
}


?>