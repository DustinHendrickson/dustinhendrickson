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

public $User_ID;
public $Pet_ID;

public $Pet_Image;

public $Pet_Offense;
public $Pet_Defense;

public $Pet_Current_Health;
public $Pet_Max_Health;

public $Pet_Current_AP;
public $Pet_Max_AP;

public $Pet_Skill_1;
public $Pet_Skill_2;

public $Pet_Bonus_Offense;
public $Pet_Bonus_Defense;
public $Pet_Bonus_Health;
public $Pet_Bonus_EXP;

public $Pet_Exp;
public $Pet_Level;

public $Pet_Name;
public $Pet_Type;
public $Pet_Status;
public $Pet_Active;

public $Last_Pet_Aquired;

public $Active_Pet_ID;


// Main constructor used to initiate the class and setup objects for the current active pet.
// You can pass in an optional pet id to make it the active pet for this object.
function __construct($User_ID,$Pet_ID=0)
{
    $this->Connection = new Connection();

    $this->User_ID = $User_ID;

    if ($Pet_ID>0){
        $Active_Pet = $Pet_ID;
    } else {
        $Active_Pet = $this->Get_Active_Pet_ID();
    }

    // Here we check to make sure we revived a valid Active pet for the user. If not we create a new one for them if they have 0 pets.
    if ($Active_Pet != NULL && $Active_Pet != 0 && $Active_Pet != ""){
        // If the active pet id was successfully grabbed, go ahead and set the pet info.
        $this->Set_Pet_Info($Active_Pet);
    } else {
        // Otherwise we check and create a new pet as long as the user ID isn't a wild pet.
        if ($this->Get_Total_Pet_Count() < 1 && $this->User_ID != 0) {
            Toasts::addNewToast("It looks like you don't have any pets :( <br>Here is a free one to get started!<br> I also threw in 300 points for the pet shop!", 'success');
            $User = new User($User_ID);
            $User->Add_Points(300);
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

public function Get_All_Enemy_Pets()
{
    $Pet_Array = array(':User_ID' => $this->User_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE User_ID != :User_ID AND Pet_Active=1";
    $Results = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array, true);

    return $Results;
}

// Returns an array of all pets owned by the user.
public function Get_All_Pets()
{
    $Pet_Array = array (':User_ID'=>$this->User_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE User_ID = :User_ID";

    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array, true);

    return $Pet_Result;
}

// Returns the Tier of the pet.
public function Get_Pet_Tier($Pet_ID)
{
    $Pet_Array = array (':Pet_ID'=>$Pet_ID);
    $Pet_SQL = "SELECT Pet_Tier FROM pets WHERE Pet_ID = :Pet_ID";

    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array, true);

    return $Pet_Result['Pet_Tier'];
}

// Returns an array of all inactive pets owned by the user.
public function Get_All_Inactive_Pets()
{
    $Pet_Array = array (':User_ID'=>$this->User_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE Pet_Active = 0 AND User_ID = :User_ID";

    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array, true);

    return $Pet_Result;
}

public function Add_Battles_Won($UserID)
{
    $User_Array = array();
    $User_Array[':User_ID']=$UserID;

    $User_SQL = "UPDATE users SET Pet_Battles_Won=Pet_Battles_Won+1 WHERE ID=:User_ID";
    $Results = $this->Connection->Custom_Execute($User_SQL, $User_Array);
}

public function Add_Battles_Lost($UserID)
{
    $User_Array = array();
    $User_Array[':User_ID']=$UserID;

    $User_SQL = "UPDATE users SET Pet_Battles_Lost=Pet_Battles_Lost+1 WHERE ID=:User_ID";
    $Results = $this->Connection->Custom_Execute($User_SQL, $User_Array);
}

public function Add_Pets_Caught()
{
    $User_Array = array();
    $User_Array[':User_ID']=$this->User_ID;

    $User_SQL = "UPDATE users SET Pets_Caught=Pets_Caught+1 WHERE ID=:User_ID";
    $Results = $this->Connection->Custom_Execute($User_SQL, $User_Array);
}

public function Add_Offense_To_Pet($Pet_ID, $Offense)
{
    $User_Array = array();
    $User_Array[':Pet_ID']=$Pet_ID;
    $User_Array[':Pet_Offense']=$Offense;

    $User_SQL = "UPDATE pets SET Pet_Offense=Pet_Offense+:Pet_Offense WHERE Pet_ID=:Pet_ID";
    $Results = $this->Connection->Custom_Execute($User_SQL, $User_Array);
}

public function Add_Defense_To_Pet($Pet_ID, $Defense)
{
    $User_Array = array();
    $User_Array[':Pet_ID']=$Pet_ID;
    $User_Array[':Pet_Defense']=$Defense;

    $User_SQL = "UPDATE pets SET Pet_Defense=Pet_Defense+:Pet_Defense WHERE Pet_ID=:Pet_ID";
    $Results = $this->Connection->Custom_Execute($User_SQL, $User_Array);
}

public function Add_Max_Health_To_Pet($Pet_ID, $Max_Health)
{
    $User_Array = array();
    $User_Array[':Pet_ID']=$Pet_ID;
    $User_Array[':Pet_Max_Health']=$Max_Health;

    $User_SQL = "UPDATE pets SET Pet_Max_Health=Pet_Max_Health+:Pet_Max_Health, Pet_Current_Health=Pet_Max_Health WHERE Pet_ID=:Pet_ID";
    $Results = $this->Connection->Custom_Execute($User_SQL, $User_Array);

}

// Set the current objects information for the active pet.
public function Set_Pet_Info($Pet_ID)
{
    $Pet_Array = array (':User_ID'=>$this->User_ID, ':Pet_ID'=>$Pet_ID);
    $Pet_SQL = "SELECT * FROM pets WHERE User_ID = :User_ID AND Pet_ID = :Pet_ID";
    $Pet_Result = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array);

    $this->Active_Pet_ID            = (int) $Pet_ID;
    $this->Pet_ID                   = (int) $Pet_ID;
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
    $this->Pet_Tier                 = (int) $Pet_Result["Pet_Tier"];
    $this->Pet_Active               = (int) $Pet_Result["Pet_Active"];
}

public function Give_Caught_Pet()
{
    $Pet_Image = explode("/", $_SESSION['PVE_AI_Pet_Image']);
    $Pet_Array = array();
    $Pet_Array[':User_ID']=$this->User_ID;
    $Pet_Array[':Pet_Name']=$_SESSION['PVE_AI_Pet_Name'];
    $Pet_Array[':Pet_Level']=$_SESSION['PVE_AI_Pet_Level'];
    $Pet_Array[':Pet_Image']=$Pet_Image[2];
    $Pet_Array[':Pet_Offense']=$_SESSION['PVE_AI_Pet_Offense'];
    $Pet_Array[':Pet_Defense']=$_SESSION['PVE_AI_Pet_Defense'];
    $Pet_Array[':Pet_Max_Health']=$_SESSION['PVE_AI_Pet_Max_Health'];
    $Pet_Array[':Pet_Current_Health']=$_SESSION['PVE_AI_Pet_Max_Health'];
    $Pet_Array[':Pet_Max_AP']=$_SESSION['PVE_AI_Pet_Max_AP'];
    $Pet_Array[':Pet_Current_AP']=$_SESSION['PVE_AI_Pet_Max_AP'];
    $Pet_Array[':Pet_Skill_1']=$_SESSION['PVE_AI_Pet_Skill_1'] ;
    $Pet_Array[':Pet_Skill_2']=$_SESSION['PVE_AI_Pet_Skill_2'] ;
    $Pet_Array[':Pet_Skill_3']=$_SESSION['PVE_AI_Pet_Skill_3'] ;
    $Pet_Array[':Pet_Type']=$_SESSION['PVE_AI_Pet_Type'];
    $Pet_Array[':Pet_Tier']=$_SESSION['PVE_AI_Pet_Tier'];

    $Pet_SQL = "INSERT INTO pets (User_ID, Pet_Level, Pet_Name, Pet_Image, Pet_Offense, Pet_Defense, Pet_Max_Health, Pet_Current_Health, Pet_Max_AP, Pet_Current_AP, Pet_Skill_1, Pet_Skill_2, Pet_Skill_3, Pet_Type, Pet_Tier) VALUES (:User_ID, :Pet_Level, :Pet_Name, :Pet_Image, :Pet_Offense, :Pet_Defense, :Pet_Max_Health, :Pet_Current_Health, :Pet_Max_AP, :Pet_Current_AP, :Pet_Skill_1, :Pet_Skill_2, :Pet_Skill_3, :Pet_Type, :Pet_Tier)";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
    $New_Pet_ID = $this->Connection->PDO_Connection->lastInsertId();
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
    $Pet_Tier = $Tier;


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
    $Pet_Array[':Pet_Tier']=$Pet_Tier;

    $Pet_SQL = "INSERT INTO pets (User_ID, Pet_Name, Pet_Image, Pet_Offense, Pet_Defense, Pet_Max_Health, Pet_Current_Health, Pet_Max_AP, Pet_Current_AP, Pet_Skill_1, Pet_Skill_2, Pet_Skill_3, Pet_Type, Pet_Tier) VALUES (:User_ID, :Pet_Name, :Pet_Image, :Pet_Offense, :Pet_Defense, :Pet_Max_Health, :Pet_Current_Health, :Pet_Max_AP, :Pet_Current_AP, :Pet_Skill_1, :Pet_Skill_2, :Pet_Skill_3, :Pet_Type, :Pet_Tier)";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
    $New_Pet_ID = $this->Connection->PDO_Connection->lastInsertId();

    if ($Results){
        Toasts::addNewToast("You received a new Pet! <br>[{$Pet_Name}]", 'petbattle');
        Write_Log('pets', "User_ID [" . $this->User_ID . "] just received a new pet with the id [ " . $New_Pet_ID  . "]");
    }
}

public function Evolve_Pet($NewTier, $Pet_ID)
{
    $this->Give_Random_Pet($NewTier);
    $this->Release_Pet($Pet_ID, false);
}

public function Create_Wild_Pet($Tier=1)
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
    $Pet_Tier = $Tier;


    $Pet_Array = array();
    $Pet_Array[':User_ID']=0;
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
    $Pet_Array[':Pet_Tier']=$Pet_Tier;

    $Pet_SQL = "INSERT INTO pets (User_ID, Pet_Name, Pet_Image, Pet_Offense, Pet_Defense, Pet_Max_Health, Pet_Current_Health, Pet_Max_AP, Pet_Current_AP, Pet_Skill_1, Pet_Skill_2, Pet_Skill_3, Pet_Type, Pet_Tier) VALUES (:User_ID, :Pet_Name, :Pet_Image, :Pet_Offense, :Pet_Defense, :Pet_Max_Health, :Pet_Current_Health, :Pet_Max_AP, :Pet_Current_AP, :Pet_Skill_1, :Pet_Skill_2, :Pet_Skill_3, :Pet_Type, :Pet_Tier)";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
    $New_Pet_ID = $this->Connection->PDO_Connection->lastInsertId();

    return $New_Pet_ID;
}

