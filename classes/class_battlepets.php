<?php
// This class will be for the pet battle system.
//
// TO DOO
// 1. Pet Information View (Self and Other Player)
// 2. Pet Battle Pick System (Show other players and their pets)
// 3. Pet Trading (Ability to trade pets with other users)
// 4. Pet Fusion (Combine pets for power boosts, +HP and +ATK)

class BattlePets
{
private $Connection;

private $User_ID;
private $Pet_ID;

private $Pet_Image;

private $Pet_Offense;
private $Pet_Defense;

private $Pet_Current_Health;
private $Pet_Max_Health;

private $Pet_Current_AP;
private $Pet_Max_AP;

private $Pet_Skill_1;
private $Pet_Skill_2;

private $Pet_Bonus_Offense;
private $Pet_Bonus_Defense;
private $Pet_Bonus_Health;
private $Pet_Bonus_EXP;

private $Pet_Exp;
private $Pet_Level;

private $Pet_Name;
private $Pet_Type;
private $Pet_Status;

private $Active_Pet_ID;


// Main constructor used to initiate the class and setup objects for the current active pet.
function __construct($User_ID)
{
    $this->Connection = new Connection();

    $this->User_ID = $User_ID;
    $Active_Pet = $this->Get_Active_Pet_ID();

    // Here we check to make sure we revived a valid Active pet for the user. If not we create a new one for them if they have 0 pets.
    if ($Active_Pet != NULL && $Active_Pet != 0 && $Active_Pet != ""){
        // If the active pet id was successfully grabbed, go ahead and set the pet info.
        $this->Set_Pet_Info($Active_Pet);
    } else {
        // Otherwise we check and create a new pet.
        if ($this->Get_Total_Pet_Count() < 1) {
            $this->Create_Random_Pet();
        }
    }
}

private function Get_Active_Pet_ID()
{
    $Pet_Array = array (':User_ID'=>$this->User_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE User_ID = :User_ID AND Pet_Status = 'Active' LIMIT 1";
    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array);

    return $Pet_Result["Pet_ID"];
}

private function Set_Pet_Info($Pet_ID)
{
    $Pet_Array = array (':User_ID'=>$this->User_ID, ':Pet_ID'=>$Pet_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE User_ID = :User_ID AND Pet_ID = :Pet_ID";
    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array);

    $this->Active_Pet_ID            = (int) $Pet_Result["Pet_ID"];
    $this->Pet_Image                = (string) $Pet_Result["Pet_Image"];
    $this->Pet_Offense              = (int) $Pet_Result["Pet_Offense"];
    $this->Pet_Defense              = (int) $Pet_Result["Pet_Defense"];
    $this->Pet_Current_Health       = (int) $Pet_Result["Pet_Current_Health"];
    $this->Pet_Max_Health           = (int) $Pet_Result["Pet_Max_Health"];
    $this->Pet_Current_AP           = (int) $Pet_Result["Pet_Current_AP"];
    $this->Pet_Max_AP               = (int) $Pet_Result["Pet_Max_AP"];
    $this->Pet_Skill_1              = (string) $Pet_Result["Pet_Skill_1"];
    $this->Pet_Skill_2              = (string) $Pet_Result["Pet_Skill_2"];
    $this->Pet_Bonus_Offense        = (int) $Pet_Result["Pet_Bonus_Offense"];
    $this->Pet_Bonus_Defense        = (int) $Pet_Result["Pet_Bonus_Defense"];
    $this->Pet_Bonus_Health         = (int) $Pet_Result["Pet_Bonus_Health"];
    $this->Pet_Bonus_EXP            = (int) $Pet_Result["Pet_Bonus_EXP"];
    $this->Pet_Exp                  = (int) $Pet_Result["Pet_Exp"];
    $this->Pet_Level                = (int) $Pet_Result["Pet_Level"];
    $this->Pet_Name                 = (string) $Pet_Result["Pet_Name"];
    $this->Pet_Type                 = (string) $Pet_Result["Pet_Type"];
    $this->Pet_Status               = (string) $Pet_Result["Pet_Status"];
}

// Creates a new random pet for the user and adds them to the DB.
public function Create_Random_Pet()
{
    // Setup YAML stuff.
    $RandomPet = Spyc::YAMLLoad('/home/var/www/petbattles/data/base_pet_list.yml');
    $Pet_Name = $RandomPet[][][][];

    // Here I will load up a random pet from a YAML config file and then add it to the DB.
    $Pet_Array = array (':Pet_ID'=>$Pet_ID);
    $Pet_SQL = "INSERT INTO pets (Pet_ID, User_ID) VALUES (:Pet_ID, :User_ID)";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
    $New_Pet_ID = $this->Connection->lastInsertId();

    $this->Set_Pet_Active($New_Pet_ID);
    $this->Switch_Pet($New_Pet_ID);
}

// This function will set a specific pet to Active and de-active any pet already active.
public function Set_Pet_Active($Pet_ID)
{
    $Old_Active_Pet = $this->Get_Active_Pet_ID();
    $this->Set_Pet_Inactive($Old_Active_Pet);

    $Pet_Array = array (':Pet_ID'=>$Pet_ID);
    $Pet_SQL = "UPDATE pets SET Pet_Status = 'Active' WHERE Pet_ID = :Pet_ID";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);

    $this->Switch_Pet($Pet_ID);
}

// Sets a specific pet as Inactive
private function Set_Pet_Inactive($Pet_ID)
{
    $Pet_Array = array (':Pet_ID'=>$Pet_ID);
    $Pet_SQL = "UPDATE pets SET Pet_Status = 'Inactive' WHERE Pet_ID = :Pet_ID";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
}

// Switches to a specific pet and re-sets up the object info.
public function Switch_Pet($Pet_ID)
{
    $this->Set_Pet_Info($Pet_ID);
}

// Returns the total amount of pets the current user has.
public function Get_Total_Pet_Count()
{
    $Pet_Array = array(':User_ID' => $this->User_ID);
    $Pet_SQL = "SELECT COUNT(*) FROM pets WHERE User_ID = :User_ID";
    $Results = $this->Connection->Custom_Count_Query($Pet_SQL, $Pet_Array);

    return $Results[0];
}

} // END CLASS
