<?php
$View = Functions::Get_View();
?>
<div class='ContentHeader'>Snake</div><hr>
<center>
<canvas id="canvas" width="800px" height="500px"></canvas>
<br><br>
<form action='?view=<?php echo $View; ?>&difficulty=easy' method='post'>
<input type="submit" name="Difficulty" value="Easy"  style="height:50px; width:60%" />
</form>
<form action='?view=<?php echo $View; ?>&difficulty=medium' method='post'>
<input type="submit" name="Difficulty" value="Medium"  style="height:50px; width:60%" />
</form>
<form action='?view=<?php echo $View; ?>&difficulty=hard' method='post'>
<input type="submit" name="Difficulty" value="Hard"  style="height:50px; width:60%" />
</form>
</center>
<!-- Jquery -->
<script type="text/javascript">
<?php 
echo '
function get_difficulty(){
    var difficulty = "'.$_GET['difficulty'].'";
    if(difficulty=="") {
        difficulty="medium";
    }
    return difficulty;
}
';
?>
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="../js/jquery/jquery.touchSwipe.min.js" type="text/javascript"></script>
<script src="../js/snake.js" type="text/javascript"></script>