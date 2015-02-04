<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$UserPet = new BattlePet($_SESSION['ID']);
$View = Functions::Get_View();

$Quests = $UserPet->Get_All_Daily_Quests();
?>
<div class='ContentHeader'>Daily Quests <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div>
<br>
<hr>
<?php

if (!$Quests) {
    echo "You have no Quests currently. Please come back tomorrow!";
}

foreach($Quests as $Quest) {
    $QuestBaseInfo = $UserPet->Get_Base_Daily_Quest_Info($Quest['QuestID']);
    echo "<b>" . $QuestBaseInfo['Name'] . "</b> - " . $QuestBaseInfo['Points'] . " points <br>" . $QuestBaseInfo['Description'];
    $QuestDailyInfo = $UserPet->Get_One_Daily_Quest($QuestBaseInfo['ID']);
    echo "<br>";
    echo "<b>Progress: </b>" . $QuestDailyInfo['CurrentObjective'] . " / " . $QuestDailyInfo['NeededObjective'];
    echo "<br>";
    echo "<hr>";
}


?>