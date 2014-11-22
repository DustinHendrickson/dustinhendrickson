<?php

    Functions::Check_User_Permissions_Redirect("Admin");
    Functions::Prepare_TinyMCE();

    //Logic to perform based on post data.
    $String_Protector_Array = array("<script","</script>","<source","<audio","('","')", "window.location", "onerror=");
    switch ($_POST['Mode'])
    {
        case 'Edit':
            $Blog = new Blog();
            $Blog->Edit_Post($_POST['postID'],str_replace($String_Protector_Array,"",$_POST['Title']),str_replace($String_Protector_Array,"",$_POST['Body']));
            break;

        case 'Add':
            $Blog = new Blog();
            $Blog->Add_Post($_SESSION['ID'],str_replace($String_Protector_Array,"",$_POST['Title']),str_replace($String_Protector_Array,"",$_POST['Body']));
            break;

        case 'Delete':
            $Blog = new Blog();
            $Blog->Delete_Post($_POST['postID']);
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
                </td>
                <td>
                    <div class='BlogCreation'>Post ID[:ID] by :Username at :CreationDate</div>
                </td>
            </tr>
            <tr>
                <td>
                    Title:
                </td>
                <td>
                    <input name='Title' style='width:80%' type='text' value=':Title'> <input type='submit' size='10' value='Edit' name='Mode'> <input size='10' style='color:red;' type='submit' value='Delete' name='Mode'>
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
                </td>
            </tr>
        </table>
    </form>
    </div>
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
                        <input name='Title' style='width:80%' type='text'> <input size='10' type='submit' value='Add' name='Mode'>
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
            </table>
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
