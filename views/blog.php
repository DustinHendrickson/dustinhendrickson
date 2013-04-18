<?php
	$Blog = new Blog();
	$Blog->Get_Posts(true,5);

	echo "Latest Blog Posts...<hr>";

	if(isset($_GET['page'])){$Page=$_GET['page'];} else {$Page=1;}

	if(isset($Blog->Pages[$Page]))
	{
		foreach ($Blog->Pages[$Page] as $Blog_Page)
		{
			$User = new User($Blog_Page['UserID']);

			echo"
				<br>
				<div class='BlogWrapper'>
				<div class='BlogTitle'>{$Blog_Page['Title']}</div>
				<div class='BlogBody'>{$Blog_Page['Body']}</div>
				<br>
				<div class='BlogCreation'>by {$User->Username} - {$Blog_Page['Creation_Date']}</div>
				</div>
			";
		}
	} else {
		Write_Log('php',"Trying to access a blog page that doesn't exist.");
		echo "<div class='Error'>You are trying to access a blog page that doesn't exist.</div>";

	}
	echo "<br>";
	$Blog->Write_Pagination_Nav();
