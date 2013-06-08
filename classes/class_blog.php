<?php

class Blog
{
    //Internal Variables
    private $Connection;
    public $Message;
    public $Message_Type;
    //Pagination Variables
    public $Blog_Page; //This is an array of all blog posts on the selected page. Example: $Blog_Page[Page#][BlogPost#] $Blog_Page[1][0] -> Will return the first post on page 1.
    public $Total_Pages;


    //Construction Method
    function __construct()
    {
        $this->Connection = new Connection();
        //We populate the pagination when the object is created.
        $this->Get_Posts();
    }

    //Displays the current message set for this object then unsets it.
    function Display_Message()
    {
        if (isset($this->Message))
            {
                echo "<div class='{$this->Message_Type}'>" . $this->Message . "</div>";
                unset($this->Message);
            }
    }

    //Data Manipulation
    function Add_Post($UserID,$Title,$Body)
    {
        $Post_Array = array (':UserID'=>$UserID,':Title'=>$Title,':Body'=>$Body);
        $Results = $this->Connection->Custom_Execute("INSERT INTO blog (UserID, Title, Body, Creation_Date) VALUES (:UserID, :Title, :Body, NOW()) ", $Post_Array, true);

        if ($Results) {
            $this->Message='Blog post successfully added.';
            $this->Message_Type='Success';
        } else {
            $this->Message='Blog post encountered an error.';
            $this->Message_Type='Error';
        }
    }

    function Delete_Post($PostID)
    {
        $Post_Array = array (':PostID'=>$PostID);
        $Results = $this->Connection->Custom_Execute("DELETE FROM blog WHERE ID=:PostID", $Post_Array);

        if ($Results){
            $this->Message="Blog post [{$PostID}] successfully deleted.";
            $this->Message_Type='Success';
        } else {
            $this->Message='Blog post delete [{$PostID}] encountered an error.';
            $this->Message_Type='Error';
        }
    }

    function Edit_Post($PostID,$UserID,$Title,$Body)
    {
        $Post_Array = array (':PostID'=>$PostID,':UserID'=>$UserID,':Title'=>$Title,':Body'=>$Body);
        $Results = $this->Connection->Custom_Execute("UPDATE blog SET UserID=:UserID, Title=:Title, Body=:Body WHERE ID=:PostID", $Post_Array);

        if ($Results){
            $this->Message="Blog post [{$PostID}] successfully edited.";
            $this->Message_Type='Success';
        } else {
            $this->Message='Blog post edit [{$PostID}] encountered an error.';
            $this->Message_Type='Error';
        }
    }

    //Writes out the links for all blog pages after Get_Posts() has been run.
    function Write_Pagination_Nav()
    {
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


    //Grabs the current page.
    function Get_Page()
    {
        if(isset($_GET['page'])){$Page=$_GET['page'];} else {$Page=1;}

        return $Page;
    }

    //This functions takes an array of blog post data and formats it versus a template object.
    private function Display_Blog_Page_Post($Blog_Post,$Template)
    {
        //Setup a new object for the Blog Post author id.
        $User = new User($Blog_Post['UserID']);

        //Templating Engine
        //This is where we setup the ID's
        //and their values that will get replaced.
        $Template_Replacement = array(
            ':ID' => $Blog_Post['ID'],
            ':Title' => $Blog_Post['Title'],
            ':Body' => $Blog_Post['Body'],
            ':CreationDate' => $Blog_Post['Creation_Date'],
            ':Username' => $User->Username,
            ':UserID' => $User->ID
        );

        //Replace the template strings with their values.
        $Template_Return = str_replace(array_keys($Template_Replacement),array_values($Template_Replacement),$Template);

        echo $Template_Return;

    }

    //Displays the blog entries from the entered page, uses a templating
    //system to replace values in a string with their blog values. Requires
    //Get_Posts() to have been run previous to using this method.
    function Display_Blog_Page($Page,$Template)
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
    function Get_Posts($PerPage=5, $Limit=0, $OrderBy="DESC", $GetID=0, $Use_User_Settings=true) //Returns Array of Posts.
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

        //Populate This Blog's Pages.
        $CurrentPage=1;
        $RowsLoaded=1;
        //Here we get the user config for items per page to display, if not logged in, set to default.
        $User = new User($_SESSION['ID']);
        if(isset($User->Config_Settings['Items_Per_Page'])) {$PerPage=$User->Config_Settings['Items_Per_Page'];}

            //Loop through each row
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