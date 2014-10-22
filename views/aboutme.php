<?php
if (isset($_SESSION['ID'])) {
    $User = new User($_SESSION['ID']);
    $User->Add_Achievement("View About Me");
}
?>
<div class='ContentHeader'>About Me</div><hr>
I'm a programmer, gamer and tech enthusiast.<br><br>
I use be part of WoW Radio and OMFG, podcasting and live streaming radio shows that were held every week.<br><br>
I'm also working on some games, mainly using the  Warcraft 3 / Starcraft 2 editor but have also started using Unity to build my own games from the ground up.<br> Checkout my <a href="?view=projects"><u>Projects</u></a> for some videos.<br><br>
There is also a boardgame I'm working on in my spare time, it's a mix of Dungeons and Dragons and Final Fantasy Tactics. You play with a small team of figures on the board and combat is calculated and displayed on your smartphone.<br> The board has pressure sensors and lighted tiles to display attack ranges / move speeds of hereos and transmits them to your smartphone via WIFI networking powered by a <a href="http://www.raspberrypi.org/">Raspberry Pi</a>.

