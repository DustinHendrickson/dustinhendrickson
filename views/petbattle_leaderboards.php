<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$View = Functions::Get_View();
$Connection = new Connection();
?>
<div class='ContentHeader'>Top 10 Leaderboards <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div><br><hr><br>
<div class="container">
   <div class="column-center">
   <b>Most Pet Battles Won</b><br><hr>

   <?php
    $User_SQL = "SELECT * FROM users ORDER BY Pet_Battles_Won DESC LIMIT 10";
    $Results = $Connection->Custom_Query($User_SQL, array(), true);

   foreach ($Results as $User) {
    echo ($User['Username'] . " - " . $User['Pet_Battles_Won'] . "<br>");
   }
   ?>
   </div>


   <div class="column-left">
   <b>Most Pets Caught</b><br><hr>

   <?php
    $User_SQL = "SELECT * FROM users ORDER BY Pets_Caught DESC LIMIT 10";
    $Results = $Connection->Custom_Query($User_SQL, array(), true);

   foreach ($Results as $User) {
    echo ($User['Username'] . " - " . $User['Pets_Caught'] . "<br>");
   }
   ?>
   </div>


   <div class="column-right">
   <b>Most Points</b><br><hr>

   <?php
    $User_SQL = "SELECT * FROM users ORDER BY Points DESC LIMIT 10";
    $Results = $Connection->Custom_Query($User_SQL, array(), true);

   foreach ($Results as $User) {
    echo ($User['Username'] . " - " . $User['Points'] . "<br>");
   }
   ?>

   </div>
</div>