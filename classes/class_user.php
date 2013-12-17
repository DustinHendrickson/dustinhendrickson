<?php

/*
 * This class defines the objects and methods of the Users
 * in the system.
 *
 * TO DO: Rewrite permissions method to not suck.
 *
 */

/**
 * @author Dustin
 */
class User {
    // Non User Variables
    private $Connection;
    public $Message;
    public $Message_Type;
    // User Variables
    public $ID;
    public $Username;
    public $First_Name;
    public $Last_Name;
    private $Password;
    public $Permissions;
    public $Account_Last_Login;
    public $Account_Created;
    public $Account_Locked;
    public $Config_Settings = array();

    // Initial function to search the database for the desired user and populate this class object.
    private function Set_User_Info()
    {
        $User_Array = array (':ID'=>$this->ID);
        $User_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE ID = :ID LIMIT 1", $User_Array);

        $this->Username = $User_Result["Username"];
        $this->First_Name = $User_Result["First_Name"];
        $this->Last_Name = $User_Result["Last_Name"];
        $this->Password = $User_Result["Password"];
        $this->Permissions = $User_Result["Permissions"];
        $this->Account_Last_Login = date('F jS Y h:ia', strtotime($User_Result["Account_Last_Login"]));
        $this->Account_Created = date('F jS Y h:ia', strtotime($User_Result["Account_Created"]));
        $this->Account_Locked = $User_Result["Account_Locked"];
    }

    private function Set_Config_Info()
    {
        // Populate User Config Settings Into Object
        $User_Config_Array = array (':ID'=>$this->ID);
        $User_Config_Result = $this->Connection->Custom_Query("SELECT * FROM users_settings WHERE userID = :ID LIMIT 1", $User_Config_Array);

        $this->Config_Settings['Items_Per_Page'] = $User_Config_Result['Items_Per_Page'];
        $this->Config_Settings['Theme'] = $User_Config_Result['Theme'];
        $this->Config_Settings['Show_Help'] = $User_Config_Result['Show_Help'];
    }

    function __construct($ID)
    {
        $this->Connection = new Connection();
        $this->ID = $ID;
        $this->Set_User_Info();
        $this->Set_Config_Info();
    }

    // This function saves the input settings to the DB and then updates the current object with the new values.
    public function Save_Configuration($UserID,$Items,$Theme,$Show_Help)
    {
        $Config_Array = array (':Items'=>$Items,':Theme'=>$Theme,':Show_Help'=>$Show_Help,':UserID'=>$UserID);
        $Results = $this->Connection->Custom_Execute("UPDATE users_settings SET Items_Per_Page=:Items, Theme=:Theme, Show_Help=:Show_Help WHERE UserID=:UserID", $Config_Array);
        $this->Set_Config_Info();

        if ($Results) {
            $this->Message='Settings successfully edited.';
            $this->Message_Type='Success';
        } else {
            $this->Message='There was an issue saving this configuration, please try again.';
            $this->Message_Type='Error';
        }
    }

    // Returns the Full Name of the user.
    // @returns string
    public function Get_Full_Name()
    {
        return $this->First_Name . " " . $this->Last_Name;
    }

    // This Function checks the Users permissions and returns them in a readable format.
    // @returns string
    public function Get_Permissions($ReturnType='String')
    {
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
                    array_push($Return_Array,"Mod");
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

    // Converts the Datbases 0,1 into Locked,Active.
    // @returns string
    public function Get_Account_Status()
    {
        if ($this->Account_Locked = 1) { return "Active"; } else { return "Locked"; }
    }

    // Retrieves the users saved theme setting and writes it out.
    public function Display_Theme()
    {
        $Theme = $this->Config_Settings['Theme'];
        $Theme = strtolower($Theme);
        if ($Theme != 'Default' && isset($Theme)) {
            echo "<link href='css/".$Theme . ".css' rel='stylesheet' type='text/css'>";
        }
    }

}
?>