public function Clear_Battle_Room($Type)
{
    unset($_SESSION[$Type.'_User_Pet_ID']);
    unset($_SESSION[$Type.'_User_Pet_Name']);
    unset($_SESSION[$Type.'_User_Pet_Image']);
    unset($_SESSION[$Type.'_User_Pet_Skill_1_Cooldown']);
    unset($_SESSION[$Type.'_User_Pet_Skill_2_Cooldown']);
    unset($_SESSION[$Type.'_User_Pet_Skill_3_Cooldown']);
    unset($_SESSION[$Type.'_AI_Pet_Skill_1_Cooldown']);
    unset($_SESSION[$Type.'_AI_Pet_Skill_2_Cooldown']);
    unset($_SESSION[$Type.'_AI_Pet_Skill_3_Cooldown']);


    unset($_SESSION[$Type.'_AI_Pet_ID']);
    unset($_SESSION[$Type.'_AI_Pet_Name']);
    unset($_SESSION[$Type.'_AI_Pet_Image']);

    unset($_SESSION[$Type.'_User_Pet_Skill_1_Effect']);
    unset($_SESSION[$Type.'_User_Pet_Skill_2_Effect']);
    unset($_SESSION[$Type.'_User_Pet_Skill_3_Effect']);

    unset($_SESSION[$Type.'_AI_Pet_Skill_1_Effect']);
    unset($_SESSION[$Type.'_AI_Pet_Skill_2_Effect']);
    unset($_SESSION[$Type.'_AI_Pet_Skill_3_Effect']);

    unset($_SESSION[$Type.'_AI_Pet_Buffs']);
    unset($_SESSION[$Type.'_User_Pet_Buffs']);

    unset($_SESSION[$Type.'_User_Pet_Buffs_Armor_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Blind_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Wound_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Focus_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Evasion_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Heal_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Frenzy_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Stun_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Poison_Duration']);
    unset($_SESSION[$Type.'_User_Pet_Buffs_Thorns_Duration']);

    unset($_SESSION[$Type.'_AI_Pet_Buffs_Armor_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Blind_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Wound_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Focus_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Evasion_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Heal_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Frenzy_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Stun_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Poison_Duration']);
    unset($_SESSION[$Type.'_AI_Pet_Buffs_Thorns_Duration']);

}

public function Create_Battle_Room($Type,$Defender_UserID=0,$Defender_PetID=0)
{
    $PET_IMAGE_PATH = 'petbattles/images/';
    $I = 1;

    $_SESSION[$Type.'_User_ID'] = $this->User_ID;
    $_SESSION[$Type.'_User_Pet_ID'] = $this->Pet_ID;
    $_SESSION[$Type.'_User_Pet_Name'] = $this->Pet_Name;
    $_SESSION[$Type.'_User_Pet_Image'] = $PET_IMAGE_PATH . $this->Pet_Image;
    $_SESSION[$Type.'_User_Pet_Offense'] = $this->Pet_Offense;
    $_SESSION[$Type.'_User_Pet_Defense'] = $this->Pet_Defense;
    $_SESSION[$Type.'_User_Pet_Current_Health'] = $this->Pet_Current_Health;
    $_SESSION[$Type.'_User_Pet_Max_Health'] = $this->Pet_Max_Health;
    $_SESSION[$Type.'_User_Pet_Current_AP'] = $this->Pet_Current_AP;
    $_SESSION[$Type.'_User_Pet_Max_AP'] = $this->Pet_Max_AP;

    $_SESSION[$Type.'_User_Pet_Skill_1'] = $this->Pet_Skill_1;
    $Pet_Skill = $this->Get_Pet_Skill_Array($this->Pet_Skill_1);
    $_SESSION[$Type.'_User_Pet_Skill_1_Type'] = $Pet_Skill['Ability_Damage_Type'];
    $_SESSION[$Type.'_User_Pet_Skill_1_Effect'] = $Pet_Skill['Ability_Effect'];

    $_SESSION[$Type.'_User_Pet_Skill_2'] = $this->Pet_Skill_2;
    $Pet_Skill = $this->Get_Pet_Skill_Array($this->Pet_Skill_2);
    $_SESSION[$Type.'_User_Pet_Skill_2_Type'] = $Pet_Skill['Ability_Damage_Type'];
    $_SESSION[$Type.'_User_Pet_Skill_2_Effect'] = $Pet_Skill['Ability_Effect'];

    $_SESSION[$Type.'_User_Pet_Skill_3'] = $this->Pet_Skill_3;
    $Pet_Skill = $this->Get_Pet_Skill_Array($this->Pet_Skill_3);
    $_SESSION[$Type.'_User_Pet_Skill_3_Type'] = $Pet_Skill['Ability_Damage_Type'];
    $_SESSION[$Type.'_User_Pet_Skill_3_Effect'] = $Pet_Skill['Ability_Effect'];

    $_SESSION[$Type.'_User_Pet_Bonus_Offense'] = $this->Pet_Bonus_Offense;
    $_SESSION[$Type.'_User_Pet_Bonus_Defense'] = $this->Pet_Bonus_Defense;
    $_SESSION[$Type.'_User_Pet_Bonus_EXP'] = $this->Pet_Bonus_EXP;
    $_SESSION[$Type.'_User_Pet_Exp'] = $this->Pet_Exp;
    $_SESSION[$Type.'_User_Pet_Level'] = $this->Pet_Level;
    $_SESSION[$Type.'_User_Pet_Type'] = $this->Pet_Type;
    $_SESSION[$Type.'_User_Pet_Tier'] = $this->Pet_Tier;


    if ($Type == 'PVE') {
        $AI_Pet_ID = $this->Create_Wild_Pet(1);
        $AI_Pet = new BattlePet(0, $AI_Pet_ID);

        $Level_Of_AI = rand($this->Pet_Level-1,$this->Pet_Level+1);

        while ($I < $Level_Of_AI) {
            $AI_Pet->LevelUp_Pet($AI_Pet_ID);
            $I++;
        }

        $AI_Pet = new BattlePet(0, $AI_Pet_ID);
        $_SESSION[$Type.'_AI_Username'] = "AI";
    }

    if ($Type=='PVP') {
        $AI_Pet = new BattlePet($Defender_UserID, $Defender_PetID);
        $AI_User = new User($Defender_UserID);
        $_SESSION[$Type .'_AI_Username'] = $AI_User->Username;
        $_SESSION[$Type.'_AI_User_ID'] = $AI_User->ID;
    }

    if ($Type == 'BOSS') {
        $AI_Pet_ID = $this->Create_Wild_Pet(5);
        $AI_Pet = new BattlePet(0, $AI_Pet_ID);

        $Level_Of_AI = 20;

        while ($I < $Level_Of_AI) {
            $AI_Pet->LevelUp_Pet($AI_Pet_ID);
            $I++;
        }

        $AI_Pet = new BattlePet(0, $AI_Pet_ID);
        $_SESSION[$Type.'_AI_Username'] = "BOSS";
    }

    $_SESSION[$Type.'_AI_ID'] = $Defender_UserID;
    $_SESSION[$Type.'_AI_Pet_ID'] = $AI_Pet->Pet_ID;
    $_SESSION[$Type.'_AI_Pet_Name'] = $AI_Pet->Pet_Name;
    $_SESSION[$Type.'_AI_Pet_Image'] = $PET_IMAGE_PATH . $AI_Pet->Pet_Image;
    $_SESSION[$Type.'_AI_Pet_Offense'] = $AI_Pet->Pet_Offense;
    $_SESSION[$Type.'_AI_Pet_Defense'] = $AI_Pet->Pet_Defense;
    $_SESSION[$Type.'_AI_Pet_Current_Health'] = $AI_Pet->Pet_Current_Health;
    $_SESSION[$Type.'_AI_Pet_Max_Health'] = $AI_Pet->Pet_Max_Health;
    $_SESSION[$Type.'_AI_Pet_Current_AP'] = $AI_Pet->Pet_Current_AP;
    $_SESSION[$Type.'_AI_Pet_Max_AP'] = $AI_Pet->Pet_Max_AP;

    $_SESSION[$Type.'_AI_Pet_Skill_1'] = $AI_Pet->Pet_Skill_1;
    $Pet_Skill = $this->Get_Pet_Skill_Array($AI_Pet->Pet_Skill_1);
    $_SESSION[$Type.'_AI_Pet_Skill_1_Type'] = $Pet_Skill['Ability_Damage_Type'];
    $_SESSION[$Type.'_AI_Pet_Skill_1_Effect'] = $Pet_Skill['Ability_Effect'];

    $_SESSION[$Type.'_AI_Pet_Skill_2'] = $AI_Pet->Pet_Skill_2;
    $Pet_Skill = $this->Get_Pet_Skill_Array($AI_Pet->Pet_Skill_2);
    $_SESSION[$Type.'_AI_Pet_Skill_2_Type'] = $Pet_Skill['Ability_Damage_Type'];
    $_SESSION[$Type.'_AI_Pet_Skill_2_Effect'] = $Pet_Skill['Ability_Effect'];

    $_SESSION[$Type.'_AI_Pet_Skill_3'] = $AI_Pet->Pet_Skill_3;
    $Pet_Skill = $this->Get_Pet_Skill_Array($AI_Pet->Pet_Skill_3);
    $_SESSION[$Type.'_AI_Pet_Skill_3_Type'] = $Pet_Skill['Ability_Damage_Type'];
    $_SESSION[$Type.'_AI_Pet_Skill_3_Effect'] = $Pet_Skill['Ability_Effect'];

    $_SESSION[$Type.'_AI_Pet_Bonus_Offense'] = $AI_Pet->Pet_Bonus_Offense;
    $_SESSION[$Type.'_AI_Pet_Bonus_Defense'] = $AI_Pet->Pet_Bonus_Defense;
    $_SESSION[$Type.'_AI_Pet_Bonus_EXP'] = $AI_Pet->Pet_Bonus_EXP;
    $_SESSION[$Type.'_AI_Pet_Exp'] = $AI_Pet->Pet_Exp;
    $_SESSION[$Type.'_AI_Pet_Level'] = $AI_Pet->Pet_Level;
    $_SESSION[$Type.'_AI_Pet_Type'] = $AI_Pet->Pet_Type;
    $_SESSION[$Type.'_AI_Pet_Tier'] = $AI_Pet->Pet_Tier;

    $_SESSION[$Type.'_AI_Pet_Buffs'] = array();
    $_SESSION[$Type.'_User_Pet_Buffs'] = array();

    if ($Type=='PVE' OR $Type=='BOSS') {
        $this->Remove_Wild_Pet($AI_Pet_ID);
    }
}

