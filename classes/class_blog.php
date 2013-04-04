<?php

class Blog
{
    //Blog Variables
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
    function Display_Post($PostID)
    {

    }

    function Display_All_Posts($Limit = 0)
    {

    }

    //Internal Functions
    function Get_Post_User()
    {

    }

}
