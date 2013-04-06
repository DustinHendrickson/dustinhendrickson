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
    function Add_Post($UserID,$Subject,$Body)
    {

    }

    function Delete_Post($PostID)
    {

    }

    function Edit_Post($PostID,$UserID,$Subject,$Body)
    {

    }

    //Data Views
    function Get_Posts($Limit = 0) //Returns Array of Posts.
    {
        $Post_Array = array (':Limit'=>$this->Limit);
        $Post_Result = $this->Connection->Custom_Query("SELECT * FROM blog LIMIT :Limit", $User_Array);

        return $Post_Result;
    }

    //Internal Functions

}
