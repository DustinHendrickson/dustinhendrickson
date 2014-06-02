<?php

function convertClientName($clientName){
	switch($clientName){
		case "W2BN":
			return "Warcraft 2: BNET";
		case "WAR3":
			return "Warcraft 3:Reign of Chaos";
		case "W3XP":
			return "Warcraft 3: Frozen Throne";
		case "SEXP":
			return "Starcraft: Broodwar";
		case "STAR":
			return "Starcraft";
		case "D2DV":
			return "Diablo II";
		case "D2XP":
			return "Diablo II: Lord of Destruction";
		case "DRT1":
			return "Diablo";
		default:
			return $clientName;
	}
}

$server_status = file_get_contents('/usr/local/var/status/server.dat');
$rows = explode("\n", $server_status);

echo "<b>Battle.net Server Status</b><br/>";
$ServerStatus=Functions::getServerStatus("bnetd");
Functions::displayServerStatus($ServerStatus);
echo "<hr>";

foreach($rows as $row => $data)
{
	//get row data
	$row_data = explode('=', $data);

	$info[$row]['value'] = $row_data[1];

	$rowName = '';
	//display data
	switch($row_data[0]){
		case 'Version':
			$rowName = "Server Version";
			break;
		case 'Uptime':
			$rowName = "Server Uptime";
			break;
		case 'Games':
			$rowName = "# of Games Up";
			break;
		case 'Users':
			$rowName = "# of Users On";
			break;
		case 'UserAccounts':
			$rowName = "Total Users Registered";
			break;
	}

	if ($row_data[0] == 'user1'){
		echo "<br/><b>Users Online</b><br/><hr>";
	}

	if (substr($row_data[0], 0, -1) == 'user') {
		$loggedinusers = explode(",", $row_data[1]);
		$userstring = $userstring . "," . $loggedinusers[2];
		echo "<b>" . $loggedinusers[1] . " is in game " . convertClientName($loggedinusers[0]) . "</b><br/>";
	}

	if ($row_data[0] == 'game1'){
		echo "<br/><b>Games Being Played</b><br/><hr>";
	}

	if (substr($row_data[0], 0, -1) == 'game') {
		$games = explode(",", $row_data[1]);
		echo convertClientName($games[0]) . " => " . $games[1] . "<br/>";
	}


	if ($rowName != '') {
		echo $rowName . ' = ' . $info[$row]['value'] . '<br />';
	}
}
echo "<br/>";

#$userFiles = scandir('/usr/local/var/users/');
$userFiles = explode(",", $userstring);

//We loop through each file and echo it's link.
foreach($userFiles as $user) {
    if ($user != '.' && $user != '..') {
        echo '<b>' . $user . '</b>';
        echo '<br />';

		$txt_file    = file_get_contents('/usr/local/var/users/' . $user);
		$rows        = explode("\n", $txt_file);

		foreach($rows as $row => $data)
		{
		    //get row data
		    $row_data = explode('"', $data);

		    $info[$row]['value']	= $row_data[3];

		    $rowName = '';
		    //display data
		    switch(stripslashes($row_data[1])){
		    	case 'profile\description':
		    		$rowName = "Profile Description";
		    		break;
		    	case 'profile\location':
		    		$rowName = "Profile Website";
		    		break;
		    	case 'BNET\acct\email':
		    		$rowName = "Email";
		    		break;
		    	case 'BNET\acct\lastlogin_ip':
		    		$rowName = "Last Login IP";
		    		break;
		    	case 'BNET\acct\ctime':
		    		$rowName = "Account Creation Time";
		    		$info[$row]['value'] = date('h:iA m-d-Y', $info[$row]['value']);
		    		break;
		    	case 'BNET\acct\userid':
		    		$rowName = "UserID";
		    		break;
		    	case 'BNET\acct\username':
		    		$rowName = "UserName";
		    		break;
		    	case 'Record\SEXP\0\last game result':
		    		$rowName = "Last Game Result";
		    		break;
		    	case 'Record\SEXP\0\draws':
		    		$rowName = "Number of Draws";
		    		break;
		    	case 'BNET\acct\lastlogin_clienttag':
		    		$rowName = "Last Game Logged Into";
		    		break;
		    }

		    if ($rowName != '') {
				echo $rowName . ' = ' . $info[$row]['value'] . '<br />';
		    }
		}
		echo '<br />';
    }
}
