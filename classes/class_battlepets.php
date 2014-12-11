<?php
// This class will be for the pet battle system.
//
// TO DOO
// 1. Pet Information View (Self and Other Player)
// 2. Pet Battle Pick System (Show other players and their pets)
// 3. Pet Trading (Ability to trade pets with other users)
// 4. Pet Fusion (Combine pets for power boosts, +HP and +ATK)

class BattlePet
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
private $Pet_Active;

private $Last_Pet_Aquired;

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
            Toasts::addNewToast("It looks like you don't have any pets :( <br>Here is a free one to get started!", 'success');
            $this->Give_Random_Pet();
        }
    }
}

// Returns the current Active Pet ID
private function Get_Active_Pet_ID()
{
    $Pet_Array = array (':User_ID'=>$this->User_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE User_ID = :User_ID AND Pet_Active = 1 LIMIT 1";
    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array);

    return $Pet_Result["Pet_ID"];
}

// Returns an array of all pets owned by the user.
public function Get_All_Pets()
{
    $Pet_Array = array (':User_ID'=>$this->User_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE User_ID = :User_ID";

    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array, true);

    return $Pet_Result;
}

// Returns an array of all inactive pets owned by the user.
public function Get_All_Inactive_Pets()
{
    $Pet_Array = array (':User_ID'=>$this->User_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE Pet_Active = 0 AND User_ID = :User_ID";

    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array, true);

    return $Pet_Result;
}

// Set the current objects information for the active pet.
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
    $this->Pet_Skill_3              = (string) $Pet_Result["Pet_Skill_3"];
    $this->Pet_Bonus_Offense        = (int) $Pet_Result["Pet_Bonus_Offense"];
    $this->Pet_Bonus_Defense        = (int) $Pet_Result["Pet_Bonus_Defense"];
    $this->Pet_Bonus_Health         = (int) $Pet_Result["Pet_Bonus_Health"];
    $this->Pet_Bonus_EXP            = (int) $Pet_Result["Pet_Bonus_EXP"];
    $this->Pet_Exp                  = (int) $Pet_Result["Pet_Exp"];
    $this->Pet_Level                = (int) $Pet_Result["Pet_Level"];
    $this->Pet_Name                 = (string) $Pet_Result["Pet_Name"];
    $this->Pet_Type                 = (string) $Pet_Result["Pet_Type"];
    $this->Pet_Status               = (string) $Pet_Result["Pet_Status"];
    $this->Pet_Active               = (int) $Pet_Result["Pet_Active"];
}

// Creates a new random pet for the user and adds them to the DB.
public function Give_Random_Pet($Tier=1)
{
    // Setup YAML stuff.
    $RandomPet = Spyc::YAMLLoad('/var/www/petbattles/data/base_pet_list.yml');
    $MaxPets = count($RandomPet['pets'][$Tier]) - 1;
    $Random = rand(0,$MaxPets);

    // Here I will load up a random pet from a YAML config file and then add it to the DB.
    $Pet_Name = $RandomPet['pets'][$Tier][$Random]['name'];
    $Pet_Image = $RandomPet['pets'][$Tier][$Random]['image'];
    $Pet_Offense = $RandomPet['pets'][$Tier][$Random]['offense'];
    $Pet_Defense = $RandomPet['pets'][$Tier][$Random]['defense'];
    $Pet_Max_Health = $RandomPet['pets'][$Tier][$Random]['max_health'];
    $Pet_Max_AP = $RandomPet['pets'][$Tier][$Random]['max_ap'];
    $Pet_Skill_1 = $RandomPet['pets'][$Tier][$Random]['skill_1'];
    $Pet_Skill_2 = $RandomPet['pets'][$Tier][$Random]['skill_2'];
    $Pet_Skill_3 = $RandomPet['pets'][$Tier][$Random]['skill_3'];
    $Pet_Type = $RandomPet['pets'][$Tier][$Random]['type'];


    $Pet_Array = array();
    $Pet_Array[':User_ID']=$this->User_ID;
    $Pet_Array[':Pet_Name']=$Pet_Name;
    $Pet_Array[':Pet_Image']=$Pet_Image;
    $Pet_Array[':Pet_Offense']=$Pet_Offense;
    $Pet_Array[':Pet_Defense']=$Pet_Defense;
    $Pet_Array[':Pet_Max_Health']=$Pet_Max_Health;
    $Pet_Array[':Pet_Current_Health']=$Pet_Max_Health;
    $Pet_Array[':Pet_Max_AP']=$Pet_Max_AP;
    $Pet_Array[':Pet_Current_AP']=$Pet_Max_AP;
    $Pet_Array[':Pet_Skill_1']=$Pet_Skill_1;
    $Pet_Array[':Pet_Skill_2']=$Pet_Skill_2;
    $Pet_Array[':Pet_Skill_3']=$Pet_Skill_3;
    $Pet_Array[':Pet_Type']=$Pet_Type;

    $Pet_SQL = "INSERT INTO pets (User_ID, Pet_Name, Pet_Image, Pet_Offense, Pet_Defense, Pet_Max_Health, Pet_Current_Health, Pet_Max_AP, Pet_Current_AP, Pet_Skill_1, Pet_Skill_2, Pet_Skill_3, Pet_Type) VALUES (:User_ID, :Pet_Name, :Pet_Image, :Pet_Offense, :Pet_Defense, :Pet_Max_Health, :Pet_Current_Health, :Pet_Max_AP, :Pet_Current_AP, :Pet_Skill_1, :Pet_Skill_2, :Pet_Skill_3, :Pet_Type)";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);

    if ($Results){
        Toasts::addNewToast("You received a new Pet! <br>[{$Pet_Name}]", 'petbattle');
        Write_Log('pets', "User_ID [" . $this->User_ID . "] just received a new pet!");
    }
}

