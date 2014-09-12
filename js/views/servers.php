<?php
include('../../headerincludes.php');
$View = Functions::Get_View();
?>
<br>

<div style="text-align:center;"><i>EXTERNAL MACHINE SERVERS</i></div>
<br>
<div class="BorderBox">
<b>Starbound</b> | Host: starbound.dustinhendrickson.com Pass: none
<?php
$ServerStatus=Functions::getRemoteSeverStatusFromPort("starbound.dustinhendrickson.com", 21025);
Functions::displayServerStatus($ServerStatus);
?>
<br>

<b>Minecraft</b>
<?php
$ServerStatus=Functions::getRemoteSeverStatusFromPort("dustinhendrickson.com",25565);
Functions::displayServerStatus($ServerStatus);
?>
<br>

<b>Webcam</b>
<?php
$ServerStatus=Functions::getRemoteSeverStatusFromPort("dustinhendrickson.com",8080);
Functions::displayServerStatus($ServerStatus);
?>
<br>

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
</div>