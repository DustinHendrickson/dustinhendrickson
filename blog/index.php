<?php
define('IN_BLOG', true);
define('PATH', '/var/www/dustin/blog/');
include(PATH . 'includes/miniblog.php');
?>
<style type="text/css">
<!--
*, html {
	margin:0;
	padding:0;
}

h2 {
	font-weight:normal;
	color:#666666;
	font-size:1.4em;
}
p {
	margin-bottom:10px;
	line-height:1.6em;
}
div.wrapper {
	width:95%;
	padding:5px;
	margin:10px auto 10px auto;
}
div.post {
	background-color:#ffffff;
	border:1px solid #cccccc;
	padding:7px;
	margin:10px 0;
}
span.date {
	color:#666666;
	font-size:0.7em;
	text-transform:uppercase;
}

div.navigation p a {
	font-size:1.2em;
}
div.navigation p.previous-link {
	width:48%;
	float:left;
	text-align:left;
}
div.navigation p.next-link {
	width:48%;
	float:right;
	text-align:right;
}
div.post-content {
	padding-top:4px;
}
div.clear { 
	clear:both;
}
div.footer p {
	padding-top:10px;
	color:#999999;
	font-size:0.9em;
	text-align:center;
}
div.footer p a {
	color:#999999;
}
-->
</style>

<div class="wrapper">
	
	<h1>Dustin's Blog</h1>
	<h2>Latest Entrys...</h2>
	
	<?=$miniblog_posts?>
	
	<div class="navigation">
		<? if(!$single) { ?>
			<? if($miniblog_previous) {	?> <p class="previous-link"><?=$miniblog_previous?></p>	<? } ?>
			<? if($miniblog_next) {	?>	<p class="next-link"><?=$miniblog_next?></p> <? } ?>
		<? } ?>
		<? if($single) { ?>
			<p class="previous-link"><a href="<?=$config['miniblog-filename']?>">&laquo; return to posts</a></p>
		<? } ?>
		<div class="clear"></div>
	</div>
	<div class="footer">
	</div>
</div>
