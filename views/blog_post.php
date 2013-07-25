<?php

    $Blog = new Blog();
    $Blog->Get_Posts(1,1,'',$_GET['blog_id']);

    echo "Blog Post ID... [ {$_GET['blog_id']} ]";
    echo "<hr>";

    $Template="
    <div class='BlogWrapper'>
    <div class='BlogTitle'>:Title</div>
    <div class='BlogBody'>:Body</div>
    <br>
    <div class='BlogCreation'>by :Username - :CreationDate</div>
    </div>
    ";

    $Blog->Display_Blog_Page($Blog->Get_Page(),$Template);

    if (isset($_GET['from_blog_page'])) { echo "Return to <a href='?view=blog&page={$_GET['from_blog_page']}'>Blog Page {$_GET['from_blog_page']}</a>"; }