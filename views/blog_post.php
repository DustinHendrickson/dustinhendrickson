<?php

    //Logic to perform based on post data.
    $String_Protector_Array = array("<script","</script>","<source","<audio","('","')", "window.location", "onerror=");
    switch ($_POST['Mode'])
    {
        case 'Edit':
            $Blog = new Blog();
            $Blog->Edit_Comment($_POST['CommentID'],str_replace($String_Protector_Array,"",$_POST['CommentText']));
            break;

        case 'Add':
            $Blog = new Blog();
            $Blog->Add_Comment($_SESSION['ID'], str_replace($String_Protector_Array,"",$_POST['BlogPostID']), str_replace($String_Protector_Array,"",$_POST['CommentText']) );
            break;

        case 'Delete':
            $Blog = new Blog();
            $Blog->Delete_Comment($_POST['CommentID']);
            break;
    }


    $Blog = new Blog();
    $Blog->Get_Posts(1,1,'',$Blog->Get_Single_View_Blog_ID());

    echo "<div class='ContentHeader'>Single Blog Post View</div>";
    echo "<hr>";

    $Template="
    <div class='BlogWrapper'>
    <div class='BlogTitle'>:Title</div>
    <div class='BlogBody'>:Body</div>
    <br>
    <div class='BlogCreation'>by :Username - :CreationDate</div>
    </div>
    ";


    $Comment_Template ="
    <hr>
    <div class='CommentWrapper'>
    <div class='CommentUsername'>:CommentUsername</div>
    <div class='CommentText'>:CommentText</div><br>
    <div class='CommentDate'>last updated :CommentDate</div>
    <input name='CommentID' type='hidden' value=':CommentID'>
    <input name='CommentUserID' type='hidden' value=':CommentUserID'>
    <input name='BlogPostID' type='hidden' value=':BlogPostID'>
    </div>
    <hr>
    ";


    //Display Blog Page Post
    $Blog->Display_Blog_Page($Blog->Get_Page(),$Template);

    if (Functions::Check_User_Permissions("User")){
        $User = new User($_SESSION['ID']);
        echo "
        <div class='BlogComments'><b>Add A Comment</b></div><hr>
        <form action='?view=blog_post&blog_id={$_GET['blog_id']}&from_blog_page={$_GET['from_blog_page']}' method='post'>
        <div class='CommentWrapper'>
        <div class='CommentUsername'>{$User->Username} - 
            <input size='10' type='submit' value='Add' name='Mode'>
        </div>
        <div class='CommentText'><textarea name='CommentText' type='text'></textarea></div><br>
        <input name='CommentUserID' type='hidden' value='{$User->ID}'>
        <input name='BlogPostID' type='hidden' value='{$Blog->Get_Single_View_Blog_ID()}'>
        </div>
        </form>
        ";
    }

    //If the Blog Post has comments, let's loop through each one and display it.
    //Additionally we display a way to add a new comment if the user is logged in.
    if ($Blog->Get_Blog_Post_Comment_Count($Blog->Get_Single_View_Blog_ID()) >= 1){
        echo "<div class='BlogComments'><b>Comments - " . $Blog->Get_Blog_Post_Comment_Count($Blog->Get_Single_View_Blog_ID()) . "</b></div>";
        $Blog->Display_Blog_Post_Comments($Blog->Get_Single_View_Blog_ID(), $Comment_Template);
    }

    //Print out our return to blog page listing if we got here from a blog post link.
    if (isset($_GET['from_blog_page'])) { echo "<br>Return to <a href='?view=blog&page={$_GET['from_blog_page']}'>Blog Page {$_GET['from_blog_page']}</a>"; }