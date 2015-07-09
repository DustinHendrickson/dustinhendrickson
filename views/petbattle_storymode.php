<?php unset($_SESSION['Story_Mode']); ?>
<div id="Preload" style="display: none;">
    <img src="petbattles/images/tiles/PetHospital.png" width="1" height="1" alt="preload" />
    <img src="petbattles/images/tiles/Rock.png" width="1" height="1" alt="preload" />
    <img src="petbattles/images/tiles/TallGrass.png" width="1" height="1" alt="preload" />
    <img src="petbattles/images/tiles/Trainer1Down.png" width="1" height="1" alt="preload" />
    <img src="petbattles/images/tiles/Trainer1Up.png" width="1" height="1" alt="preload" />
    <img src="petbattles/images/tiles/Trainer1Left.png" width="1" height="1" alt="preload" />
    <img src="petbattles/images/tiles/Trainer1Right.png" width="1" height="1" alt="preload" />
    <img src="petbattles/images/GrassBackground.png" width="1" height="1" alt="preload" />
</div>
<div class='ContentHeader'>Story Mode <a href='?view=petbattle_home'><img align='right' height='35' width='100' src='img/back.png'></a></div><br><hr><br>
<center>
<canvas id="canvasbg" style="position:absolute; z-index: 1; left:9%; top:12%;" width="800px" height="480px"></canvas>
<canvas id="canvas" style="position:absolute; z-index: 2; left:9%; top:12%;" width="800px" height="480px"></canvas>
<canvas id="canvasui" style="position:absolute; z-index: 3; left:9%; top:16%;" width="800px" height="480px"></canvas>
<canvas id="canvasconsole" style="position:absolute; z-index: 4; left:9%; top:12%;" width="800px" height="480px"></canvas>

</center>
<!-- Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="../js/jquery/jquery.touchSwipe.min.js" type="text/javascript"></script>
<script src="../js/petbattles.js" type="text/javascript"></script>
