<?php
include('../../headerincludes.php');

$User = new User($_POST['UserSessionID']);

if ($User->FightBot_Name != ''){

    // Connect to Redis from PHP
    $redisClient = new Redis();
    $redisClient -> connect('localhost');

    // Load Variables from Redis DB
    $Level = $redisClient->get('user:' . $User->FightBot_Name . ':level');
    $Exp = $redisClient->get('user:' . $User->FightBot_Name . ':exp');
    $WeaponID = $redisClient->get('user:' . $User->FightBot_Name . ':weapon');
    $ArmorID = $redisClient->get('user:' . $User->FightBot_Name . ':armor');

    // Load in YAML information and set variables.
    $EquipmentData = Spyc::YAMLLoad('/home/dustin/cadbot_cinch/plugins/fight/equipment.yml');
    $Weapon = $EquipmentData['weapons'][$Level][$WeaponID]['name'];
    $Armor = $EquipmentData['armor'][$Level][$ArmorID]['name'];
    $WeaponElement = $EquipmentData['weapons'][$Level][$WeaponID]['element'];
    $WeaponDamage = $EquipmentData['weapons'][$Level][$WeaponID]['damage'];
    $ArmorElement = $EquipmentData['armor'][$Level][$ArmorID]['element'];
    $ArmorAmount = $EquipmentData['armor'][$Level][$ArmorID]['armor'];

    // Actual output of data
    echo "Name: <b>{$User->FightBot_Name}</b></br>";
    echo "Level: <b>{$Level}</b></br>";
    echo "EXP: <b>{$Exp}</b> / <b>" . ($Level * 100) . "</b></br>";
    echo "Weapon: <b>{$Weapon}</b> 1-{$WeaponDamage} - {$WeaponElement}<br>";
    echo "Armor: <b>{$Armor}</b> 0-{$ArmorAmount} - {$ArmorElement}";
} else {
    echo "To see stats and weapons in the web browser please create an account, log in and fill out your FightBot irc registered name on the <a href='?view=my_account'><u>My Account</u></a> page.";
}
?>
