<?php

class Blog
{
    //Internal Variables
    private $Connection;
    private $User;
    //Pagination Variables
    public $Blog_Page; //This is an array of all blog posts on the selected page. Example: $Blog_Page[Page#][BlogPost#] $Blog_Page[1][0] -> Will return the first post on page 1.
    public $Total_Pages;
    public $Comments; //Array of comments on the blog post.


    // Construction Method
    function __construct()
    {
        $this->Connection = new Connection();

        //We populate the pagination when the object is created.
        //Here we get the user config for items per page to display, if not logged in, set to default.
        $this->User = new User($_SESSION['ID']);
        if(isset($this->User->Config_Settings['Items_Per_Page'])) { $PerPage=$this->User->Config_Settings['Items_Per_Page']; }

        //If the user config exists we'll populate the posts with that, otherwise use the defaults.
        if (isset($PerPage)) {
            $this->Get_Posts($PerPage);
        } else {
            $this->Get_Posts();
        }
    }

    // Data Manipulation
    function Add_Post($UserID,$Title,$Body)
    {
        $Post_Array = array (':UserID'=>$UserID,':Title'=>$Title,':Body'=>$Body);
        $Results = $this->Connection->Custom_Execute("INSERT INTO blog (UserID, Title, Body, Creation_Date) VALUES (:UserID, :Title, :Body, NOW())", $Post_Array, true);

        if ($Results) {
            Toasts::addNewToast('Blog post successfully added.','success');
            $this->User->Add_Achievement("Add Blog Post");
        } else {
            Toasts::addNewToast('Blog post encountered an error.','error');
        }
    }

    function Delete_Post($PostID)
    {
        $Post_Array = array (':PostID'=>$PostID);
        $Results = $this->Connection->Custom_Execute("DELETE FROM blog WHERE ID=:PostID", $Post_Array);

        if ($Results){
            // First we buildup an array of all comments on the post and then delete them.
            $BlogPostComments = $this->Get_All_Comments_For_Blog_Post($PostID);
            foreach ($BlogPostComments as $BlogPostComment){
                $this->Delete_Comment($BlogPostComment['CommentID'], false);
            }

            Toasts::addNewToast('Blog post ['.$PostID .'] successfully deleted.','success');
            Toasts::addNewToast('Blog comments successfully deleted.','success');
            $this->User->Add_Achievement("Delete Blog Post");
        } else {
            Toasts::addNewToast('Blog post delete ['.$PostID .'] encountered an error.','error');
        }
    }

    function Edit_Post($PostID,$Title,$Body)
    {
        $Post_Array = array (':PostID'=>$PostID,':Title'=>$Title,':Body'=>$Body);
        $Results = $this->Connection->Custom_Execute("UPDATE blog SET Title=:Title, Body=:Body WHERE ID=:PostID", $Post_Array);

        if ($Results){
            Toasts::addNewToast('Blog post ['.$PostID .'] successfully edited.','success');
        } else {
            Toasts::addNewToast('Blog post edit['.$PostID .'] encountered an error.','error');
        }
    }

    // Comment Data Manipulation
    function Add_Comment($CommentUserID,$BlogPostID,$CommentText)
    {
        if ($CommentText != '') {
            $Comment_Array = array (':CommentUserID'=>$CommentUserID,':BlogPostID'=>$BlogPostID,':CommentText'=>$CommentText);
            $Results = $this->Connection->Custom_Execute("INSERT INTO blog_comments (CommentUserID, BlogPostID, CommentText, CommentDate) VALUES (:CommentUserID, :BlogPostID, :CommentText, NOW())", $Comment_Array, true);
        }

        if ($Results && $CommentText != '') {
            Toasts::addNewToast('Blog comment successfully added.','success');
            $this->User->Add_Achievement("Add Comment");
        } else {
            Toasts::addNewToast('Blog comment encountered an error.','error');
        }
    }

    function Delete_Comment($CommentID,$ShowToast=true)
    {
        $Comment_Array = array (':CommentID'=>$CommentID);
        $Results = $this->Connection->Custom_Execute("DELETE FROM blog_comments WHERE CommentID=:CommentID", $Comment_Array);

        if ($Results){
            if ($ShowToast==true) {
            Toasts::addNewToast('Blog comment successfully deleted.','success');
            }
            $this->User->Add_Achievement("Delete Comment");
        } else {
            if ($ShowToast==true) {
            Toasts::addNewToast('Blog comment delete encountered an error.','error');
            }
        }
    }


    function Edit_Comment($CommentID,$CommentText)
    {
        if ($CommentText != '') {
            $Comment_Array = array (':CommentID'=>$CommentID,':CommentText'=>$CommentText);
            $Results = $this->Connection->Custom_Execute("UPDATE blog_comments SET CommentText=:CommentText WHERE CommentID=:CommentID", $Comment_Array);
        }

        if ($Results && $CommentText != ''){
            Toasts::addNewToast('Blog comment successfully edited.','success');
        } else {
            Toasts::addNewToast('Blog comment edit encountered an error.','error');
        }
    }

