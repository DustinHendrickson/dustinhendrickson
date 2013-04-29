<?php

    $Blog = new Blog();
    $Blog->Get_Posts();

    echo "Latest Blog Posts... ";
    echo "<hr>";
    $Blog->Write_Pagination_Nav();

    $Template="
    <div class='BlogWrapper'>
    <div class='BlogTitle'>:Title</div>
    <div class='BlogBody'>:Body</div>
    <br>
    <div class='BlogCreation'>by :Username - :CreationDate</div>
    </div>
    ";

    $Blog->Display_Page($Blog->Get_Page(),$Template);

    $Blog->Write_Pagination_Nav();