<?php

class Blog
{
    //Internal Variables
    private $Connection;
    public $Message;
    public $Message_Type;
    //Pagination Variables
    public $Paginated=false;
    public $Pages;
    public $PagesMax;


    //Construction Method
    function __construct()
    {
        $this->Connection = new Connection();
    }

    //Data Manipulation
    function Add_Post($UserID,$Title,$Body)
    {
        $Post_Array = array (':UserID'=>$UserID,':Title'=>$Title,':Body'=>$Body);
        $this->Connection->Custom_Execute("INSERT INTO blog (UserID, Title, Body, Creation_Date) VALUES (:UserID, :Title, :Body, NOW()) ", $Post_Array, true);

        $this->Message='Blog post successfully added.';
        $this->Message_Type='Success';
    }

    function Delete_Post($PostID)
    {
        $Post_Array = array ('PostID'=>$PostID);
        $this->Connection->Custom_Execute("DELETE FROM blog WHERE ID=:PostID", $Post_Array);

        $this->Message="Blog post [{$PostID}] successfully deleted.";
        $this->Message_Type='Success';
    }

    function Edit_Post($PostID,$UserID,$Title,$Body)
    {
        $Post_Array = array (':PostID'=>$PostID,':UserID'=>$UserID,':Title'=>$Title,':Body'=>$Body);
        $this->Connection->Custom_Execute("UPDATE blog SET UserID=:UserID, Title=:Title, Body=:Body WHERE ID=:PostID", $Post_Array);
        
        $this->Message="Blog post [{$PostID}] successfully edited.";
        $this->Message_Type='Success';
    }

    function Write_Pagination_Nav(){
        echo "<div class='Pagination'>";
        //echo "<a href='?view={$View}&page=1'><< </a>";
        for ($i=1; $i<=$this->PagesMax; $i++)
        {
            $View = Functions::Get_View();
            echo "<a href='?view={$View}&page={$i}'>{$i}</a>";
            if ($i != $this->PagesMax) {echo " | ";}
        }
        //echo "<a href='?view={$View}&page={$this->PagesMax}'> >></a>";
        echo "</div>";
    }

    function Get_Page() {
        if(isset($_GET['page'])){$Page=$_GET['page'];} else {$Page=1;}

        return $Page;
    }

    function Display_Page($Page,$Template) {
        if(isset($this->Pages[$Page]))
        {

            foreach ($this->Pages[$Page] as $Blog_Page)
            {
                $User = new User($Blog_Page['UserID']);

                //Templating Engine
                $Template_Replacement = array(
                    ':ID' => $Blog_Page['ID'],
                    ':Title' => $Blog_Page['Title'],
                    ':Body' => $Blog_Page['Body'],
                    ':CreationDate' => $Blog_Page['Creation_Date'],
                    ':Username' => $User->Username,
                    ':UserID' => $User->ID
                );

                $Template_Return = str_replace(array_keys($Template_Replacement),array_values($Template_Replacement),$Template);

                echo $Template_Return;
            }
        } else {
            Write_Log('php',"Trying to access a blog page that doesn't exist.");
            echo "<div class='Error'>You are trying to access a blog page that doesn't exist.</div>";
        }
    }

    //Data Views
    function Get_Posts($Paginate=false,$PerPage=5,$Limit = 0,$OrderBy="DESC") {//Returns Array of Posts.
        //We can't prepare ORDER BY in PDO, so we have to verify ourselves.
        if ($OrderBy != "ASC" && $OrderBy != "DESC") {$OrderBy='';}

        if ($Limit != 0) {
            $Post_Array = array (':Limit'=>$Limit);
            $Post_Result = $this->Connection->Custom_Query("SELECT * FROM blog ORDER BY Creation_Date {$OrderBy} LIMIT :Limit", $Post_Array, true);
        } else {
            $Post_Array = array ();
            $Post_Result = $this->Connection->Custom_Query("SELECT * FROM blog ORDER BY Creation_Date {$OrderBy}", $Post_Array, true);
        }

        //Populate This Blog's Pages.
        if ($Paginate = true){
            $this->Paginated=true;
            $CurrentPage=1;
            $RowsLoaded=1;
            //Here we get the user config for items per page to display, if not logged in, set 5
            $User = new User($_SESSION['ID']);
            if(isset($User->Config_Settings['Items_Per_Page'])) {$PerPage=$User->Config_Settings['Items_Per_Page'];}

                //Loop through each row
                foreach($Post_Result as $Post_Row){
                    //Adding this blog row to the current page.
                    if(!isset($this->Pages[$CurrentPage])) {$this->Pages[$CurrentPage] = array();}
                    array_push($this->Pages[$CurrentPage], $Post_Row);

                    //Control the increments for pagination
                    if ($RowsLoaded < $PerPage){
                        $RowsLoaded++;
                    } else {
                        $RowsLoaded = 1;
                        $CurrentPage++;
                    }
                }

            //We need to make sure the current page array is set, otherwise decrease it by one.
            if(!isset($this->Pages[$CurrentPage])) { $CurrentPage = $CurrentPage-1; }
            //We're done, so we set the last page so we can loop later. 
            $this->PagesMax = $CurrentPage;
        }

        return $Post_Result;
    }

}
