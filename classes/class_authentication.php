<?php

class Authentication
{
public $Connection;
const DATE_FORMAT = 'Y-m-d H:i:s';

function __construct()
{
    $this->Connection = new Connection();
}

function Login($User,$Pass)
{
    $Username = Functions::Make_Safe($User);
    $Password = Functions::Make_Safe($Pass);
    $Password = md5($Password);

    // Check if parameters are blank
    if($Username!='' && $Password!='') {

            // Query to get the credentials of the user logging in.
            $Login_Array = array(':Username'=>$Username, ':Password'=>$Password);
            $Login_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE Username=:Username AND Password=:Password", $Login_Array);

            // Check and see if there were any matches in the result.
            if (!empty($Login_Result)) {

                    //Check to see if the account is locked.
                    if($Login_Result['Account_Locked'] == 0){

                        //Login is successfully, now we set up our session variables.
                        $_SESSION["Name"] = $Login_Result["Username"];
                        $_SESSION["ID"] = $Login_Result["ID"];

                        //Update the DB for the date of the login.
                        $Last_Login_Array = array(':Account_Last_Login'=>date(self::DATE_FORMAT),':ID'=>$_SESSION['ID']);
                        $this->Connection->Custom_Execute("UPDATE users SET Account_Last_Login=:Account_Last_Login WHERE ID=:ID",$Last_Login_Array);
                        Write_Log("users", "ACCOUNT: Successfull login attempt for account [$Username] and password [$Password]");

                        // Display success toast.
                        Toasts::addNewToast("You have logged in as " . $_SESSION['Name'], "success");
                        $User = new User($_SESSION["ID"]);
                        $User->Add_Achievement("Logged In");

                        // Redirect user to homepage.
                        header( 'Location: /');
                        exit();
                    } else {
                        Toasts::addNewToast("This account is locked and may not log in.", "error");
                        Write_Log("users", "ACCOUNT: Locked login attempt for account [$Username] and password [$Password]");
                    }
            } else {
                Toasts::addNewToast("Username and Password combination is incorrect.", "error");
                Write_Log("users", "ACCOUNT: Failed login attempt for account [$Username] and password [$Password]");
            }
    } else {
        Toasts::addNewToast("Please fill out all required fields.", "error");
        Write_Log("users", "ACCOUNT: Not all login fields given.");
    }
}

function Register($User,$Pass,$Mail,$Permissions='4')
{
    $Username = Functions::Make_Safe($User);
    $Password = Functions::Make_Safe($Pass);
    $EMail = Functions::Make_Safe($Mail);
    $MD5Password = md5($Password);

    // Check if parameters are blank
    if(isset($Username) && isset($Password) && isset($EMail)) {

         if (!preg_match('/[^a-z_\-0-9]/i', $Username)) {

            if (filter_var($EMail, FILTER_VALIDATE_EMAIL)) {

                //Populate result sets to check if there is already users with these credentials in the db.
                $Register_Username_Array = array(':Username'=>$Username);
                $Register_Username_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE Username=:Username LIMIT 1", $Register_Username_Array);

                $Register_EMail_Array = array(':EMail'=>$EMail);
                $Register_EMail_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE EMail=:EMail LIMIT 1", $Register_EMail_Array);

                // Check if the username is already registered.
                if(empty($Register_Username_Result)) {

                    // Check is the email is already registerd.
                    if(empty($Register_EMail_Result)) {

                        $Now = date(self::DATE_FORMAT);

                        // Write query to insert registration data into db.
                        $Registration_Insert_Array = array(':Username'=>$Username,':Password'=>$MD5Password, ':EMail'=>$EMail,':Permissions'=>$Permissions,':Account_Created'=>$Now,':Account_Locked'=>0);
                        $this->Connection->Custom_Execute("INSERT INTO users (Username,Password,EMail,Permissions,Account_Created,Account_Locked) VALUES (:Username, :Password, :EMail, :Permissions, :Account_Created, :Account_Locked)",$Registration_Insert_Array);

                        // Create a new user class and check the ID to make sure the user was added.
                        $User = new User($this->Connection->PDO_Connection->lastInsertId());
                        if (isset($User->ID)) {
                            //Insert new default data into the users_settings table for the userid;
                            $Registration_Insert_UserSettings_Array = array(':UserID'=>$User->ID);
                            $this->Connection->Custom_Execute("INSERT INTO users_settings (UserID) VALUES (:UserID)",$Registration_Insert_UserSettings_Array);
                        }

                        // Check to see if insert worked.
                        if($this->Connection->PDO_Connection->lastInsertId()!='') {

                            // Success
                            Write_Log("users", "ACCOUNT: Successfull register attempt for account [$Username] and email [$EMail]");
                            // Login the user sending the unencrypted password since login re-encrypts it.
                            self::Login($Username,$Password);

                        } else {
                            Toasts::addNewToast("There was a problem creating this account. Please try again.", "error");
                            Write_Log("users", "ACCOUNT: Unknown error, couldn't register user to database.");
                        }
                     } else {
                        Toasts::addNewToast("That email has been taken. Please select a new one.", "error");
                        Write_Log("users", "ACCOUNT: Failed register attempt for account [$Username], email [$EMail] already exists.");
                    }
                } else {
                    Toasts::addNewToast("That username has been taken. Please select a new one.", "error");
                    Write_Log("users", "ACCOUNT: Failed register attempt for account [$Username] username already exists.");
                }
            } else {
                Toasts::addNewToast("Please select a valid email.", "error");
                Write_Log("users", "ACCOUNT: Non valid email given.");
            }
        } else {
            Toasts::addNewToast("Please select a valid alphanumeric username.", "error");
            Write_Log("users", "ACCOUNT: Non alphanumeric username given.");
        }
    } else {
        Toasts::addNewToast("Please fill out all required fields.", "error");
        Write_Log("users", "ACCOUNT: Not all register fields given.");
    }
}

function Logout()
{
    $_SESSION = array();
    session_destroy();

    header( 'Location: /') ;
    exit();
}

} // END CLASS
