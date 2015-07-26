<?php
include('../../headerincludes.php');

$User = new User($_POST['UserSessionID']);
$Connection = new Connection();

if ($User->FightBot_Name != ''){

    //Setup Checks for user display.
    $UserToDisplay = $User->FightBot_Name;
    if (isset($_SESSION['SearchList'])) {
        $UserToDisplay = $_SESSION['SearchList'];
    }

    // Connect to Redis from PHP
    $redisClient = new Redis();
    $redisClient -> connect('localhost');

    // Load Variables from Redis DB
    $Level = $redisClient->get('user:' . $UserToDisplay . ':level');
    $Exp = $redisClient->get('user:' . $UserToDisplay . ':exp');
    $WeaponID = $redisClient->get('user:' . $UserToDisplay . ':weapon');
    $ArmorID = $redisClient->get('user:' . $UserToDisplay . ':armor');

    // Load in YAML information and set variables.
    $EquipmentData = Spyc::YAMLLoad('/home/dustin/cadbot_cinch/plugins/fight/equipment.yml');
    $Weapon = $EquipmentData['weapons'][$Level][$WeaponID]['name'];
    $Armor = $EquipmentData['armor'][$Level][$ArmorID]['name'];
    $WeaponElement = $EquipmentData['weapons'][$Level][$WeaponID]['element'];
    $WeaponDamage = $EquipmentData['weapons'][$Level][$WeaponID]['damage'];
    $ArmorElement = $EquipmentData['armor'][$Level][$ArmorID]['element'];
    $ArmorAmount = $EquipmentData['armor'][$Level][$ArmorID]['armor'];

    // Actual output of data
?>
<table >

<td width='30%'>
Name: <b><?php echo $UserToDisplay ?></b></br>
Level: <b><?php echo $Level ?></b></br>
EXP: <b><?php echo $Exp ?></b> / <b> <?php echo ($Level * 100) ?></b></br>
</td>

<td width='30%'>
Weapon: <b><?php echo $Weapon ?></b> 1-<?php echo $WeaponDamage; ?><br>
Element: <b><?php echo $WeaponElement ?></b><br>
</td>

<td width='30%'>
Armor: <b><?php echo $Armor ?></b> 0-<?php echo $ArmorAmount; ?><br>
Element: <b><?php echo $ArmorElement ?></b><br>
</td>

</table>
<?php
} else {
    echo "To see stats and weapons in the web browser please create an account, log in and fill out your FightBot irc registered name on the <a href='?view=my_account'><u>My Account</u></a> page.";
}
?>