// This function will try to catch a Wild pet you're fighting.
public function Catch_Pet($Pet_ID)
{
    $PET_HEALTH_FOR_BETTER_CATCH = .45;

    $Pet_To_Catch = new BattlePet($Pet_ID);

    // The current function here makes sure that the pet to catches health is less than or equal to 45%, if so it's easier to catch by 50%.
    if ($Pet_To_Catch->Current_HP <= ($Pet_To_Catch->Pet_Max_Health * $PET_HEALTH_FOR_BETTER_CATCH)) {
        $Random = rand(1,50);
    } else {
        $Random = rand(1,100);
    }
    if ($Random <= 20) {
        // YOU CAUGHT IT!
        Toasts::addNewToast("You just caught [{$Pet_To_Catch->Pet_Name}]", 'petbattle');
    } else {
        // YOU MISSED! WTF!?
        Toasts::addNewToast("Pet [{$Pet_To_Catch->Pet_Name}] just got away!", 'petbattle');
    }
}

// Deletes a pet from the database.
public function Release_Pet($Pet_ID)
{
    $Pet_Array[':Pet_ID'] = $Pet_ID;
    $Pet_SQL = "DELETE FROM pets WHERE Pet_ID=:Pet_ID";
    
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);

    if ($Results) {
        Toasts::addNewToast("You just released a pet back into the wild.", 'petbattle');
    }
}

// Returns the current active pet information.
public function Get_Active_Pet()
{
    $Pet_Array = array(':User_ID' => $this->User_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE Pet_Active = 1 AND User_ID = :User_ID";
    $Results = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array);

    return $Results;
}
// This function will set a specific pet to Active and de-active any pet already active.
public function Set_Active_Pet($Pet_ID)
{
    $Old_Active_Pet = $this->Get_Active_Pet_ID();
    $this->Set_Inactive_Pet($Old_Active_Pet);

    $Pet_Array = array (':Pet_ID'=>$Pet_ID, ':User_ID'=>$this->User_ID);
    $Pet_SQL = "UPDATE pets SET Pet_Active = 1 WHERE Pet_ID = :Pet_ID AND User_ID = :User_ID";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);

    if ($Results) {
        Toasts::addNewToast("You just set a new active pet!", 'petbattle');
        $this->Switch_Pet($Pet_ID);
    }
}

// Sets a specific pet as Inactive
private function Set_Inactive_Pet($Pet_ID)
{
    $Pet_Array = array (':Pet_ID'=>$Pet_ID);
    $Pet_SQL = "UPDATE pets SET Pet_Active = 0 WHERE Pet_ID = :Pet_ID";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
}

// Switches to a specific pet and re-sets up the object info.
private function Switch_Pet($Pet_ID)
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

// Returns the total amount of alive pets the current user has.
public function Get_Total_Alive_Pet_Count()
{
    $Pet_Array = array(':User_ID' => $this->User_ID);
    $Pet_SQL = "SELECT COUNT(*) FROM pets WHERE Pet_Status='Alive' AND User_ID = :User_ID";
    $Results = $this->Connection->Custom_Count_Query($Pet_SQL, $Pet_Array);

    return $Results[0];
}

// Returns the total amount of dead pets the current user has.
public function Get_Total_Dead_Pet_Count()
{
    $Pet_Array = array(':User_ID' => $this->User_ID);
    $Pet_SQL = "SELECT COUNT(*) FROM pets WHERE Pet_Status='Dead' AND User_ID = :User_ID";
    $Results = $this->Connection->Custom_Count_Query($Pet_SQL, $Pet_Array);

    return $Results[0];
}

} // END CLASS
