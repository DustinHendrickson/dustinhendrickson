<?php

    $Blog = new Blog();

    echo "<div class='ContentHeader'>Latest Blog Posts...</div>";
    echo "<hr>";
    $Blog->Write_Pagination_Nav();

    $Template="
    <div class='BlogWrapper'>
    <div class='BlogTitle'><a href='?view=blog_post&blog_id=:ID&from_blog_page={$Blog->Get_Page()}'>:Title</a></div>
    <div class='BlogCreation'>by :Username - :CreationDate</div>
    <div class='BlogBody'>:Body</div>
    <div class='BlogCommentLink'><a href='?view=blog_post&blog_id=:ID&from_blog_page={$Blog->Get_Page()}'>Comments: :CommentCount</a></div>
    </div>
    ";

    $Blog->Display_Blog_Page($Blog->Get_Page(),$Template);

    $Blog->Write_Pagination_Nav();
