<?php
include('../../headerincludes.php');
if($_POST['SearchList'] != '') { 
    $_SESSION['SearchList'] = $_POST['SearchList'];
}

$User = new User($_SESSION['UserSessionID']);
$Connection = new Connection();

    //Setup Checks for user display.
    if (isset($_SESSION['SearchList'])) {
        $UserToDisplay = $_SESSION['SearchList'];
    } else {
        $UserToDisplay = '';
    }

    // Connect to Redis from PHP
    $redisClient = new Redis();
    $redisClient -> connect('localhost');

    // Load Variables from Redis DB
    $Level = $redisClient->get('user:' . $UserToDisplay . ':level');
    $Exp = $redisClient->get('user:' . $UserToDisplay . ':exp');
    $WeaponID = $redisClient->get('user:' . $UserToDisplay . ':weapon');
    $ArmorID = $redisClient->get('user:' . $UserToDisplay . ':armor');
    $Wins = $redisClient->get('user:' . $UserToDisplay . ':wins');
    $Loses = $redisClient->get('user:' . $UserToDisplay . ':loses');
    $Ties = $redisClient->get('user:' . $UserToDisplay . ':ties');
    $Killing_Spree = $redisClient->get('user:' . $UserToDisplay . ':killing_spree');
    $Losing_Spree = $redisClient->get('user:' . $UserToDisplay . ':losing_spree');
    $Quests_Completed = $redisClient->get('user:' . $UserToDisplay . ':quests_completed');
    $Quests_Failed = $redisClient->get('user:' . $UserToDisplay . ':quests_failed');

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
<table width='100%'>

<tr>
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
</tr>



<tr>
    <td>
    Wins: <b><?php echo $Wins ?></b></br>
    </td>

    <td>
    Loses: <b><?php echo $Loses ?></b></br>
    </td>

    <td>
    Ties: <b><?php echo $Ties ?></b></br>
    </td>
</tr>


<tr>
    <td>
    Killing Spree: <b><?php echo $Killing_Spree ?></b></br>
    Losing Spree: <b><?php echo $Losing_Spree ?></b></br>
    </td>

    <td>
    Quests Completed: <b><?php echo $Quests_Completed ?></b></br>
    Quests Failed: <b><?php echo $Quests_Failed ?></b></br>
    </td>

    <td>
    Win Percent: <b><?php if ($Wins + $Loses + $Ties > 0)  { echo number_format($Wins / ($Wins + $Loses + $Ties), 2) * 100 . "%"; } ?></b></br>
    Quest Win Percent: <b><?php if ($Quests_Completed + $Quests_Failed > 0 ) { echo number_format($Quests_Completed / ($Quests_Completed + $Quests_Failed), 2) * 100 . "%"; } ?></b></br>
    </td>
</tr>

<tr>
<td>
</td>

</tr>
<tr>
<td>
 
</td>
</tr>

<tr>
    <td colspan='3'>
        <form method='post' action='fightbot.php'>
        View Another Players Info <i>(Select their name)</i><br>

        <select id='SearchList' name='SearchList' style='width: 100%;' size='5' onclick='this.form.submit()'>
        <?PHP
                $Connection = new Connection();
                $List_Array = array();
                $List_Results = $Connection->Custom_Query("SELECT * FROM users WHERE FightBot_Name IS NOT NULL", $List_Array, true);
                foreach ($List_Results as $Row) {
                    if($UserToDisplay == $Row['FightBot_Name']) { $selected = "selected"; } else { $selected = ''; }
                    echo "<option " . $selected . " value='" . $Row['FightBot_Name'] . "'>" . $Row['FightBot_Name']. "</option>";
                }
        ?>
        </select>
        <submit 
        </form>
    </td>
</tr>

</table>
