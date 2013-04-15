<?php

class Blog
{
    //Internal Variables
    private $Connection;
    public $ErrorMessage;


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

    }

    function Delete_Post($PostID)
    {
        $Post_Array = array ('PostID'=>$PostID);
        $this->Connection->Custom_Execute("DELETE FROM blog WHERE ID=:PostID", $Post_Array);

    }

    function Edit_Post($PostID,$UserID,$Title,$Body)
    {
        $Post_Array = array (':PostID'=>$PostID,':UserID'=>$UserID,':Title'=>$Title,':Body'=>$Body);
        $this->Connection->Custom_Execute("UPDATE blog SET UserID=:UserID, Title=:Title, Body=:Body WHERE ID=:PostID", $Post_Array);

    }

    //Data Views
    function Get_Posts($Limit = 0) //Returns Array of Posts.
    {
        if ($Limit != 0) {
            $Post_Array = array (':Limit'=>$Limit);
            $Post_Result = $this->Connection->Custom_Query("SELECT * FROM blog LIMIT :Limit", $Post_Array, true);
        } else {
            $Post_Array = array ();
            $Post_Result = $this->Connection->Custom_Query("SELECT * FROM blog", $Post_Array, true);
        }

        return $Post_Result;
    }

    //Internal Functions

}
