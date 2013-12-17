<?php

    Functions::Check_User_Permissions_Redirect("Admin");
    Functions::Prepare_TinyMCE();

    //Logic to perform based on post data.
    $String_Protector_Array = array("<script","</script>","<source","<audio","('","')");
    switch ($_POST['Mode'])
    {
        case 'Edit':
            $Blog = new Blog();
            $Blog->Edit_Post($_POST['postID'],$_POST['userID'],str_replace($String_Protector_Array,"",$_POST['Title']),str_replace($String_Protector_Array,"",$_POST['Body']));
            $Blog->Display_Message();
            break;

        case 'Add':
            $Blog = new Blog();
            $Blog->Add_Post($_POST['userID'],str_replace($String_Protector_Array,"",$_POST['Title']),str_replace($String_Protector_Array,"",$_POST['Body']));
            $Blog->Display_Message();
            break;

        case 'Delete':
            $Blog = new Blog();
            $Blog->Delete_Post($_POST['postID']);
            $Blog->Display_Message();
            break;
    }

    //Build Blog data and page for editing.
    $Blog = new Blog();
    $Page = $Blog->Get_Page();

    //Front end to Edit or Delete a blog entry.
    $Template = "
    <div class='BlogWrapper'>
    <form action='?view=blog_admin&page={$Page}' method='post'>
        <table>
            <tr>
                <td>
                    Title:
                </td>
                <td>
                    <input name='Title' type='text' value=':Title'>
                </td>
            </tr>
            <tr>
                <td>
                    Body:
                </td>
                <td>
                    <textarea name='Body' type='text'>:Body</textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input name='postID' type='hidden' value=':ID'>
                    <input name='userID' type='hidden' value=':UserID'>
                </td>
                <td>
                    <div class='BlogCreation'>Post ID[:ID] by :Username - :CreationDate</div>
                    <input type='submit' value='Edit' name='Mode'>
                    <input type='submit' value='Delete' name='Mode'>
                </td>
            </tr>
        </table>
    </form>
    </div>
    <br>
    ";

    //New Blog Entry, we only show this on page 1.
    if($Page==1){
        echo "
        <div class='ContentHeader'>Add a new blog post.</div><hr>
        <div class='BorderBox'>
        <form action='?view=blog_admin&page={$Page}' method='post'>
            <table>
                <tr>
                    <td>
                        Title:
                    </td>
                    <td>
                        <input name='Title' type='text'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Body:
                    </td>
                    <td>
                        <textarea name='Body' type='text'></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name='userID' type='hidden' value='{$_SESSION['ID']}'>
                    </td>
                </tr>
            </table>
            <input type='submit' value='Add' name='Mode'>
        </form>
        </div>
        <br>
        <br>
        ";
    }

    echo "<div class='ContentHeader'>Edit an existing blog post.</div><hr>";
    echo "<div class='BorderBox'>";
    $Blog->Write_Pagination_Nav();

    $Blog->Display_Blog_Page($Blog->Get_Page(),$Template);

    $Blog->Write_Pagination_Nav();

    echo "</div>";
