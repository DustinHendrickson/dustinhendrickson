<?php
function getServerStatus($ServerName) {
  exec("ps aux | pgrep " . $ServerName, $ServerStatus);

   if ($ServerStatus[0] != "") {
     return "Up";
   } else {
     return "Down";
   }
}

function displayServerStatus($ServerStatus) {
  switch ($ServerStatus){
        case "Up":
          echo "<div class='success'> {$ServerStatus} </div>";
          break;
        case "Down":
          echo "<div class='error'> {$ServerStatus} </div>";
          break;
      }
}

function getRemoteSeverStatusFromPort($PortNumber) {
  // Here we do a basic connection test to see if this port is reachable on the network, times out in 5 seconds,
  // if the timeout succeeds, we know that server is down. Otherwise it's up.
  $socket = @fsockopen("dustinhendrickson.com", $PortNumber, $errorNumber, $errorString, 5);
  if ($socket){
    fclose($socket);
    return "Up";
  } else {
    return "Down";
  }
}

function getNumberOfConnectionsOnPort($PortNumber) {
  // Only works on internal connections, returns the number of connections to the specific port.
  $count = shell_exec("netstat -an | grep :{$PortNumber} | grep ESTABLISHED | wc --lines");
  return $count;
}

Functions::Check_User_Permissions_Redirect("Staff");
Functions::Refresh_Page(60);

?>
<div class='ContentHeader'>Server Status Listings</div><hr>
<div id='servers' name='servers' style='overflow:auto; height:60%; width:100%'>
<br>

<i>EXTERNAL SERVERS:</i><br><br>

<b>Starbound</b> | Host: dustinhendrickson.com Pass: none
<?php
$ServerStatus=getRemoteSeverStatusFromPort(21025);
displayServerStatus($ServerStatus);
?>
<br>

<b>Terraria</b>
<?php
$ServerStatus=getRemoteSeverStatusFromPort(7777);
displayServerStatus($ServerStatus);
?>
<br>

<b>Minecraft</b>
<?php
$ServerStatus=getRemoteSeverStatusFromPort(25565);
displayServerStatus($ServerStatus);
?>
<br>

<b>FTP</b> | <a target='_blank' href='ftp://dustinhendrickson.com'>ftp://dustinhendrickson.com</a> | User: guest Pass: none
<?php
$ServerStatus=getRemoteSeverStatusFromPort(21);
displayServerStatus($ServerStatus);
?>
<br>


<i>INTERNAL SERVERS:</i><br><br>

<b>Mysql</b>
<?php
$ServerStatus=getServerStatus("mysql");
displayServerStatus($ServerStatus);
?>
<br>

<b>FTP</b>
<?php
$ServerStatus=getServerStatus("ftp");
displayServerStatus($ServerStatus);
 ?>
<br>

<b>IRC Bot</b>
<?php
$ServerStatus=getServerStatus("ruby");
displayServerStatus($ServerStatus);
?>
<br>

</div>
