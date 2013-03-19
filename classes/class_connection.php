<?php

class Connection
{
public $Connection;
public $db;
const HOST = "localhost";
const USER = "root";
const PASSWORD = "cool";
const DATABASE = "DustinDB";

function __construct(){
 
$this->Connection = mysql_connect(self::HOST,self::USER,self::PASSWORD);

if(!$this->Connection)
	{
		Write_Log("sql",'ERROR: Could not connect to the server. < ' . mysql_error());	
		die('ERROR: Could not connect to server. < ' . mysql_error());
	}
	else
	{
		Write_Log("sql",'SUCCESS: A successfull connection was made to the server.');
	}

	//Connect to Database.
	$this->db = mysql_select_db(self::DATABASE,$this->Connection);

	if(!$this->db)
		{	Write_Log("sql", "ERROR: Could not connect to database. < ". mysql_error());	}
	else
		{	Write_Log("sql", "SUCCESS: Connected to database.");	}
}

function Custom_Query($query_string)
	{
		$query_results = mysql_query($query_string, $this->Connection);

	if (!$query_results) 
	{	
		Write_Log("sql","ERROR: Could not perform selected query. < Query-> '" . $query_string . "' > " . mysql_error());
		die("ERROR: Could not perform selected query: " . mysql_error());
	}
		return $query_results;
	}

}//END OF CLASS
?>