    // Writes out the links for all blog pages after Get_Posts() has been run.
    function Write_Pagination_Nav()
    {
        //Make sure we only write out if there's a page generated.
        if ($this->Total_Pages > 0) {
            echo "<div class='Pagination'>";
            for ($i=1; $i<=$this->Total_Pages; $i++)
            {
                $View = Functions::Get_View();
                if ($this->Get_Page() == $i) {echo "<div class='Current_Page'>";} else {echo "<div class='Page'>";}
                echo "<a href='?view={$View}&page={$i}'>{$i}</a>";
                if ($this->Get_Page = $i) {echo "</div>";}
                //if ($i != $this->Total_Pages) {echo " | ";}
            }
            echo "</div>";
            echo "<div class='Clear'></div>";
        }
    }


    // Grabs the current page.
    function Get_Page()
    {
        if(isset($_GET['page'])){$Page=$_GET['page'];} else {$Page=1;}

        return $Page;
    }

    // Grabs the currently viewed Blog ID.
    function Get_Single_View_Blog_ID()
    {
        if(isset($_GET['blog_id'])){$BlogID=$_GET['blog_id'];} else {$BlogID=0;}

        return $BlogID;
    }

    // Returns the number of comments associated with a blog post id.
    function Get_Blog_Post_Comment_Count($BlogPostID)
    {
        $SQL = "SELECT COUNT(*) FROM blog_comments WHERE BlogPostID = :BlogPostID";
        $Array = array(':BlogPostID' => $BlogPostID);
        $Result = $this->Connection->Custom_Count_Query($SQL, $Array);

        return $Result[0];
    }

    // Returns an array of all blog comments on a blog post id.
    function Get_All_Comments_For_Blog_Post($BlogPostID)
    {
        $SQL = "SELECT * FROM blog_comments WHERE BlogPostID = :BlogPostID";
        $Array = array(':BlogPostID' => $BlogPostID);
        $Result = $this->Connection->Custom_Query($SQL, $Array, true);

        return $Result;
    }

    //This functions takes an array of blog post data and formats it versus a template object.
    private function Display_Blog_Page_Post($Blog_Post,$Template)
    {
        //Setup a new object for the Blog Post author id.
        $User = new User($Blog_Post['UserID']);

        //Template Engine
        //This is where we setup the ID's
        //and their values that will get replaced.
        $Template_Replacement = array(
            ':ID' => $Blog_Post['ID'],
            ':Title' => $Blog_Post['Title'],
            ':Body' => $Blog_Post['Body'],
            ':CreationDate' => date('h:i A l F j, Y', strtotime($Blog_Post['Creation_Date'])),
            ':Username' => $User->Username,
            ':UserID' => $User->ID,
            ':CommentCount' => $this->Get_Blog_Post_Comment_Count($Blog_Post['ID'])
        );

        //Replace the template strings with their values.
        $Template_Return = str_replace(array_keys($Template_Replacement),array_values($Template_Replacement),$Template);

        echo $Template_Return;

    }

