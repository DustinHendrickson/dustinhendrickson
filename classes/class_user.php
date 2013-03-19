<?php

/*
 * This class defines the objects and methods of the Users
 * in the system.
 */

/**
 * Description of class_user
 *
 * @author Dustin
 */
class User {
    //Non User Variables
    private $Connection;
    //User Variables
    private $ID;
    public $Username;
    public $First_Name;
    public $Last_Name;
    private $Password;
    public $Permissions;
    public $Account_Last_Login;
    public $Account_Created;
    public $Account_Locked;
    
    //Initial function to search the database for the desired user and populate this class object. 
    private function Set_User_Info() {
        
        $User_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE ID = ".$this->ID." LIMIT 1");
        
        while ($User_Row = mysql_fetch_assoc($User_Result)) {
            $this->Username = $User_Row["Username"];
            $this->First_Name = $User_Row["First_Name"];
            $this->Last_Name = $User_Row["Last_Name"];
            $this->Password = $User_Row["Password"];
            $this->Permissions = $User_Row["Permissions"];
            $this->Account_Last_Login = strtotime($User_Row["Account_Last_Login"]);
            $this->Account_Created = strtotime($User_Row["Account_Created"]);
            $this->Account_Locked = $User_Row["Account_Locked"];
        }
    }
    
    function __construct($ID) {
        $this->Connection = new Connection();
        $this->ID = $ID;
        $this->Set_User_Info();
    }
    
    //Returns the Full Name of the user.
    // @returns string
    public function Get_Full_Name() {
        return $this->First_Name . " " . $this->Last_Name;
    }

    //This Function checks the Users permissions and returns them in a readable format.
    // @returns string
    public function Get_Permissions() {
        $Permissions_Array = explode(",",$this->Permissions);
        $Return_Array = array();

        foreach ($Permissions_Array as $Permission) {
            switch ($Permission) {
                case 1:
                    array_push($Return_Array,"Admin");
                    break;
                case 2:
                    array_push($Return_Array,"Manager");
                    break;
                case 3:
                    array_push($Return_Array,"Employee");
                    break;
                case 4:
                    array_push($Return_Array,"User");
                    break;
            }
        }

        $Return_String = implode(", ",$Return_Array);

        return $Return_String;
    }

    //Converts the Datbases 0,1 into Locked,Active.
    // @returns string
    public function Get_Account_Status() {
        if ($this->Account_Locked = 1) { return "Active"; } else { return "Locked"; }
    }
}
?>