<?php

class Authentication
{
public $Connection;
public $Error_Message;
const DATE_FORMAT = 'Y-m-d H:i:s';

function __construct(){
	$this->Connection = new Connection();
}

function Login($User,$Pass) {
	$Username = Functions::Make_Safe($User);
	$Password = Functions::Make_Safe($Pass);
	$Password = md5($Password);
	
	//Check if parameters are blank
	if($Username!='' && $Password!='') {

		//Query to get the credentials of the user logging in.
		$Login_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE Username = '$Username' AND Password = '$Password'");

		//Check and see if there were any matches in the result.
		if (mysql_numrows($Login_Result) >= 1) {

			while ($Login_Row = mysql_fetch_assoc($Login_Result)) {
				
				//Check to see if the account is locked.
				if($Login_Row['Account_Locked'] == 0){

					//Login is successfully, now we set up our session variables.
					$_SESSION["Name"] = $Login_Row["Username"];
					$_SESSION["ID"] = $Login_Row["ID"];
					//Update the DB for the date of the login.
					$this->Connection->Custom_Query("UPDATE users SET Account_Last_Login='". date(self::DATE_FORMAT)."' WHERE ID='".$_SESSION['ID']."'");
					Write_Log("php", "ACCOUNT: Successfull login attempt for account [$Username] and password [$Password]");
					header( 'Location: ?' ) ;
				} else {
					$this->Error_Message = "This account is locked and may not log in.";
					Write_Log("php", "ACCOUNT: Locked login attempt for account [$Username] and password [$Password]");
				}
			}
		} else {
			$this->Error_Message = "Username and Password combination is incorrect.";
			Write_Log("php", "ACCOUNT: Failed login attempt for account [$Username] and password [$Password]");
		}
	} else {
		$this->Error_Message = "Please fill out all required fields.";
		Write_Log("php", "ACCOUNT: Not all login fields given.");
	}
}

function Register($User,$Pass,$Mail,$Permissions='4') {
	$Username = Functions::Make_Safe($User);
	$Password = Functions::Make_Safe($Pass);
	$EMail = Functions::Make_Safe($Mail);
	$MD5Password = md5($Password);

	//Check if parameters are blank
	if($Username!='' && $Password!='' && $EMail!='') {

		//Populate result sets to check if there is already users with these credentials in the db.
		$Register_Username_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE Username = '$Username'");
		$Register_Email_Result = $this->Connection->Custom_Query("SELECT * FROM users WHERE EMail = '$EMail'");

		//Check if the username is already registered.
		if(mysql_numrows($Register_Username_Result) < 1) {

			//Check is the email is already registerd.
			if(mysql_numrows($Register_Email_Result) < 1) {

				$Now = date(self::DATE_FORMAT);

				//Write query to insert registration data into db.
				$Register_Insert_Result = $this->Connection->Custom_Query("INSERT INTO users (Username,Password,EMail,Permissions,Account_Created,Account_Locked) VALUES ('$Username','$MD5Password', '$EMail', '$Permissions','$Now','0')");
				
				//Check to see if insert worked.
				if(mysql_insert_id()!='') {

					//Success
					Write_Log("php", "ACCOUNT: Successfull register attempt for account [$Username] and email [$EMail]");
					//Login the user sending the unencrypted password.
					self::Login($Username,$Password);

				} else {
					$this->Error_Message = "There was a problem creating this account. Please try again.";
					Write_Log("php", "ACCOUNT: Unknown error, couldn't add to database.");
				}
			 } else {
				$this->Error_Message = "That email has been taken. Please select a new one.";
				Write_Log("php", "ACCOUNT: Failed register attempt for account [$Username], email [$EMail] already exists.");
			}
		} else {
			$this->Error_Message = "That username has been taken. Please select a new one.";
			Write_Log("php", "ACCOUNT: Failed register attempt for account [$Username] username already exists.");
		}

	} else {
		$this->Error_Message = "Please fill out all required fields.";
		Write_Log("php", "ACCOUNT: Not all register fields given.");
	}
}


function Logout() {
	$_SESSION = array();
	session_destroy();
	header( 'Location: ?' );
}


}
?>