    public function Display_Blog_Post_Comments($BlogPostID,$Template)
    {
        $Copied_Template = $Template;
        $SQL = "SELECT * FROM blog_comments WHERE BlogPostID = :BlogPostID ORDER BY CommentDate DESC";
        $Array = array(':BlogPostID' => $BlogPostID);
        $Result = $this->Connection->Custom_Query($SQL, $Array, true);

        // Display each comment for the post.
        if(isset($Result)) {
            foreach($Result as $Row){

                $CommentUser = new User($Row['CommentUserID']);
                $SessionUser = new User($_SESSION['ID']);

                //Template Engine
                //This is where we setup the ID's
                //and their values that will get replaced.
                $Template_Replacement = array(
                    ':CommentUsername' => $CommentUser->Username,
                    ':CommentText' => $Row['CommentText'],
                    ':CommentDate' => date('h:i A l F j, Y', strtotime($Row['CommentDate'])),
                    ':CommentID' => $Row['CommentID'],
                    ':CommentUserID' => $Row['CommentUserID'],
                    ':BlogPostID' => $BlogPostID
                );

                //Here we check if the current user is the owner of the comment, if so, give them options to Edit or Delete.
                if ($SessionUser->ID == $CommentUser->ID){

                    $Template_Copy ="
                    <hr>
                    <form action='?view=blog_post&blog_id={$_GET['blog_id']}&from_blog_page={$_GET['from_blog_page']}' method='post'>
                    <div class='CommentWrapper'>
                    <div class='CommentUsername'>:CommentUsername - 
                        <input size='10' type='submit' value='Edit' name='Mode'>
                        <input size='10' style='color:red;' type='submit' value='Delete' name='Mode'>
                    </div>
                    <div class='CommentText'><textarea name='CommentText' type='text'>:CommentText</textarea></div><br>
                    <div class='CommentDate'>last updated :CommentDate</div>
                    <input name='CommentID' type='hidden' value=':CommentID'>
                    <input name='CommentUserID' type='hidden' value=':CommentUserID'>
                    <input name='BlogPostID' type='hidden' value=':BlogPostID'>
                    </div>
                    </form>
                    <hr>
                    ";

                    $Template_Replacement = array(
                    ':CommentUsername' => $CommentUser->Username,
                    ':CommentText' => $Row['CommentText'],
                    ':CommentDate' => date('h:i A l F j, Y', strtotime($Row['CommentDate'])),
                    ':CommentID' => $Row['CommentID'],
                    ':CommentUserID' => $Row['CommentUserID'],
                    ':BlogPostID' => $BlogPostID
                    );

                } else {
                    //Here we reset the template to it's original if no modifications were necessary
                    $Template_Copy = $Template;

                    //If the user has admin access, give them a button to delete the post.
                    if (Functions::Check_User_Permissions("Admin")){
                        $Template_Copy ="
                        <hr>
                        <form action='?view=blog_post&blog_id={$_GET['blog_id']}&from_blog_page={$_GET['from_blog_page']}' method='post'>
                        <div class='CommentWrapper'>
                        <div class='CommentUsername'>:CommentUsername - 
                            <input size='10' style='color:red;' type='submit' value='Delete' name='Mode'>
                        </div>
                        <div class='CommentText'>:CommentText</div><br>
                        <div class='CommentDate'>last updated :CommentDate</div>
                        <input name='CommentID' type='hidden' value=':CommentID'>
                        <input name='CommentUserID' type='hidden' value=':CommentUserID'>
                        <input name='BlogPostID' type='hidden' value=':BlogPostID'>
                        </div>
                        </form>
                        <hr>
                        ";
                    }
                }

                //Replace the template strings with their values.
                $Template_Return = str_replace(array_keys($Template_Replacement),array_values($Template_Replacement),$Template_Copy);

                echo $Template_Return;

            }
        }

    }

    //Displays the blog entries from the entered page, uses a templating
    //system to replace values in a string with their blog values. Requires
    //Get_Posts() to have been run previous to using this method.
    public function Display_Blog_Page($Page,$Template)
    {
        if(isset($this->Blog_Page[$Page]))
        {
            //Loop through each page of the blog object.
            foreach ($this->Blog_Page[$Page] as $Blog_Post)
            {
                $this->Display_Blog_Page_Post($Blog_Post,$Template);
            }
        } else {
            Write_Log('php',"Trying to access a blog page that doesn't exist.");
            echo "<div class='Error'>You are trying to access a blog page that doesn't exist.</div>";
        }
    }

    //Returns an array of all blog posts, automatically paginates and can be used
    //with Display_Page($PageNumber)
    public function Get_Posts($PerPage=5, $Limit=0, $OrderBy="DESC", $GetID=0) //Returns Array of Posts.
    {
        //Clear the current page incase this is a forced post get.
        //Otherwise the array wouldnt get cleared and we'd have more
        //posts in there than we'd like.
        unset($this->Blog_Page);
        unset($this->Total_Pages);

        //We can't prepare ORDER BY in PDO, so we have to verify ourselves.
        //We also have to do some weird string buildup for this one.
        if ($OrderBy != "ASC" && $OrderBy != "DESC") {$OrderBy="";} else {$OrderBy=" " . $OrderBy;}

        if ($OrderBy != "") {
            $OrderBy_Text = "ORDER BY Creation_Date{$OrderBy}";
        }

        if ($Limit != 0) {
            $Limit_Text = "LIMIT {$Limit}";
        }

        if ($GetID != 0) {
            $ID_Text = "WHERE ID={$GetID}";
        }

        $Post_SQL = "SELECT * FROM blog {$ID_Text} {$Limit_Text} {$OrderBy_Text}";
        $Post_Array = array();
        $Post_Result = $this->Connection->Custom_Query($Post_SQL, $Post_Array, true);

        //Populate This Blog's Starting Pages.
        $CurrentPage=1;
        $RowsLoaded=1;

        //Loop through each result row
        if(isset($Post_Result)) {
            foreach($Post_Result as $Post_Row){
                //Adding this blog row to the current page.
                if(!isset($this->Blog_Page[$CurrentPage])) {$this->Blog_Page[$CurrentPage] = array();}
                array_push($this->Blog_Page[$CurrentPage], $Post_Row);

                //Control the increments for pagination
                if ($RowsLoaded < $PerPage){
                    $RowsLoaded++;
                } else {
                    $RowsLoaded = 1;
                    $CurrentPage++;
                }
            }
        }

        //We need to make sure the current page array is set, otherwise decrease it by one.
        if(!isset($this->Blog_Page[$CurrentPage])) { $CurrentPage = $CurrentPage-1; }
        //We're done, so we set the last page so we can loop later.
        $this->Total_Pages = $CurrentPage;

        return $Post_Result;
    }

}
