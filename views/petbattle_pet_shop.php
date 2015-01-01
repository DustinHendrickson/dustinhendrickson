<?php
Functions::Check_User_Permissions_Redirect('User');

$User = new User($_SESSION['ID']);
$UserPet = new BattlePet($_SESSION['ID']);
$View = Functions::Get_View();

 switch ($_POST['Action'])
    {
        case 'Buy':
            if ($User->Points >= $_POST['Item_Cost']) {
                $UserPet->Purchase_Item($_POST['Item_Name'],$_POST['Item_Cost'],$_POST['Item_Description'], $_POST['Item_Image']);
            } else {
                Toasts::addNewToast("You don't have enough Points to buy that!", 'petbattle');
            }
            break;
    }

?>
<div class='ContentHeader'>Purchase Pet Items <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div><br><hr><br>
<center>
Points are earned by winning PVP battles and earning achievements from the site! You can also redeem free points twice a day <a href='?view=points'><b>here</b></a>. You can use items you have purchased in your <a href='?view=petbattle_collection'><b>Pet Collection</b></a><br><br><hr>
<?php 
    $ItemList = Spyc::YAMLLoad('/var/www/petbattles/data/base_item_list.yml');
    $MaxItems = count($ItemList['items'][1]);

    // Here I will load up a random pet from a YAML config file and then add it to the DB.
    $i=0;
    while ($i < $MaxItems) {
        echo "<form action='?view=". $View . "' method='post'>";
        $Item_Name = $ItemList['items'][1][$i]['name'];
        $Item_Image = $ItemList['items'][1][$i]['image'];
        $Item_Cost = $ItemList['items'][1][$i]['cost'];
        $Item_Description = $ItemList['items'][1][$i]['description'];
        echo "<img src='petbattles/images/items/".$Item_Image."'></img>";
        echo "<br>";
        echo "<b>" . $Item_Name . " </b>- " . $Item_Cost . " points";
        echo "<br>";
        echo "<i>" . $Item_Description . "</i>";
        echo "";
        echo "<br>";
        ?>
        <input type='hidden' value='<?php echo $Item_Name; ?>' name='Item_Name' />
        <input type='hidden' value='<?php echo $Item_Image; ?>' name='Item_Image' />
        <input type='hidden' value='<?php echo $Item_Cost; ?>' name='Item_Cost' />
        <input type='hidden' value='<?php echo $Item_Description; ?>' name='Item_Description' />
        <input title="Purchase this item!" type="submit" name="Action" value="Buy"  style="height:25px; width:50px" />
        <?php
        echo "<hr>";
        echo "</form>";
    $i++;
    }

?>
</center>