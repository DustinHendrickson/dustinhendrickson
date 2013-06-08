<?php

    $Blog = new Blog();

    echo "Latest Blog Posts... ";
    echo "<hr>";
    $Blog->Write_Pagination_Nav();

    $Template="
    <div class='BlogWrapper'>
    <div class='BlogTitle'><a href='?view=blog_post&blog_id=:ID'>:Title</a></div>
    <div class='BlogBody'>:Body</div>
    <br>
    <div class='BlogCreation'>by :Username - :CreationDate</div>
    </div>
    ";

    $Blog->Display_Blog_Page($Blog->Get_Page(),$Template);

    $Blog->Write_Pagination_Nav();