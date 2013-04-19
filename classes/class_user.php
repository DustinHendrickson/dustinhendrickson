<?php

/*
 * This class defines the objects and methods of the Users
 * in the system.
 */

/**
 * 
 *
 * @author Dustin
 */
class User {
    //Non User Variables
    private $Connection;
    //User Variables
    public $ID;
    public $Username;
    public $First_Name;
    public $Last_Name;
    private $Password;
    public $Permissions;
    public $Account_Last_Login;
    public $Account_Created;
    public $Account_Locked;
    public $Config_Settings; //TO DO - Add configuration options to the system.
    
    //Initial function to search the database for the desired user and populate this class object. 
    private function Set_User_Info() {
        
        $User_Array = array (':ID'=>$this->ID);
        $User_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE ID = :ID LIMIT 1", $User_Array);

            $this->Username = $User_Result["Username"];
            $this->First_Name = $User_Result["First_Name"];
            $this->Last_Name = $User_Result["Last_Name"];
            $this->Password = $User_Result["Password"];
            $this->Permissions = $User_Result["Permissions"];
            $this->Account_Last_Login = strtotime($User_Result["Account_Last_Login"]);
            $this->Account_Created = strtotime($User_Result["Account_Created"]);
            $this->Account_Locked = $User_Result["Account_Locked"];
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
    public function Get_Permissions($ReturnType='String') {
        $Permissions_Array = explode(",",$this->Permissions);
        $Return_Array = array();

        foreach ($Permissions_Array as $Permission) {
            switch ($Permission) {
                case 1:
                    array_push($Return_Array,"Admin");
                    break;
                case 2:
                    array_push($Return_Array,"Staff");
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

        switch($ReturnType) {
            case 'String':
                return $Return_String;
                break;
            case 'Array':
                return $Return_Array;
                break;
        }

    }

    //Converts the Datbases 0,1 into Locked,Active.
    // @returns string
    public function Get_Account_Status() {
        if ($this->Account_Locked = 1) { return "Active"; } else { return "Locked"; }
    }
}
?>