<?php
include('../../headerincludes.php');
$View = Functions::Get_View();
?>
<br>

<div style="text-align:center;"><i>EXTERNAL MACHINE SERVERS</i></div>
<br>
<div class="BorderBox">

    <div class="BlackBox">
        <b>Starbound</b> | Host: 23.229.98.58:23625
        <?php
        $ServerStatus=Functions::getRemoteSeverStatusFromPort("23.229.98.58", 23625);
        Functions::displayServerStatus($ServerStatus);
        ?>
        <br>
        <center><a href="http://clanforge.multiplay.co.uk/servers/2411959/view" target="_blank"><img src="http://cache.multiplayuk.com/b/1-2411959-560x95-13273-FF5519-FFFFFF.png" alt="Server Banner" style="border:0;width:560px;height:95px" /></a></center>
    </div>

    <br>

    <div class="BlackBox">
        <b>Mumble</b> | Host: 76.74.238.52:2262
        <?php
        $ServerStatus=Functions::getRemoteSeverStatusFromPort("76.74.238.52", 2262);
        Functions::displayServerStatus($ServerStatus);
        ?>
        <br>
        <center><a href="http://clanforge.multiplay.co.uk/servers/655395/view" target="_blank"><img src="http://cache.multiplayuk.com/b/1-655395-560x95-5231-FF5519-FFFFFF.png" alt="Server Banner" style="border:0;width:560px;height:95px" /></a></center>
    </div>

</div>

<br>

<div style="text-align:center;"><i>SERVERS RUNNING ON THIS MACHINE</style></i></div>
<br>
<div class="BorderBox">
<b>Mysql</b>
<?php
$ServerStatus=Functions::getServerStatus("mysql");
Functions::displayServerStatus($ServerStatus);
?>
<br>

<b>FTP</b> | <a target='_blank' href='ftp://dustinhendrickson.com'>ftp://dustinhendrickson.com</a> | User: ftp Pass: cool
<?php
$ServerStatus=Functions::getServerStatus("ftp");
Functions::displayServerStatus($ServerStatus);
 ?>
<br>

<b>Console</b>
<?php
$ServerStatus=Functions::getServerStatus("shellinaboxd");
Functions::displayServerStatus($ServerStatus);
 ?>
<br>

<b>IRC GUI</b>
<?php
$ServerStatus=Functions::getServerStatus("kiwiirc");
Functions::displayServerStatus($ServerStatus);
?>
<br>

<b>IRC Bot</b>
<?php
$ServerStatus=Functions::getServerStatus("ruby");
Functions::displayServerStatus($ServerStatus);
?>
<br>

<b>Battle.Net</b>
<?php
$ServerStatus=Functions::getServerStatus("bnetd");
Functions::displayServerStatus($ServerStatus);
?>
<br>

<div class="BlackBox">
<b>Steam Game Server</b>
<br>
Server Info Disabled until I fix it.
<?php
// I have disabled this due to it causing the server to hangup
// 
// $ServerStatus=Functions::getServerStatus("srcds_linux");
// Functions::displayServerStatus($ServerStatus);

// $SteamQuery = Functions::sourceServerQuery("dustinhendrickson.com:27015"); // $ip MUST contain IP:PORT
// $SteamServer = Functions::formatSourceQuery($SteamQuery);

// echo "<hr>";
// echo "<b>Hostname</b>: ".$SteamServer['hostname'] . "<br>";
// echo "<b>Map</b>: ".$SteamServer['map'] . "<br>";
// echo "<b>Game</b>: ".$SteamServer['game'] . "<br>";
// echo "<b>Gamemode</b>: ".$SteamServer['gamemode'];
?>
</div>
<br>
</div>
