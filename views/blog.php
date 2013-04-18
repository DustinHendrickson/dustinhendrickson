<?php
	$Blog = new Blog();
	$Blog->Get_Posts(true,5);

	echo "Latest Blog Posts...<hr>";

	$Template="
	<br>
    <div class='BlogWrapper'>
    <div class='BlogTitle'>{$Blog_Page['Title']}</div>
    <div class='BlogBody'>{$Blog_Page['Body']}</div>
    <br>
    <div class='BlogCreation'>by {$User->Username} - {$Blog_Page['Creation_Date']}</div>
    </div>
	";

	$Blog->Display_Page($Blog->Get_Page(),$Template);

	echo "<br>";
	$Blog->Write_Pagination_Nav();