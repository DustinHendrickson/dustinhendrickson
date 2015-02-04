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
    private $Password;
    public $ID;
    public $Username;
    public $First_Name;
    public $Last_Name;
    public $FightBot_Name;
    public $Permissions;
    public $EMail;
    public $Account_Last_Login;
    public $Account_Created;
    public $Account_Locked;
    public $Achievements_Unlocked;
    public $Points;
    public $Points_Last_Recieved;
    public $Last_Daily_Quest_Recieved;
    public $Config_Settings = array();
    public $SECONDS_INTERVAL = 43200;     // Interval in seconds that point redemption refreshes.
    public $TODAYS_DATE;

    // Pet Vars
    public $Pet_Battles_Won;
    public $Pet_Battles_Lost;
    public $Pets_Caught;

    // Initial function to search the database for the desired user and populate this class object.
    private function Set_User_Info()
    {
        $User_Array = array (':ID'=>$this->ID);
        $User_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE ID = :ID LIMIT 1", $User_Array);

        $this->Username                         = $User_Result["Username"];
        $this->First_Name                       = $User_Result["First_Name"];
        $this->Last_Name                        = $User_Result["Last_Name"];
        $this->FightBot_Name                    = $User_Result["FightBot_Name"];
        $this->Password                         = $User_Result["Password"];
        $this->Permissions                      = $User_Result["Permissions"];
        $this->EMail                            = $User_Result["EMail"];
        $this->Account_Last_Login               = date('F jS Y h:ia', strtotime($User_Result["Account_Last_Login"]));
        $this->Account_Created                  = date('F jS Y h:ia', strtotime($User_Result["Account_Created"]));
        $this->Account_Locked                   = $User_Result["Account_Locked"];
        $this->Achievements_Unlocked            = $User_Result["Achievements_Unlocked"];
        $this->Points                           = $User_Result["Points"];
        $this->Points_Last_Recieved             = date('F jS Y h:ia', strtotime($User_Result["Points_Last_Recieved"]));
        $this->Last_Daily_Quest_Recieved        = date('F jS Y h:ia', strtotime($User_Result["Last_Daily_Quest_Recieved"]));

        $this->Pet_Battles_Won                  = $User_Result["Pet_Battles_Won"];
        $this->Pet_Battles_Lost                 = $User_Result["Pet_Battles_Lost"];
        $this->Pets_Caught                      = $User_Result["Pets_Caught"];
    }

    private function Set_Config_Info()
    {
        // Populate User Config Settings Into Object
        if(isset($this->ID)){
            $User_Config_Array = array (':ID'=>$this->ID);
            $User_Config_Result = $this->Connection->Custom_Query("SELECT * FROM users_settings WHERE UserID = :ID LIMIT 1", $User_Config_Array);

            if ($User_Config_Result){
                $this->Config_Settings['Items_Per_Page'] = $User_Config_Result['Items_Per_Page'];
                $this->Config_Settings['Theme']          = $User_Config_Result['Theme'];
                $this->Config_Settings['Show_Help']      = $User_Config_Result['Show_Help'];
                $this->Config_Settings['Show_Toasts']      = $User_Config_Result['Show_Toasts'];
            } else {
                $this->Config_Settings['Items_Per_Page'] = 5;
                $this->Config_Settings['Theme']          = "Default";
                $this->Config_Settings['Show_Help']      = 1;
                $this->Config_Settings['Show_Toasts']      = 1;
            }
        }
    }

    function __construct($ID)
    {
        $this->Connection = new Connection();
        $this->ID = $ID;
        $this->Set_User_Info();
        $this->Set_Config_Info();
    }


    // This function saves the input settings to the DB and then updates the current object with the new values.
    public function Save_Configuration($Parameters)
    {

        // Here we check to see if the user already has a settings entry. If not, we add one, if they do, we update it.
        $Checker_Array = array(':UserID'=>$this->ID);
        $Checker_Results = $this->Connection->Custom_Query("SELECT * FROM users_settings WHERE UserID=:UserID", $Checker_Array);

        if (!$Checker_Results) {
             // Make sure there wasn't NOTHING passed in.
            if ($Parameters) {
                $Config_Array = array();
                $Parameters["UserID"] = $this->ID;
                $SQL = "INSERT INTO users_settings (";

                $Number_Of_Parameters = count($Parameters);
                $i = 0;

                // Loop through each parameter to produce an Array and String to run an SQL query.
                foreach ($Parameters as $ParameterName => $ParameterValue) {
                    $i++;

                    $KeyValue = ":".$ParameterName;
                    $Config_Array[$KeyValue] = $ParameterValue;
                    $SQL .= " " . $ParameterName;

                    // Here we check if the parameter is the last one, if so we don't add a command in the SQL.
                    if ($i != $Number_Of_Parameters) {
                        $SQL .= ", ";
                    }
                }

                $SQL .= ") VALUES (";

                $i = 0;

                foreach ($Parameters as $ParameterName => $ParameterValue) {
                    $i++;

                    $KeyValue = ":".$ParameterName;
                    $Config_Array[$KeyValue] = $ParameterValue;
                    $SQL .= " :" . $ParameterName;

                    // Here we check if the parameter is the last one, if so we don't add a command in the SQL.
                    if ($i != $Number_Of_Parameters) {
                        $SQL .= ", ";
                    }
                }

                $SQL .= ")";

                Write_Log('debug', "SQL: " . $SQL);
                $Results = $this->Connection->Custom_Execute($SQL, $Config_Array);
            }


        } else {

            // Make sure there wasn't NOTHING passed in.
            if ($Parameters) {
                $Config_Array = array();
                $SQL = "UPDATE users_settings SET ";

                $Number_Of_Parameters = count($Parameters);
                $i = 0;

                // Loop through each parameter to produce an Array and String to run an SQL query.
                foreach ($Parameters as $ParameterName => $ParameterValue) {
                    $i++;

                    $KeyValue = ":".$ParameterName;
                    $Config_Array[$KeyValue] = $ParameterValue;
                    $SQL .= " " . $ParameterName . "=:" . $ParameterName;

                    // Here we check if the parameter is the last one, if so we don't add a command in the SQL.
                    if ($i != $Number_Of_Parameters) {
                        $SQL .= ", ";
                    }
                }

                $SQL .= " WHERE UserID=:UserID";
                $Config_Array[":UserID"] = $this->ID;

                Write_Log('debug', "SQL: " . $SQL);
                $Results = $this->Connection->Custom_Execute($SQL, $Config_Array);
            }
        }

        if ($Results) {
            $this->Set_Config_Info();
            Toasts::addNewToast('Settings successfully edited.','success');
        } else {
            Toasts::addNewToast('There was an issue saving this configuration, please try again.','error');
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
        $Permissions_Array = explode(",", $this->Permissions);
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
        switch ($this->Account_Locked){
            case 1:
                return "Locked";
                break;
            case 0:
                return "Active";
                break;
            default:
                return "Unknown";
                break;
        }
    }

    // Retrieves the users saved theme setting and writes it out.
    public function Display_Theme()
    {
        $Theme = $this->Config_Settings['Theme'];
        $Theme = strtolower($Theme);
        if ($Theme != 'Default' && $Theme != 'default' && isset($Theme) && $Theme != '') {
            echo "<link href='css/".$Theme . ".css' rel='stylesheet' type='text/css'>";
        }
    }

    public function Edit_User($Parameters)
    {
        // Make sure there wasn't NOTHING passed in.
        if ($Parameters) {
            $Config_Array = array();
            $SQL = "UPDATE users SET ";
            $Modified = "Setting ";
            $Number_Of_Parameters = count($Parameters);
            $i = 0;

            // Loop through each parameter to produce an Array and String to run an SQL query.
            foreach ($Parameters as $ParameterName => $ParameterValue) {
                $i++;

                $KeyValue = ":".$ParameterName;
                $Config_Array[$KeyValue] = $ParameterValue;
                $SQL .= " " . $ParameterName . "=:" . $ParameterName;
                $Modified .= $ParameterName . "=" . $ParameterValue . " ";

                // Here we check if the parameter is the last one, if so we don't add a command in the SQL.
                if ($i != $Number_Of_Parameters) {
                    $SQL .= ", ";
                }
            }

            $Config_Array[":ID"] = $this->ID;
            $SQL .= " WHERE ID=:ID";

            $Results = $this->Connection->Custom_Execute($SQL, $Config_Array);

            Write_Log('users', "Trying to edit UserID [" . $this->ID . "]");

            if ($Results) {
                $this->Set_User_Info();
                Toasts::addNewToast('User was edited successfully.','success');
                Write_Log('users', "Success - Edited userid [" . $this->ID . "] " . $Modified . " Modified by userid [" . $_SESSION['ID'] . "]" );
            } else {
                Toasts::addNewToast('There was an issue editing a user, please try again.','error');
                Write_Log('users', "Error - Could not edited user [" . $this->ID . "]" . " Called by userid [" . $_SESSION['ID'] . "]"  );
            }
        } else {
            Toasts::addNewToast('Could not save changes, nothing was changed.','error');
            Write_Log('users', "Error - Could not edited user [" . $this->ID . "] No parameters were given."  );
        }
    }

    // Deletes the user from the DB.
    public function Delete_User()
    {
        $Config_Array = array (':ID'=>$this->ID);
        $Results = $this->Connection->Custom_Execute("DELETE FROM users WHERE ID=:ID", $Config_Array);

        if ($Results) {
            Toasts::addNewToast('User was deleted successfully.','success');
            Write_Log('users', "Success - Deleted userid [" . $this->ID . "] Deleted by userid [" . $_SESSION['ID'] . "]" );
        } else {
            Toasts::addNewToast('There was an issue editing a user, please try again.','error');
            Write_Log('users', "Error - Could not edited user [" . $this->ID . "]" . " Called by userid [" . $_SESSION['ID'] . "]"  );
        }
    }

    // ACHIEVEMENT SYSTEM ====================================================================================================
    public function Add_Achievement($Name)
    {

        if(!$this->Is_Achievement_Unlocked($Name)) {

            $this->Achievements_Unlocked .= $this->Get_Achievement_ID_By_Name($Name) . ",";

            $Config_Array = array (':ID'=>$this->ID, ':Achievements_Unlocked'=>$this->Achievements_Unlocked);
            $Results = $this->Connection->Custom_Execute("UPDATE users SET Achievements_Unlocked=:Achievements_Unlocked WHERE ID=:ID", $Config_Array);

            $Achievement = $this->Get_Achievement_Info_By_Name($Name);

            if ($Results) {
                Toasts::addNewToast($Achievement['Description'] . " +" . $Achievement['Points'] . " points.",'achievement');
                Write_Log('users', "Success - Added achievement [" . $Achievement['ID'] . "] " . $Achievement['Name'] . " to user " . $this->ID );
                $this->Add_Points($Achievement['Points']);
            } else {
                Write_Log('users', "Error - Tried adding achievement [" . $Achievement['ID'] . "] " . $Achievement['Name'] . " to user " . $this->ID . " FAILED.");
            }
        }
    }

    // Checks to see if the achievement has been unlocked by the user already.
    public function Is_Achievement_Unlocked($Name)
    {
        $Achievements_Array = explode(",", $this->Achievements_Unlocked);
        foreach ($Achievements_Array as $Achievement) {
            if ($this->Get_Achievement_ID_By_Name($Name) == $Achievement) {
                return true;
            }
        }

        return false;
    }

    //
    public function Get_Achievement_Info_By_Name($Name)
    {
        $Checker_Array = array(':Name'=>$Name);
        $Checker_Results = $this->Connection->Custom_Query("SELECT * FROM achievements WHERE Name=:Name", $Checker_Array);

        return $Checker_Results;
    }

    public function Get_List_Of_All_Achievements()
    {
        $Return_String = '';
        $Checker_Array = array();
        $Checker_Results = $this->Connection->Custom_Query("SELECT * FROM achievements", $Checker_Array, true);
        foreach ($Checker_Results as $Row) {
            $Return_String .= $Row['ID'] . ',';
        }

        return $Return_String;
    }

    public function Get_Achievement_Info_By_ID($ID)
    {
        $Checker_Array = array(':ID'=>$ID);
        $Checker_Results = $this->Connection->Custom_Query("SELECT * FROM achievements WHERE ID=:ID", $Checker_Array);

        return $Checker_Results;
    }

    public function Get_Achievement_ID_By_Name($Name)
    {
        $Checker_Results = $this->Get_Achievement_Info_By_Name($Name);

        $ID = $Checker_Results['ID'];

        return $ID;
    }

    public function Get_Achievement_Name_By_ID($ID)
    {
        $Checker_Results = $this->Get_Achievement_Info_By_ID($ID);

        $Name = $Checker_Results['Name'];

        return $Name;
    }
    // END ACHIEVEMENT SYSTEM ================================================================================================



    // POINTS SYSTEM ===================================================================================================
    // Retrieves Points
    public function Get_Points()
    {
        return $this->Points;
    }

    // Set's Points
    public function Set_Points($Points)
    {
        $this->Points = $Points;
        $Config_Array = array (':Points'=>$Points,':ID'=>$this->ID);
        $Results = $this->Connection->Custom_Execute("UPDATE users SET Points=:Points WHERE ID=:ID", $Config_Array);

        Write_Log('points', "Trying to set points to [" . $Points . "] for UserID [" . $this->ID . "]");

        if ($Results) {
            Write_Log('points', "Success - Points were added successfully");
        } else {
            Write_Log('points', "Error - There was an issue adding points to the user, please try again.");
        }

    }

    // Add Points
    public function Add_Points($Points)
    {
        $this->Set_Points($this->Points + $Points);
    }

    // Subtract Points
    public function Subtract_Points($Points)
    {
        $this->Set_Points($this->Points - $Points);
    }

    // Returns the date a user last received redemption points in human readable format.
    public function Get_Last_Recieved_Points_DateTime()
    {
        return date('F jS Y h:ia', strtotime($this->Points_Last_Recieved));
    }

    // Returns the date a user last received redemption points in computer readable format.
    public function Get_Last_Recieved_Points_UnixTime()
    {
        return strtotime($this->Points_Last_Recieved);
    }

    // Returns if a user has redeemed points during the interval time.
    public function Can_Redeem_Points()
    {
        $Seconds_Difference = time() - $this->Get_Last_Recieved_Points_UnixTime();

        if ($Seconds_Difference > $this->SECONDS_INTERVAL) {
            return true;
        } else {
            return false;
        }
    }

    // Function to redeem daily points.
    public function Redeem_Points($Points)
    {
        $this->TODAYS_DATE = date("Y-m-d H:i:s");

        $Config_Array = array (':Points_Last_Recieved'=>$this->TODAYS_DATE,':ID'=>$this->ID);
        $Results = $this->Connection->Custom_Execute("UPDATE users SET Points_Last_Recieved=:Points_Last_Recieved WHERE ID=:ID", $Config_Array);

        $this->Points_Last_Recieved = $this->TODAYS_DATE;

        Write_Log('points', "Redeeming [" . $Points . "] points for UserID [" . $this->ID . "]");

        if ($Results) {
            Toasts::addNewToast('Points were redeemed successfully. +' . $Points . " points" ,'success');
            Write_Log('points', "Success - Points were redeemed successfully.");
            $this->Add_Points($Points);
        } else {
            Toasts::addNewToast('There was an issue redeeming points, please try again.','error');
            Write_Log('points', "Error - There was an issue redeeming points, please try again.");
        }

    }

    // END POINTS SYSTEM ================================================================================================


}
?>
