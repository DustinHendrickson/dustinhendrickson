<?php

class Connection
{
    public $PDO_Connection;
    public $Config;

    function __construct()
    {
        //Read in Config file for use in the connection.
        $this->Config = parse_ini_file($GLOBALS['Path'] . "DBConfig.ini");

        try {
        //Define the DATABASE CONNECTION using PDO, also set attributes needed.
            $this->PDO_Connection = new PDO('mysql:host='.$this->Config[HOST].';dbname='.$this->Config[DATABASE].';charset=utf8', $this->Config[USER], $this->Config[PASSWORD]);
            $this->PDO_Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->PDO_Connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        //PDO Error Catching
        } catch(PDOException $exception) {
            Write_Log("sql", "[ Error #" . $exception->getCode() . " ] Line #" . $exception->getLine() . " on " . $exception->getFile() . " >> " . $exception->getMessage());
        }
    }


    function Custom_Query($query_string,$query_array,$ALL=false)
    {

        try {
            $PDO_Prepped = $this->PDO_Connection->prepare($query_string);
            $PDO_Prepped->execute($query_array);
            if ($ALL == true){
                $PDO_Results = $PDO_Prepped->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $PDO_Results = $PDO_Prepped->fetch(PDO::FETCH_ASSOC);
            }

        } catch(PDOException $exception) {
            Write_Log("sql", "Line #" . $exception->getLine() . " on " . $exception->getFile() . " >> " . $exception->getMessage());
        }

        return $PDO_Results;
    }


    function Custom_Count_Query($query_string,$query_array)
    {

        try {
            $PDO_Prepped = $this->PDO_Connection->prepare($query_string);
            $PDO_Prepped->execute($query_array);

            $PDO_Results = $PDO_Prepped->fetch(PDO::FETCH_NUM);

        } catch(PDOException $exception) {
            Write_Log("sql", "Line #" . $exception->getLine() . " on " . $exception->getFile() . " >> " . $exception->getMessage());
        }

        return $PDO_Results;
    }


    function Custom_Execute($query_string,$query_array)
    {
        try {
            $PDO_Prepped = $this->PDO_Connection->prepare($query_string);
            $Results = $PDO_Prepped->execute($query_array);

        } catch(PDOException $exception) {
            Write_Log("sql", "Line #" . $exception->getLine() . " on " . $exception->getFile() . " >> " . $exception->getMessage());
        }

        return $Results;
    }

}//END OF CLASS
