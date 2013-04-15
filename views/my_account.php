<?php
Functions::Verify_Session_Redirect();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$User = new User($_SESSION['ID']);

	echo "<b>Name:</b> " . $User->Get_Full_Name() . "<br />";
	echo "<b>Permissions:</b> " . $User->Get_Permissions()  . "<br />";
	echo "<b>Last Login:</b> " . date('F dS Y h:ia',$User->Account_Last_Login). "<br />";
	echo "<b>Account Creation:</b> " . date('F dS Y h:ia',$User->Account_Created) . "<br />";
	echo "<b>Account Status:</b> " .  $User->Get_Account_Status() . "<br />";
?>