// This function gives a pet a set amount of exp, if it's more than the leveling threshold (100 exp) it will level the pet and apply the remaining exp.
public function Give_Exp($User_ID, $Pet_ID, $Exp)
{
    $i = 0;
    $Pet = new BattlePet($User_ID, $Pet_ID);
    $NewExp = $Pet->Pet_Exp + $Exp;

    // Apply any Level ups needed.
    if ($NewExp >= 100) {
        $LevelsGained = floor($NewExp / 100);

        while ($i < $LevelsGained) {
            $this->LevelUp_Pet($Pet_ID);
            $NewExp = $NewExp - 100;
            $i++;
        }
    }

    // Here we make sure we don't go negative.
    if ($NewExp < 0) {
        $NewExp = 0;
    }

    $Pet_Array = array();
    $Pet_Array[':Pet_ID']=$Pet_ID;
    $Pet_Array[':Pet_Exp']=$NewExp;

    $Pet_SQL = "UPDATE pets SET Pet_Exp=:Pet_Exp WHERE Pet_ID=:Pet_ID";
    $Pet->Pet_EXP = $NewExp;
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
}

// This functions takes a pet id and levels that pet up, increasing it's stats.
public function LevelUp_Pet($Pet_ID)
{
    $Pet = new BattlePet($this->User_ID, $Pet_ID);

    if ($Pet->Pet_Level < 20) {
        $RandomOffense = rand(1,3);
        $RandomMaxHealth = rand(20,25);
        $RandomDefense = rand(1,2);
        $RandomMaxAP = rand(1,2);

        $New_Level = $Pet->Pet_Level+1;
        $Pet_Array = array();
        $Pet_Array[':Pet_Offense']=$Pet->Pet_Offense + $RandomOffense;
        $Pet_Array[':Pet_Defense']=$Pet->Pet_Defense + $RandomDefense;
        $Pet_Array[':Pet_Max_Health']=$Pet->Pet_Max_Health + $RandomMaxHealth;
        $Pet_Array[':Pet_Current_Health']=$Pet->Pet_Max_Health + $RandomMaxHealth;
        $Pet_Array[':Pet_Max_AP']=$Pet->Pet_Max_AP + $RandomMaxAP;
        $Pet_Array[':Pet_Current_AP']=$Pet->Pet_Max_AP + $RandomMaxAP;
        $Pet_Array[':Pet_ID']=$Pet_ID;
        $Pet_Array[':Pet_Level']=$Pet->Pet_Level + 1;
        $Pet_Array[':Pet_Exp']=0;


        $Pet_SQL = "UPDATE pets SET Pet_Level=:Pet_Level, Pet_Exp=:Pet_Exp, Pet_Offense=:Pet_Offense, Pet_Defense=:Pet_Defense, Pet_Max_Health=:Pet_Max_Health, Pet_Current_Health=:Pet_Current_Health, Pet_Max_AP=:Pet_Max_AP, Pet_Current_AP=:Pet_Current_AP WHERE Pet_ID=:Pet_ID";
        $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);

        if ($Results){
            if ($this->User_ID != 0 && $this->User_ID == $_SESSION['ID']){
                if ($New_Level == 3) {$NewSkill = "Learned " . $Pet->Pet_Skill_2. "!<br>";}
                if ($New_Level == 10) {$NewSkill = "Learned " . $Pet->Pet_Skill_3 . "!<br>";}
                $this->Update_Daily_Quest(7,1);
                Toasts::addNewToast("Your pet just leveled up!<br>[{$Pet->Pet_Name}]<br>{$Pet->Pet_Level} -> {$New_Level}<br>{$NewSkill}+ {$RandomOffense} Offense<br>+ {$RandomDefense} Defense<br>+ {$RandomMaxHealth} Health<br>+ {$RandomMaxAP} AP", 'petbattle');
            }
        }
    }
}



// Removed a Wild Pet from the DB.
public function Remove_Wild_Pet($Pet_ID)
{
    $Pet_Array[':Pet_ID'] = $Pet_ID;
    $Pet_SQL = "DELETE FROM pets WHERE Pet_ID=:Pet_ID";

    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
}

