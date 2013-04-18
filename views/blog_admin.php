<?php
    Functions::Verify_Session_Redirect();
    Functions::Check_User_Permissions_Redirect("Admin");

    //Logic to perform based on post data.
    $String_Protector_Array = array("<script","</script>","('","')");
    switch ($_POST['Mode']){

        case 'Edit':
            $Blog = new Blog();
            $Blog->Edit_Post($_POST['postID'],$_POST['userID'],str_replace($String_Protector_Array,"",$_POST['Title']),str_replace($String_Protector_Array,"",$_POST['Body']));
            break;

        case 'Add':
            $Blog = new Blog();
            $Blog->Add_Post($_POST['userID'],str_replace($String_Protector_Array,"",$_POST['Title']),str_replace($String_Protector_Array,"",$_POST['Body']));
            break;

        case 'Delete':
            $Blog = new Blog();
            $Blog->Delete_Post($_POST['postID']);
            break;

    }
    //Display any messages from the logic.
    if (isset($Blog->Message)) { echo "<div class='{$Blog->Message_Type}'>".$Blog->Message."</div>"; unset($Blog->Message); }


    //New Blog Entry
    echo "
    <form action='?view=blog_admin&page={$_GET['page']}' method='post'>
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
    <hr>
    ";


    //Build Blog data and display posts for editing.
    $Blog = new Blog();
    $Blog_Pages = $Blog->Get_Posts(true,5);

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
                    <input name='Title' type='text' value='{$Blog_Page['Title']}'>
                </td>
            </tr>
            <tr>
                <td>
                    Body: 
                </td>
                <td>
                    <textarea name='Body' type='text'>{$Blog_Page['Body']}</textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input name='postID' type='hidden' value='{$Blog_Page['ID']}'>
                    <input name='userID' type='hidden' value='{$User->ID}'>
                </td>
            </tr>
        </table>
        <div class='BlogCreation'>Post ID[{$Blog_Page['ID']}] by {$User->Username} - {$Blog_Page['Creation_Date']}</div>
        <input type='submit' value='Edit' name='Mode'>
        <input type='submit' value='Delete' name='Mode'>
    </form>
    </div>
    <br>
    ";

    $Blog->Display_Page($Blog->Get_Page(),$Template);

    $Blog->Write_Pagination_Nav();