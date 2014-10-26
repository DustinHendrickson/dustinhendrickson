<div class='ContentHeader'>The Emulator</div><i>The emulator supports .nes .smc .gen .gb .gbc .gba files.</i><hr><br>
<div>
    <div id="emulator">
        <p>To play this game, please, download the latest Flash player!</p>
        <br>
        <a href="http://www.adobe.com/go/getflashplayer">
            <img src="//www.adobe.com/images/shared/download_buttons/get_adobe_flash_player.png" alt="Get Adobe Flash player"/>
        </a>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

<script type="text/javascript">

    var resizeOwnEmulator = function(width, height)
    {
        var emulator = $('#emulator');
        emulator.css('width', width);
        emulator.css('height', height);
    }

    $(function()
    {
        function embed()
        {
            var emulator = $('#emulator');
            if(emulator)
            {
                var flashvars = 
                {
                    system : 'snes'
                };
                var params = {};
                var attributes = {};

                params.allowscriptaccess = 'sameDomain';
                params.allowFullScreen = 'true';
                params.allowFullScreenInteractive = 'true';

                swfobject.embedSWF('../snes/Nesbox.swf', 'emulator', '640', '480', '11.2.0', '../snes/expressInstall.swf', flashvars, params, attributes);
            }
        }

        embed();
    });
</script>

<br><br><hr>
<div style='text-align: center;' class='ContentHeader'>Roms</div>
<div style='text-align: center;'><i>You will need to download the rom from the server below then select it from the emulator up top.</i></div>
<br>

<hr>
<br><b>SNES</b><br>
<?php echo "There are " . Functions::countFilesInDirectory('files/snes/Roms/') . " roms for this category."; ?>
<br>
<img height='128' width='128' src='../img/SNES.png'><br>
<a id="ToggleSNES" href="javascript:ToggleDiv('SNES','ToggleSNES');" >+ Show Contents</a>
<div id="SNES" style='display: none;'>
<iframe src="https://dustinhendrickson.com/files/snes/Roms" width="100%" height="400px"></iframe>
</div>
<br><br><hr>


<br><b>NES</b><br>
<?php echo "There are " . Functions::countFilesInDirectory('files/nes/Roms/') . " roms for this category."; ?>
<br>
<img height='128' width='128' src='../img/NES.png'><br>
<a id="ToggleNES" href="javascript:ToggleDiv('NES','ToggleNES');" >+ Show Contents</a>
<div id="NES" style='display: none;'>
<iframe src="https://dustinhendrickson.com/files/nes/Roms" width="100%" height="400px"></iframe>
</div>
<br><br><hr>


<br><b>Sega</b><br>
<?php echo "There are " . Functions::countFilesInDirectory('files/sega/Roms/') . " roms for this category."; ?>
<br>
<img height='128' width='128' src='../img/SEGA.png'><br>
<a id="ToggleSEGA" href="javascript:ToggleDiv('SEGA','ToggleSEGA');" >+ Show Contents</a>
<div id="SEGA" style='display: none;'>
<iframe src="https://dustinhendrickson.com/files/sega/Roms" width="100%" height="400px"></iframe>
</div>
<br><br><hr>


<br><b>Game Boy</b><br>
<?php echo "There are " . Functions::countFilesInDirectory('files/gb/GB/') . " roms for this category."; ?>
<br>
<img height='128' width='128' src='../img/GB.png'><br>
<a id="ToggleGB" href="javascript:ToggleDiv('GB','ToggleGB');" >+ Show Contents</a>
<div id="GB" style='display: none;'>
<iframe src="https://dustinhendrickson.com/files/gb/GB" width="100%" height="400px"></iframe>
</div>
<br><br><hr>


<br><b>Game Boy Advanced</b><br>
<?php echo "There are " . Functions::countFilesInDirectory('files/gba/GBA/') . " roms for this category."; ?>
<br>
<img height='128' width='128' src='../img/GBA.png'><br>
<a id="ToggleGBA" href="javascript:ToggleDiv('GBA','ToggleGBA');" >+ Show Contents</a>
<div id="GBA" style='display: none;'>
<iframe src="https://dustinhendrickson.com/files/gba/GBA" width="100%" height="400px"></iframe>
</div>
<br><br><hr>