// Deletes a pet from the database.
public function Release_Pet($Pet_ID, $Display_Toast=true)
{
    $Pet_Array[':Pet_ID'] = $Pet_ID;
    $Pet_SQL = "DELETE FROM pets WHERE Pet_ID=:Pet_ID";

    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);

    if ($Results && $Display_Toast==true) {
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


// =============== BATTLE FUNCTIONS
public function Get_Pet_Skill_Array($Skill_Name)
{
    $Pet_Array = array(':Ability_Name' => $Skill_Name);
    $Pet_SQL = "SELECT * FROM pet_abilitys WHERE Ability_Name = :Ability_Name";
    $Results = $this->Connection->Custom_Query($Pet_SQL, $Pet_Array);

    return $Results;
}


// This function will initiate a battle between the supplied pets.
public function Attack($Skill_Name, $Type, $Defender_UserID=0, $Defender_PetID=0)
{
    unset($_SESSION[$Type.'_User_Pet_Bonus_Offense']);
    unset($_SESSION[$Type.'_User_Pet_Bonus_Defense']);
    unset($_SESSION[$Type.'_AI_Pet_Bonus_Offense']);
    unset($_SESSION[$Type.'_AI_Pet_Bonus_Defense']);
    $User_Chance_To_Hit = 75;
    $AI_Chance_To_Hit = 75;
    $User_Extra_Damage_Taken_Percent = 0;
    $AI_Extra_Damage_Taken_Percent = 0;
    $User_Poison_Damage_Taken_Percent = 0;
    $AI_Poison_Damage_Taken_Percent = 0;
    $User_Is_Stunned = false;
    $AI_Is_Stunned = false;
    $User_Is_Healing = false;
    $AI_Is_Healthing = false;
    $User_Extra_Defense_Percent = 0;
    $AI_Extra_Defense_Percent = 0;
    $User_Extra_Offense_Percent = 0;
    $AI_Extra_Offense_Percent = 0;
    $User_Damage_Returned_Percent = 0;
    $AI_Damage_Returned_Percent = 0;
    $User_Missed = false;
    $AI_Missed = false;



    //EFFECTS - USER
    if (!isset($_SESSION[$Type.'_User_Pet_Buffs'])) {$_SESSION[$Type.'_User_Pet_Buffs'] = array();}
    foreach ($_SESSION[$Type.'_User_Pet_Buffs'] as $BuffKey => $Buff) {
        switch ($Buff) {
            case 'Thorns':
                //Returns 10% damage back to attacker for 2 turns.
                $User_Damage_Returned_Percent += .10;
                $_SESSION[$Type.'_User_Pet_Buffs_Thorns_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Thorns_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Blind':
                //Decreases chance to hit 20% for 2 turns.
                $User_Chance_To_Hit -= 20;
                $_SESSION[$Type.'_User_Pet_Buffs_Blind_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Blind_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Wound':
                //Pet takes 35% more damage for 2 turns.
                $User_Extra_Damage_Taken_Percent += .35;
                $_SESSION[$Type.'_User_Pet_Buffs_Wound_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Wound_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Poison':
                //Pet takes 10% damage every turn for 2 turns. Not affected by armor.
                $User_Poison_Damage_Taken_Percent += .15;
                $_SESSION[$Type.'_User_Pet_Buffs_Poison_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Poison_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Stun':
                //Makes pet un-command able for 2 turns.
                $User_Is_Stunned = true;
                $_SESSION[$Type.'_User_Pet_Buffs_Stun_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Stun_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Focus':
                //Decreases chance to miss by 20% for 2 turns.
                $User_Chance_To_Hit += 20;
                $_SESSION[$Type.'_User_Pet_Buffs_Focus_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Focus_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Heal':
                //Pet Heals 20% each turn for 2 turns.
                $User_Is_Healing = true;
                $_SESSION[$Type.'_User_Pet_Buffs_Heal_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Heal_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Armor':
                //Increases pets defense by 20% for 2 turns.
                $User_Extra_Defense_Percent += .20;
                $_SESSION[$Type.'_User_Pet_Buffs_Armor_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Armor_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Frenzy':
                //Increases pets offense by 25% for 2 turns
                $User_Extra_Offense_Percent += .25;
                $_SESSION[$Type.'_User_Pet_Buffs_Frenzy_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Frenzy_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Evasion':
                //Increases chance to be missed by 20% for 2 turns.
                $AI_Chance_To_Hit -= 20;
                $_SESSION[$Type.'_User_Pet_Buffs_Evasion_Duration'] -= 1;
                if ($_SESSION[$Type.'_User_Pet_Buffs_Evasion_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_User_Pet_Buffs'][$BuffKey]);
                }
                break;

            default:
                # code...
                break;
        }
    }

    if (!isset($_SESSION[$Type.'_AI_Pet_Buffs'])) {$_SESSION[$Type.'_AI_Pet_Buffs'] = array();}
    //EFFECTS - AI
    foreach ($_SESSION[$Type.'_AI_Pet_Buffs'] as $BuffKey => $Buff) {
        switch ($Buff) {
            case 'Thorns':
                //Returns 10% damage back to attacker for 2 turns.
                $AI_Damage_Returned_Percent += .10;
                $_SESSION[$Type.'_AI_Pet_Buffs_Thorns_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Thorns_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Blind':
                //Decreases chance to hit 20% for 2 turns.
                $AI_Chance_To_Hit -= 20;
                $_SESSION[$Type.'_AI_Pet_Buffs_Blind_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Blind_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Wound':
                //Pet takes 35% more damage for 2 turns.
                $AI_Extra_Damage_Taken_Percent += .35;
                $_SESSION[$Type.'_AI_Pet_Buffs_Wound_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Wound_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Poison':
                //Pet takes 10% damage every turn for 2 turns. Not affected by armor.
                $AI_Poison_Damage_Taken_Percent += .15;
                $_SESSION[$Type.'_AI_Pet_Buffs_Poison_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Poison_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Stun':
                //Makes pet un-command able for 2 turns.
                $AI_Is_Stunned = true;
                $_SESSION[$Type.'_AI_Pet_Buffs_Stun_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Stun_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Focus':
                //Decreases chance to miss by 20% for 2 turns.
                $AI_Chance_To_Hit += 20;
                $_SESSION[$Type.'_AI_Pet_Buffs_Focus_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Focus_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Heal':
                //Pet Heals 15% each turn for 2 turns.
                $AI_Is_Healing = true;
                $_SESSION[$Type.'_AI_Pet_Buffs_Heal_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Heal_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Armor':
                //Increases pets defense by 20% for 2 turns.
                $AI_Extra_Defense_Percent += .20;
                $_SESSION[$Type.'_AI_Pet_Buffs_Armor_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Armor_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Frenzy':
                //Increases pets offense by 25% for 2 turns
                $AI_Extra_Offense_Percent += .25;
                $_SESSION[$Type.'_AI_Pet_Buffs_Frenzy_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Frenzy_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            case 'Evasion':
                //Increases chance to be missed by 20% for 2 turns.
                $User_Chance_To_Hit -= 20;
                $_SESSION[$Type.'_AI_Pet_Buffs_Evasion_Duration'] -= 1;
                if ($_SESSION[$Type.'_AI_Pet_Buffs_Evasion_Duration'] <= 0) {
                    unset($_SESSION[$Type.'_AI_Pet_Buffs'][$BuffKey]);
                }
                break;

            default:
                # code...
                break;
        }
    }

    if ($AI_Is_Stunned == false) {
        // Here we make sure the user's pet is the right level.
        $Weighted_Defend = rand(1,20);
        if ($Weighted_Defend <=5) {$Weighted_Defend = 0;} else {$Weighted_Defend = 1;}
        $Pet_Random_Ability = rand($Weighted_Defend,1);
        if ($_SESSION[$Type.'_AI_Pet_Level'] >= 3 && $_SESSION[$Type.'_AI_Pet_Skill_2_Cooldown'] <= 0) {
            $Pet_Random_Ability = rand($Weighted_Defend,2);
        }
        if ($_SESSION[$Type.'_AI_Pet_Level'] >= 10 && $_SESSION[$Type.'_AI_Pet_Skill_3_Cooldown'] <= 0) {
            $Pet_Random_Ability = rand($Weighted_Defend,3);
        }
        switch ($Pet_Random_Ability) {
            case '0':
                $AI_Defend_Pet_Defense = $_SESSION[$Type.'_AI_Pet_Defense'];
            case '1':
                $AI_Pet_Ability = $this->Get_Pet_Skill_Array($_SESSION[$Type.'_AI_Pet_Skill_1']);
                break;
            case '2':
                $AI_Pet_Ability = $this->Get_Pet_Skill_Array($_SESSION[$Type.'_AI_Pet_Skill_2']);
                break;
            case '3':
                $AI_Pet_Ability = $this->Get_Pet_Skill_Array($_SESSION[$Type.'_AI_Pet_Skill_3']);
                break;
        }
    }


    if ($Skill_Name!="Defend") {
        // This sets up the ability reference array for the User.
        $User_Pet_Ability = $this->Get_Pet_Skill_Array($Skill_Name);

        // Sets the random hit chance.
        $Random = rand(1,100);

        if ($User_Is_Stunned == false) {
            if ($User_Pet_Ability['Ability_Damage'] > 0) {
                if ($Random <= $User_Chance_To_Hit) {
                    $User_Pet_Offense_Modifier = $this->Get_Elemental_Modifier($User_Pet_Ability['Ability_Damage_Type'], $_SESSION[$Type.'_AI_Pet_Type']);
                    $User_Offense_Elemental = ceil(($_SESSION[$Type.'_User_Pet_Offense'] * $User_Pet_Offense_Modifier));

                    $User_Offense = rand(1,$User_Pet_Ability['Ability_Damage']) + $_SESSION[$Type.'_User_Pet_Offense'] + $_SESSION[$Type.'_User_Pet_Bonus_Offense'] + $User_Offense_Elemental;
                    $User_Offense = $User_Offense + ceil($User_Offense * $User_Extra_Offense_Percent);
                    $AI_Defense = ($_SESSION[$Type.'_AI_Pet_Defense'] + $_SESSION[$Type.'_AI_Pet_Bonus_Defense'] + $AI_Defend_Pet_Defense);
                    $AI_Defense = $AI_Defense + ceil($AI_Defense * $AI_Extra_Defense_Percent);
                    $AI_Percent_Blocked = ($AI_Defense * .01);
                    $AI_Damage_Blocked = ceil($User_Offense * $AI_Percent_Blocked);
                    $User_Damage_Done = ceil(($User_Offense + ($User_Offense * $AI_Extra_Damage_Taken_Percent)) - $AI_Damage_Blocked);
                    if ($User_Damage_Done < 0) {$User_Damage_Done = 0;}

                    $_SESSION[$Type.'_AI_Pet_Current_Health'] = $_SESSION[$Type.'_AI_Pet_Current_Health'] - $User_Damage_Done;

                    if ($User_Pet_Ability['Ability_Effect'] != 'None') { $User_Ability_Triggered = "[".$User_Pet_Ability['Ability_Effect'] . "] was also triggered."; }

                    $Return_Array['UserAction'] = "YOU used ability " . $User_Pet_Ability['Ability_Name'] . " on enemy pet dealing [" . $User_Damage_Done . "] + [".$User_Offense_Elemental."] elemental damage. ".$User_Ability_Triggered." The enemy blocked [" . ceil($AI_Damage_Blocked) . "] damage with [" . $AI_Defense . "%] reduction.";
                } else {
                    $User_Missed = true;
                    $Return_Array['UserAction'] = "YOU MISSED!";
                }
            } else {
                $Return_Array['UserAction'] = "YOU used ability " . $User_Pet_Ability['Ability_Name'] . " which has the effect of [" . $User_Pet_Ability['Ability_Effect'] . "].";
            }
        }
    }

    if ($User_Is_Stunned == true) {
        $Return_Array['UserAction'] = "YOUR Pet is Stunned and cannot fight.";
    }

    if ($Skill_Name=="Defend" && $User_Is_Stunned == false){
        $User_Defend_Pet_Defense = $_SESSION[$Type.'_User_Pet_Defense'];
        $Return_Array['UserAction'] = "YOU used ability " . $Skill_Name . " and raised your defense to [" . $User_Defend_Pet_Defense * 2 . "]";
    }

    if (!isset($AI_Defend_Pet_Defense)) {
        // Here we check to make sure the enemy pet didn't die before having him attack back.
        if ($_SESSION[$Type.'_AI_Pet_Current_Health'] > 0) {
            $Random = rand(1,100);

            if ($AI_Is_Stunned == false) {
                if ($AI_Pet_Ability['Ability_Damage'] > 0) {
                    if ($Random <= $AI_Chance_To_Hit ) {
                        $AI_Pet_Offense_Modifier = $this->Get_Elemental_Modifier($AI_Pet_Ability['Ability_Damage_Type'], $_SESSION[$Type.'_User_Pet_Type']);
                        $AI_Offense_Elemental = ceil(($_SESSION[$Type.'_AI_Pet_Offense'] * $AI_Pet_Offense_Modifier));

                        $AI_Offense = rand(1,$AI_Pet_Ability['Ability_Damage']) + $_SESSION[$Type.'_AI_Pet_Offense'] + $_SESSION[$Type.'_AI_Pet_Bonus_Offense'];
                        $AI_Offense = $AI_Offense + ceil($AI_Offense * $AI_Extra_Offense_Percent);
                        $User_Defense = ($_SESSION[$Type.'_User_Pet_Defense'] + $_SESSION[$Type.'_User_Pet_Bonus_Defense']);
                        $User_Defense = $User_Defense + ceil($User_Defense * $User_Extra_Defense_Percent);
                        $User_Percent_Blocked = (($User_Defense + $User_Defend_Pet_Defense) * .01);
                        $User_Damage_Blocked = ceil($AI_Offense * $User_Percent_Blocked);
                        $AI_Damage_Done = ceil(($AI_Offense + ($AI_Offense * $User_Extra_Damage_Taken_Percent)) - ceil($User_Damage_Blocked));
                        if ($AI_Damage_Done < 0) {$AI_Damage_Done = 0;}

                        $_SESSION[$Type.'_User_Pet_Current_Health'] = $_SESSION[$Type.'_User_Pet_Current_Health'] - $AI_Damage_Done;

                        if ($AI_Pet_Ability['Ability_Effect'] != 'None') { $AI_Ability_Triggered = "[".$AI_Pet_Ability['Ability_Effect'] . "] was also triggered."; }

                        $Return_Array['AIAction'] = "ENEMY used ability " . $AI_Pet_Ability['Ability_Name'] . " on your pet dealing [" . $AI_Damage_Done ."] + [".$AI_Offense_Elemental."] elemental damage. ".$AI_Ability_Triggered." You blocked [" . ceil($User_Damage_Blocked) . "] damage with [" . ($User_Defense + $User_Defend_Pet_Defense) . "%] reduction.";
                    } else {
                        $AI_Missed = true;
                        $Return_Array['AIAction'] = "ENEMY MISSED!";
                    }
                } else {
                    $Return_Array['AIAction'] = "ENEMY used ability " . $AI_Pet_Ability['Ability_Name'] . " which has the effect of [" . $AI_Pet_Ability['Ability_Effect'] . "].";
                }
            } else {
                $Return_Array['AIAction'] = "ENEMY is Stunned and cannot attack.";
            }
        }
    }

    if (isset($AI_Defend_Pet_Defense) && $AI_Is_Stunned == false) {
        $Return_Array['AIAction'] = "ENEMY used ability Defend and raised it's defense to [" . ($AI_Defend_Pet_Defense * 2) . "]";
    }

    // Here we calculate thorns damage and apply it.
    if ($User_Damage_Returned_Percent > 0 && $AI_Damage_Done > 0 && $AI_Missed == false) {
        $Thorns_Damage_To_AI = ceil($AI_Damage_Done * $User_Damage_Returned_Percent);
        $_SESSION[$Type.'_AI_Pet_Current_Health'] -= $Thorns_Damage_To_AI;
        $Return_Array['AIAction'] .= "<br>[" . $Thorns_Damage_To_AI . "] Thorns damage was also taken.";
    }

    if ($AI_Damage_Returned_Percent > 0 && $User_Damage_Done > 0 && $User_Missed == false) {
        $Thorns_Damage_To_User = ceil($User_Damage_Done * $AI_Damage_Returned_Percent);
        $_SESSION[$Type.'_User_Pet_Current_Health'] -= $Thorns_Damage_To_User;
        $Return_Array['UserAction'] .= "<br>[" . $Thorns_Damage_To_User . "] Thorns damage was also taken.";
    }

    // Poison Buff
    if ($User_Poison_Damage_Taken_Percent > 0) {
        $Poison_Damage = ceil($_SESSION[$Type.'_User_Pet_Current_Health'] * $User_Poison_Damage_Taken_Percent);
        $_SESSION[$Type.'_User_Pet_Current_Health'] -= $Poison_Damage;
        $Return_Array['UserAction'] .= "<br>[" . $Poison_Damage . "] Poison damage was also taken.";
    }
    if ($AI_Poison_Damage_Taken_Percent > 0) {
        $Poison_Damage = ceil($_SESSION[$Type.'_AI_Pet_Current_Health'] * $AI_Poison_Damage_Taken_Percent);
        $_SESSION[$Type.'_AI_Pet_Current_Health'] -= $Poison_Damage;
        $Return_Array['AIAction'] .= "<br>[" . $Poison_Damage . "] Poison damage was also taken.";
    }


    // Healing Buff
    if ($User_Is_Healing == true) {
        $HP_Healed = ceil($_SESSION[$Type.'_User_Pet_Max_Health'] * .05);
        $Difference = $_SESSION[$Type.'_User_Pet_Max_Health'] - $_SESSION[$Type.'_User_Pet_Current_Health'];
        if ($HP_Healed > $Difference) {
            $HP_Healed -= $HP_Healed - $Difference;
        }
        $_SESSION[$Type.'_User_Pet_Current_Health'] += $HP_Healed;
        $Return_Array['UserAction'] .= "<br>[" . $HP_Healed . "] damage was also Healed.";
        unset($HP_Healed);
    }
    if ($AI_Is_Healing == true) {
        $HP_Healed = ceil($_SESSION[$Type.'_AI_Pet_Max_Health'] * .05);
        $Difference = $_SESSION[$Type.'_AI_Pet_Max_Health'] - $_SESSION[$Type.'_AI_Pet_Current_Health'];
        if ($HP_Healed > $Difference) {
            $HP_Healed -= $HP_Healed - $Difference;
        }
        $_SESSION[$Type.'_AI_Pet_Current_Health'] += $HP_Healed;
        $Return_Array['AIAction'] .= "<br>[" . $HP_Healed . "] damage was also Healed.";
        unset($HP_Healed);
    }


    //Reduce the cooldowns on abilitys.
    if ($_SESSION[$Type.'_User_Pet_Skill_1_Cooldown'] > 0) { $_SESSION[$Type.'_User_Pet_Skill_1_Cooldown'] = $_SESSION[$Type.'_User_Pet_Skill_1_Cooldown'] - 1;}
    if ($_SESSION[$Type.'_User_Pet_Skill_2_Cooldown'] > 0) { $_SESSION[$Type.'_User_Pet_Skill_2_Cooldown'] = $_SESSION[$Type.'_User_Pet_Skill_2_Cooldown'] - 1;}
    if ($_SESSION[$Type.'_User_Pet_Skill_3_Cooldown'] > 0) { $_SESSION[$Type.'_User_Pet_Skill_3_Cooldown'] = $_SESSION[$Type.'_User_Pet_Skill_3_Cooldown'] - 1;}

    if ($_SESSION[$Type.'_AI_Pet_Skill_1_Cooldown'] > 0) { $_SESSION[$Type.'_AI_Pet_Skill_1_Cooldown'] = $_SESSION[$Type.'_AI_Pet_Skill_1_Cooldown'] - 1;}
    if ($_SESSION[$Type.'_AI_Pet_Skill_2_Cooldown'] > 0) { $_SESSION[$Type.'_AI_Pet_Skill_2_Cooldown'] = $_SESSION[$Type.'_AI_Pet_Skill_2_Cooldown'] - 1;}
    if ($_SESSION[$Type.'_AI_Pet_Skill_3_Cooldown'] > 0) { $_SESSION[$Type.'_AI_Pet_Skill_3_Cooldown'] = $_SESSION[$Type.'_AI_Pet_Skill_3_Cooldown'] - 1;}


    // Here we set cooldowns
    if ($Skill_Name == $_SESSION[$Type.'_User_Pet_Skill_1']) { $_SESSION[$Type.'_User_Pet_Skill_1_Cooldown'] = $User_Pet_Ability["Ability_Cooldown"];}
    if ($Skill_Name == $_SESSION[$Type.'_User_Pet_Skill_2']) { $_SESSION[$Type.'_User_Pet_Skill_2_Cooldown'] = $User_Pet_Ability["Ability_Cooldown"];}
    if ($Skill_Name == $_SESSION[$Type.'_User_Pet_Skill_3']) { $_SESSION[$Type.'_User_Pet_Skill_3_Cooldown'] = $User_Pet_Ability["Ability_Cooldown"];}

    if ($AI_Pet_Ability["Ability_Name"] == $_SESSION[$Type.'_AI_Pet_Skill_1']) { $_SESSION[$Type.'_AI_Pet_Skill_1_Cooldown'] = $AI_Pet_Ability["Ability_Cooldown"];}
    if ($AI_Pet_Ability["Ability_Name"] == $_SESSION[$Type.'_AI_Pet_Skill_2']) { $_SESSION[$Type.'_AI_Pet_Skill_2_Cooldown'] = $AI_Pet_Ability["Ability_Cooldown"];}
    if ($AI_Pet_Ability["Ability_Name"] == $_SESSION[$Type.'_AI_Pet_Skill_3']) { $_SESSION[$Type.'_AI_Pet_Skill_3_Cooldown'] = $AI_Pet_Ability["Ability_Cooldown"];}


    // Here we apply weapon effects if the pet hit.
    if ($User_Missed == false && $User_Is_Stunned == false && $User_Pet_Ability['Ability_Effect'] != "None") {
        $this->Add_Buff_To_AI_From_User($User_Pet_Ability['Ability_Effect'], $Type);
    }
    if ($AI_Missed == false && $AI_Is_Stunned == false && $AI_Pet_Ability['Ability_Effect'] != "None") {
        $this->Add_Buff_To_User_From_AI($AI_Pet_Ability['Ability_Effect'], $Type);
    }

    // Here we update the Daily Quests.
    if ($User_Damage_Done > 0) {
        $this->Update_Daily_Quest(6,$User_Damage_Done);
    }

    // Here we check to see if any side has won the battle.
    $User_Won = false;
    if ($Type=='PVE'){
        if ($_SESSION[$Type.'_AI_Pet_Current_Health'] <= 0) {
            $User_Won = true;
            $this->PVE_Win_Battle();
        }

        if ($_SESSION[$Type.'_User_Pet_Current_Health'] <= 0 && $User_Won == false) {
            $this->PVE_Lose_Battle();
        }
    }

    if ($Type=='PVP'){
        if ($_SESSION[$Type.'_AI_Pet_Current_Health'] <= 0) {
            $User_Won = true;
            $this->PVP_Win_Battle();
        }

        if ($_SESSION[$Type.'_User_Pet_Current_Health'] <= 0 && $User_Won == false) {
            $this->PVP_Lose_Battle();
        }
    }

    if ($Type=='BOSS'){
        if ($_SESSION[$Type.'_AI_Pet_Current_Health'] <= 0) {
            $User_Won = true;
            $this->BOSS_Win_Battle();
        }

        if ($_SESSION[$Type.'_User_Pet_Current_Health'] <= 0 && $User_Won == false) {
            $this->BOSS_Lose_Battle();
        }
    }

    return $Return_Array;

}

public function Add_Buff_To_AI_From_User($Effect, $Type)
{

    switch ($Effect) {
        case 'Blind':
            //Decreases chance to hit 20% for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Blind';
                $_SESSION[$Type.'_AI_Pet_Buffs_Blind_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Blind_Duration'] += 2;
            break;
        case 'Wound':
            //Pet takes 35% more damage for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Wound';
                $_SESSION[$Type.'_AI_Pet_Buffs_Wound_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Wound_Duration'] += 2;
            break;
        case 'Poison':
            //Pet takes 10% damage every turn for 2 turns. Not affected by armor.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Poison';
                $_SESSION[$Type.'_AI_Pet_Buffs_Poison_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Poison_Duration'] += 2;
            break;
        case 'Stun':
            //Makes pet un-command able for 1 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Stun';
                $_SESSION[$Type.'_AI_Pet_Buffs_Stun_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Stun_Duration'] += 1;
            break;
        case 'Thorns':
            //Returns 10% damage back to attacker for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Thorns';
                $_SESSION[$Type.'_User_Pet_Buffs_Thorns_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Thorns_Duration'] += 2;
            break;
        case 'Focus':
            //Decreases chance to be miss by 20% for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Focus';
                $_SESSION[$Type.'_User_Pet_Buffs_Focus_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Focus_Duration'] += 2;
            break;
        case 'Heal':
            //Pet Heals 15% each turn for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Heal';
                $_SESSION[$Type.'_User_Pet_Buffs_Heal_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Heal_Duration'] += 2;
            break;
        case 'Armor':
            //Increases pets defense by 20% for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Armor';
                $_SESSION[$Type.'_User_Pet_Buffs_Armor_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Armor_Duration'] += 2;
            break;
        case 'Frenzy':
            //Increases pets offense by 25% for 2 turns
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Frenzy';
                $_SESSION[$Type.'_User_Pet_Buffs_Frenzy_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Frenzy_Duration'] += 2;
            break;
        case 'Evasion':
            //Increases chance to be missed by 20% for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Evasion';
                $_SESSION[$Type.'_User_Pet_Buffs_Evasion_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Evasion_Duration'] += 2;
            break;

        default:

            break;
    }
}

public function Add_Buff_To_User_From_AI($Effect, $Type)
{
    switch ($Effect) {
        case 'Blind':
            //Decreases chance to hit by 20% for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Blind';
                $_SESSION[$Type.'_User_Pet_Buffs_Blind_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Blind_Duration'] += 2;
            break;
        case 'Wound':
            //Pet takes 35% more damage for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Wound';
                $_SESSION[$Type.'_User_Pet_Buffs_Wound_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Wound_Duration'] += 2;
            break;
        case 'Poison':
            //Pet takes 10% damage every turn for 2 turns. Not affected by armor.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Poison';
                $_SESSION[$Type.'_User_Pet_Buffs_Poison_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Poison_Duration'] += 2;
            break;
        case 'Stun':
            //Makes pet un-command able for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_User_Pet_Buffs'])) {
                $_SESSION[$Type.'_User_Pet_Buffs'][] = 'Stun';
                $_SESSION[$Type.'_User_Pet_Buffs_Stun_Duration'] = 0;
            }
            $_SESSION[$Type.'_User_Pet_Buffs_Stun_Duration'] += 1;
            break;
        case 'Thorns':
            //Returns 10% damage back to attacker for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Thorns';
                $_SESSION[$Type.'_AI_Pet_Buffs_Thorns_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Thorns_Duration'] += 2;
            break;
        case 'Focus':
            //Decreases chance to miss by 20% for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Focus';
                $_SESSION[$Type.'_AI_Pet_Buffs_Focus_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Focus_Duration'] += 2;
            break;
        case 'Heal':
            //Pet Heals 15% each turn for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Heal';
                $_SESSION[$Type.'_AI_Pet_Buffs_Heal_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Heal_Duration'] += 2;
            break;
        case 'Armor':
            //Increases pets defense by 20% for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Armor';
                $_SESSION[$Type.'_AI_Pet_Buffs_Armor_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Armor_Duration'] += 2;
            break;
        case 'Frenzy':
            //Increases pets offense by 25% for 2 turns
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Frenzy';
                $_SESSION[$Type.'_AI_Pet_Buffs_Frenzy_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Frenzy_Duration'] += 2;
            break;
        case 'Evasion':
            //Increases chance to be missed by 20% for 2 turns.
            if (!in_array($Effect, $_SESSION[$Type.'_AI_Pet_Buffs'])) {
                $_SESSION[$Type.'_AI_Pet_Buffs'][] = 'Evasion';
                $_SESSION[$Type.'_AI_Pet_Buffs_Evasion_Duration'] = 0;
            }
            $_SESSION[$Type.'_AI_Pet_Buffs_Evasion_Duration'] += 2;
            break;

        default:
            # code...
            break;
    }
}

public function PVE_Win_Battle()
{
    $EXP_Earned = rand(1,20 - $this->Pet_Level);
    $this->Give_Exp($this->User_ID, $this->Pet_ID, $EXP_Earned);
    $this->Clear_Battle_Room('PVE');
    $this->Add_Battles_Won($this->User_ID);
    $this->Update_Daily_Quest(3,1);
    Toasts::addNewToast("You just won a battle!<br> + " . $EXP_Earned . " Exp", 'petbattle');
}

public function PVE_Lose_Battle()
{
    $this->Clear_Battle_Room('PVE');
    $this->Add_Battles_Lost($this->User_ID);
    Toasts::addNewToast("You just lost a battle :(!", 'petbattle');
}

public function PVP_Win_Battle()
{
    $AI = new BattlePet($_SESSION['PVP_AI_User_ID'], $_SESSION['PVP_AI_Pet_ID']);
    $User = new User($this->User_ID);

    $LevelDifference = $AI->Pet_Level - $this->Pet_Level;
    if ($LevelDifference < 0) {
        $EXP_Earned = rand(1,20 - $this->Pet_Level) - abs($LevelDifference);
    } else {
        $EXP_Earned = rand(1,20 - $this->Pet_Level) + $LevelDifference;
    }
    $Random_Points = rand(1,3);
    $User->Add_Points($Random_Points);
    $this->Give_Exp($this->User_ID, $this->Pet_ID, $EXP_Earned);
    $this->Add_Battles_Won($this->User_ID);
    $this->Update_Daily_Quest(4,1);

    $AI->Add_Battles_Lost($_SESSION['PVP_AI_User_ID']);

    $this->Clear_Battle_Room('PVP');
    Toasts::addNewToast("You just won a PVP battle!<br> + " . $EXP_Earned . " Exp<br> + ". $Random_Points ." points!", 'petbattle');
}

public function PVP_Lose_Battle()
{
    $this->Add_Battles_Lost($this->User_ID);

    $AI = new BattlePet($_SESSION['PVP_AI_User_ID'], $_SESSION['PVP_AI_Pet_ID']);
    $LevelDifference = $AI->Pet_Level - $this->Pet_Level ;

    $AI->Add_Battles_Won($_SESSION['PVP_AI_User_ID']);

    if ($LevelDifference < 0) {
        $EXP_Earned = rand($AI->Pet_Level, 20 - $AI->Pet_Level) + abs($LevelDifference);
    } else {
        $EXP_Earned = rand($AI->Pet_Level, 20 - $AI->Pet_Level) - abs($LevelDifference);
    }

    $AI->Give_Exp($AI->User_ID, $AI->Pet_ID, $EXP_Earned);

    $this->Clear_Battle_Room('PVP');
    Toasts::addNewToast("You just lost a PVP battle :(!<br>Enemy pet earned " . $EXP_Earned . " EXP", 'petbattle');
}

public function BOSS_Win_Battle()
{
    $this->Clear_Battle_Room('BOSS');
    $this->Add_Battles_Won($this->User_ID);
    $User = new User($this->User_ID);
    $User->Add_Points(300);
    Toasts::addNewToast("You just beat the boss!<br>You received 300 Points!", 'petbattle');
}

public function BOSS_Lose_Battle()
{
    $this->Clear_Battle_Room('BOSS');
    $this->Add_Battles_Lost($this->User_ID);
    Toasts::addNewToast("You just lost against the boss!<br>Try training your pet with items to help beat him.", 'petbattle');
}

// This function will try to catch a Wild pet you're fighting.
public function PVE_Catch_Pet()
{
    $Chance_Range_Percent = ($_SESSION['PVE_AI_Pet_Current_Health'] / $_SESSION['PVE_AI_Pet_Max_Health']);
    $Chance = (100 * $Chance_Range_Percent);
    $Random = rand(1, $Chance);

    if ($Random <= 8) {
        // YOU CAUGHT IT!
        $this->Give_Caught_Pet();
        Toasts::addNewToast("You just caught [{$_SESSION['PVE_AI_Pet_Name']}] <br>(" . number_format(100-$Chance,2,'.','') . "%) Chance", 'petbattle');
        $this->Add_Pets_Caught();
        $this->Update_Daily_Quest(1,1);
        $this->Update_Daily_Quest(2,1);
        $this->Clear_Battle_Room('PVE');
    } else {
        // YOU MISSED! WTF!?
        Toasts::addNewToast("Pet [{$_SESSION['PVE_AI_Pet_Name']}] got away! <br>(" . number_format(100-$Chance,2,'.','') . "%) Chance", 'petbattle');
        $this->Clear_Battle_Room('PVE');
    }
}

// Subtract from the current users AP.
public function Subtract_AP($NewAP, $Pet_ID)
{
    $Pet_Array = array (':Pet_ID'=>$Pet_ID, ':Pet_Current_AP'=>$NewAP);
    $Pet_SQL = "UPDATE pets SET Pet_Current_AP = Pet_Current_AP - :Pet_Current_AP WHERE Pet_ID = :Pet_ID";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
}

// Add to the users current AP.
public function Add_AP($NewAP, $Pet_ID)
{
    $Pet_Array = array (':Pet_ID'=>$Pet_ID, ':Pet_Current_AP'=>$NewAP);
    $Pet_SQL = "UPDATE pets SET Pet_Current_AP = Pet_Current_AP + :Pet_Current_AP WHERE Pet_ID = :Pet_ID";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
}

// Saves a direct amount of AP for the user.
public function Save_AP($NewAP, $Pet_ID)
{
    $Pet_Array = array (':Pet_ID'=>$Pet_ID, ':Pet_Current_AP'=>$NewAP);
    $Pet_SQL = "UPDATE pets SET Pet_Current_AP = :Pet_Current_AP WHERE Pet_ID = :Pet_ID";
    $Results = $this->Connection->Custom_Execute($Pet_SQL, $Pet_Array);
}

// Returns an INT value used to add or negate modifiers based on Attack/Defense pet Type.
public function Get_Elemental_Modifier($Attacking_Type, $Defending_Type)
{
    $Strong_Against_Modifier = .2;
    $Weak_Against_Modifier = -.2;
    $Normal_Modifier = 0;

    switch ($Attacking_Type) {
        case 'Normal':
            return $Normal_Modifier;
            break;
        case 'Water':
            switch ($Defending_Type) {
                case 'Fire':
                    return $Strong_Against_Modifier;
                    break;
                case 'Wind':
                    return $Normal_Modifier;
                    break;
                case 'Water':
                    return $Normal_Modifier;
                    break;
                case 'Earth':
                    return $Weak_Against_Modifier;
                    break;
                default:
                    return $Normal_Modifier;
                    break;
            }
            break;
        case 'Fire':
            switch ($Defending_Type) {
                case 'Fire':
                    return $Normal_Modifier;
                    break;
                case 'Wind':
                    return $Strong_Against_Modifier;
                    break;
                case 'Water':
                    return $Weak_Against_Modifier;
                    break;
                case 'Earth':
                    return $Normal_Modifier;
                    break;
                default:
                    return $Normal_Modifier;
                    break;
            }
            break;
        case 'Wind':
            switch ($Defending_Type) {
                case 'Fire':
                    return $Weak_Against_Modifier;
                    break;
                case 'Wind':
                    return $Normal_Modifier;
                    break;
                case 'Water':
                    return $Normal_Modifier;
                    break;
                case 'Earth':
                    return $Strong_Against_Modifier;
                    break;
                default:
                    return $Normal_Modifier;
                    break;
            }
            break;
        case 'Earth':
            switch ($Defending_Type) {
                case 'Fire':
                    return $Normal_Modifier;
                    break;
                case 'Wind':
                    return $Weak_Against_Modifier;
                    break;
                case 'Water':
                    return $Strong_Against_Modifier;
                    break;
                case 'Earth':
                    return $Normal_Modifier;
                    break;
                default:
                    return $Normal_Modifier;
                    break;
            }
            break;

        default:
            return $Normal_Modifier;
            break;
    }
}

// Purchases a new item from the shop for a user.
public function Purchase_Item($Item_Name, $Item_Cost, $Item_Description, $Item_Image)
{
    $Item_Array = array (':User_ID'=>$this->User_ID, ':Item_Name'=>$Item_Name, ':Item_Image'=>$Item_Image, ':Item_Description'=>$Item_Description);
    $Item_SQL = "INSERT INTO inventory (User_ID, Item_Name, Item_Image, Item_Description) VALUES (:User_ID,:Item_Name,:Item_Image,:Item_Description)";
    $Results = $this->Connection->Custom_Execute($Item_SQL, $Item_Array);


    if ($Results) {
        $User = new User($this->User_ID);
        $User->Subtract_Points($Item_Cost);
        Toasts::addNewToast("You bought a [" . $Item_Name . "]<br> - " . $Item_Cost . " Points", 'petbattle');
    } else {
        Toasts::addNewToast("There was a problem purchasing your item, please try again.", 'error');
    }
}

// Remove an item from a users inventory.
public function Remove_Item($Item_ID)
{
    $Item_Array = array (':Item_ID'=>$Item_ID);
    $Item_SQL = "DELETE FROM inventory WHERE Item_ID=:Item_ID";
    $Results = $this->Connection->Custom_Execute($Item_SQL, $Item_Array);

}

// Returns the number of items the user has.
public function Get_Item_Count($Item_Name)
{
    $Item_Array = array(':User_ID' => $this->User_ID, ':Item_Name'=>$Item_Name);
    $Item_SQL = "SELECT COUNT(*) FROM inventory WHERE User_ID = :User_ID AND Item_Name=:Item_Name";
    $Results = $this->Connection->Custom_Count_Query($Item_SQL, $Item_Array);

    return $Results[0];
}

// Returns the items image string from the DB.
public function Get_Item_Image($Item_Name)
{
    $Item_Array = array(':User_ID' => $this->User_ID, ':Item_Name'=>$Item_Name);
    $Item_SQL = "SELECT * FROM inventory WHERE User_ID = :User_ID AND Item_Name=:Item_Name";
    $Results = $this->Connection->Custom_Query($Item_SQL, $Item_Array);

    return $Results['Item_Image'];
}

// This Functions triggers every time an item is used.
public function Event_Item_Used()
{
    $this->Update_Daily_Quest(5,1);
}

// Returns the specified item's description.
public function Get_Item_Description($Item_Name)
{
    $Item_Array = array(':User_ID' => $this->User_ID, ':Item_Name'=>$Item_Name);
    $Item_SQL = "SELECT * FROM inventory WHERE User_ID = :User_ID AND Item_Name=:Item_Name";
    $Results = $this->Connection->Custom_Query($Item_SQL, $Item_Array);

    return $Results['Item_Description'];
}

// Returns the Item ID based on Item Name
public function Get_Item_ID($Item_Name)
{
    $Item_Array = array(':User_ID' => $this->User_ID, ':Item_Name'=>$Item_Name);
    $Item_SQL = "SELECT * FROM inventory WHERE User_ID = :User_ID AND Item_Name=:Item_Name";
    $Results = $this->Connection->Custom_Query($Item_SQL, $Item_Array);

    return $Results['Item_ID'];
}

// Returns the total item count for the user.
public function Get_All_Item_Count()
{
    $Item_Array = array(':User_ID' => $this->User_ID);
    $Item_SQL = "SELECT COUNT(*) FROM inventory WHERE User_ID = :User_ID";
    $Results = $this->Connection->Custom_Count_Query($Item_SQL, $Item_Array);

    return $Results[0];
}

// DAILY QUESTS


// Returns an array containing 1 base daily quest's info based on the ID.
public function Get_Base_Daily_Quest_Info($ID)
{
    $Quest_Array = array(':ID' => $ID);
    $Quest_SQL = "SELECT * FROM base_daily_quests WHERE ID = :ID";
    $Results = $this->Connection->Custom_Query($Quest_SQL, $Quest_Array);

    return $Results;
} 

// Returns an array of ALL daily quests owned by a user.
public function Get_All_Daily_Quests()
{
    $Quest_Array = array(':UserID' => $this->User_ID);
    $Quest_SQL = "SELECT * FROM daily_quests WHERE UserID = :UserID";
    $Results = $this->Connection->Custom_Query($Quest_SQL, $Quest_Array, true);

    return $Results;
}

// Returns an array of 1 random base daily quest.
private function Get_Random_Daily_Quest()
{
    $Item_Array = array();
    $Item_SQL = "SELECT * FROM base_daily_quests";
    $Random_Results = $this->Connection->Custom_Query($Item_SQL, $Item_Array, true);

    shuffle($Random_Results);

    $Results = array_pop($Random_Results);

    return $Results;
}

// Gives a random daily quest if they have not gotten a new one in 24 hours.
public function Give_Daily_Quest()
{

    $User = new User($this->User_ID);
    $Seconds_Difference = time() - strtotime($User->Last_Daily_Quest_Recieved);
    $Number_Of_Quests_To_Give = 0;

    // We are calculating out how many days they have been gone, they can only get up to 3 max daily quests as that's the limit.
    if ($Seconds_Difference > 86400 && $Seconds_Difference < 172800)  { $Number_Of_Quests_To_Give = 1; }
    if ($Seconds_Difference > 172800 && $Seconds_Difference < 259200) { $Number_Of_Quests_To_Give = 2; }
    if ($Seconds_Difference > 259200) { $Number_Of_Quests_To_Give = 3;}

    // Depending on how many quests they are getting we add one per.
    while( $Number_Of_Quests_To_Give >= 1) {
        if ($this->Get_Total_Daily_Quests() < 3) {

            $Random_Quest = $this->Get_Random_Daily_Quest();
            
            while ($this->User_Has_Daily_Quest($Random_Quest['ID'])==true) {
                $Random_Quest = $this->Get_Random_Daily_Quest();
            }

            // Here we build up and save the quest into the DB.
            $Quest_Array = array(':QuestID' => $Random_Quest['ID'], ':UserID' => $this->User_ID, ':NeededObjective' => $Random_Quest['NeededObjective'], ':CurrentObjective' => 0, ':Points' => $Random_Quest['Points'] );
            $Quest_SQL = "INSERT INTO daily_quests (QuestID, UserID, CurrentObjective, NeededObjective, Points) VALUES (:QuestID, :UserID, :CurrentObjective, :NeededObjective, :Points)";
            $Results = $this->Connection->Custom_Execute($Quest_SQL, $Quest_Array);
            $New_Quest_ID = $this->Connection->PDO_Connection->lastInsertId();

            $Quest_Array = array();
            $Quest_Array[':UserID']=$this->User_ID;
            $Quest_Array[':Last_Daily_Quest_Recieved']=date("Y-m-d H:i:s");


            $Quest_SQL = "UPDATE users SET Last_Daily_Quest_Recieved=:Last_Daily_Quest_Recieved WHERE ID=:UserID";
            $Results = $this->Connection->Custom_Execute($Quest_SQL, $Quest_Array);

            Toasts::addNewToast("You just got a new Daily Quest!<br> [ <b>{$Random_Quest['Name']}</b> ] - {$Random_Quest['Points']} Points <br> {$Random_Quest['Description']}", 'success');
            $Number_Of_Quests_To_Give--;
        } else {
            break;
        }
    }

}

// Returns the amount of daily quests the user has.
public function Get_Total_Daily_Quests()
{
    $Quest_Array = array(':UserID' => $this->User_ID);
    $Quest_SQL = "SELECT COUNT(*) FROM daily_quests WHERE UserID = :UserID";
    $Results = $this->Connection->Custom_Count_Query($Quest_SQL, $Quest_Array);

    return $Results[0];
}

// Returns an array of a SPECIFIC daily quest.
public function Get_One_Daily_Quest($QuestID)
{
    $Quest_Array = array(':UserID' => $this->User_ID, ':QuestID' => $QuestID);
    $Quest_SQL = "SELECT * FROM daily_quests WHERE UserID = :UserID AND QuestID = :QuestID";
    $Results = $this->Connection->Custom_Query($Quest_SQL, $Quest_Array);

    return $Results;
}

// Removes a daily quest from a users log.
public function Remove_Daily_Quest($QuestID)
{
    $Quest_Array = array(':UserID' => $this->User_ID, ':QuestID' => $QuestID);
    $Quest_SQL = "DELETE FROM daily_quests WHERE UserID = :UserID AND QuestID = :QuestID";
    $Results = $this->Connection->Custom_Execute($Quest_SQL, $Quest_Array);
}

// Main function to check if a user has completed a daily quest or not.
public function Check_If_Daily_Quest_Completed()
{
    //This will be called at the load of every page, we check against the DB and see if we have completed any of the quests.
    $Current_Dailys = $this->Get_All_Daily_Quests();

    foreach ($Current_Dailys as $Daily) {
        if ($Daily['CurrentObjective'] >= $Daily['NeededObjective']) {

            $User = new User($this->User_ID);
            $User->Add_Points($Daily['Points']);
            $this->Remove_Daily_Quest($Daily['QuestID']);
            Toasts::addNewToast("You just completed a Daily Quest!<br> + {$Daily['Points']} Points", 'success');

        }
    }
}

// Returns true or false depending on if the user has the specified daily quest.
public function User_Has_Daily_Quest($QuestID)
{
    $Quest_Array = array(':UserID' => $this->User_ID, ':QuestID' => $QuestID);
    $Quest_SQL = "SELECT COUNT(*) FROM daily_quests WHERE UserID = :UserID AND QuestID = :QuestID";
    $Results = $this->Connection->Custom_Count_Query($Quest_SQL, $Quest_Array);

    if ($Results[0] >= 1) {
        return true;
    } else {
        return false;
    }
}

// Updates a daily quest's progress if the user has it.
public function Update_Daily_Quest($QuestID, $QuestObjective)
{
    if ($this->User_Has_Daily_Quest($QuestID)==true) {
        $Quest_Array = array();
        $Quest_Array[':UserID']=$this->User_ID;
        $Quest_Array[':QuestObjective']=$QuestObjective;
        $Quest_Array[':QuestID']=$QuestID;

        $Quest_SQL = "UPDATE daily_quests SET CurrentObjective=CurrentObjective+:QuestObjective WHERE UserID=:UserID AND QuestID=:QuestID";
        $Results = $this->Connection->Custom_Execute($Quest_SQL, $Quest_Array);
    }
}


} // END CLASS
