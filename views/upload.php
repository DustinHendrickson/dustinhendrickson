<?php
Functions::Check_User_Permissions_Redirect("Staff");
?>
<div class='ContentHeader'>File Upload</div><hr>
<?php
switch ($_POST['Mode'])
    {   // Check to make sure we've triggered an upload call.
        case 'Upload':
            $User = new User($_SESSION['ID']);

            // We check to make sure that there is no error in the process.
            if ($_FILES["file"]["error"] > 0) {
                echo "Error: " . $_FILES["file"]["error"] . "<br>";
                echo "<hr><br>";
                Toasts::addNewToast('Something went wrong with the file upload. Please try again.','error');
                Write_Log("upload", "UPLOAD:ERROR " . $User->Username . " tried uploading file " . $_FILES["file"]["name"] . " and ran into error " . $_FILES["file"]["error"]);
            } else {

                // Make sure there is not already a file with that name on the system.
                if (file_exists("/var/www/uploads/" . $_FILES["file"]["name"])) {
                    echo "ERROR: " . $_FILES["file"]["name"] . " already exists. ";
                    Toasts::addNewToast('That file already exists, please pick a different file or rename it.','error');
                    Write_Log("upload", "UPLOAD:ERROR " . $User->Username . " tried uploading file " . $_FILES["file"]["name"] . " but it already exists.");
                } else {

                    // Everything looks good so we upload the file and output some statistics for the user.
                    echo "<b> File Stats </b><br>";
                    echo "<b>File Name:</b> " . $_FILES["file"]["name"] . "<br>";
                    echo "<b>Type:</b> " . $_FILES["file"]["type"] . "<br>";
                    echo "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " kB<br><br>";

                    // Here we move the file from the tmp directory to the server uploads directory and link the file to the user.
                    move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/uploads/" . $_FILES["file"]["name"]);
                    echo "<b>Link to file:</b> " . "<a target='_blank' href='https://dustinhendrickson.com/uploads/" . $_FILES["file"]["name"] . "'> https://dustinhendrickson.com/uploads/" . $_FILES["file"]["name"] ."</a>";
                    Toasts::addNewToast('File upload was successful.','success');
                    Write_Log("upload", "UPLOAD:SUCCESS " . $User->Username . " successfully uploaded file " . $_FILES["file"]["name"]);
                }

            echo "<hr><br>";
            }
    }

?>
<form action="?view=upload" method="post" enctype="multipart/form-data">
<label for="file">File to upload:</label>
<input type="file" name="file" id="file"><br><br>
<input type="submit" name="Mode" value="Upload">
</form>
<hr><br>
<b>Files currently uploaded</b><br>
<iframe src="https://dustinhendrickson.com/uploads/" width="100%" height="70%"></iframe>
