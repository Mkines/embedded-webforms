<?php
/** 
 * Tyler Weisman, Web Developer Bridgewater College
 * JARVIS Master Database Object 2.0
 *  
 * Description: this php object is used to query and construct return arrays of all different data on the BC Web Servers. This also can parse and
 *     manipulate the data before sending it back. 
 */
namespace AppBundle\DependencyInjection;

class DBController
{
   //Variable declaration
   private $pdo;

   public function __construct($database_host, $database_name, $database_user, $database_password)
   {
        //establish the connection:
        $pdo = $this->connect_dbPDO($database_host, $database_name, $database_user, $database_password);

        //the database connection variable
        $this->pdo = $pdo;
   }

   private function connect_dbPDO($database_host, $database_name, $database_user, $database_password)
   {
        $hostbase = "mysql:host=$database_host;dbname=$database_name";
        try{
            $pdo = new \PDO("$hostbase", "$database_user", "$database_password");
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            //added to potentially fix the issue with super large dataset queries in PDO
            //$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

            return $pdo;
        } catch (\PDOException $error) {
            /* Tyler: removed until I figure out a better way to display error logs with symfony */
            //$this->log_mysqlError("PDO Connection Creation Failed for $titlename form - ".$error->getMessage()."\r\n");
        }
   }

   //SELECT Functions:
   //-----------------------------------------------------------------------------------------------------------------------
   public function select_db_row($vars, $sql)
   {
        /** 
         * Description: basically this just does a simple query of the table and returns a PDO object to the main script.
         */
        try{
            $db = $this->pdo->prepare($sql);
            foreach($vars as $key=>$item) {
                $db->bindValue(":".$key, $item);
            }

            $db->execute();
            //check that the select returned at least one row of data. If not return false.
            if($db->rowCount() > 0)
                return $db->fetchAll(\PDO::FETCH_ASSOC);
            else
                return false;

        } catch(\PDOException $error) {
            //Logging needs to be logged in...
            return false;
        }
   }

   //INSERT, UPDATE, DELETE Functions:
   //-----------------------------------------------------------------------------------------------------------------------
   public function manipulate_db_row($vars, $sql)
   {
        /**
         * Description: this function does all the basic on off queries for INSERT or UPDATE in the db.
         * Returns - just true or false if the query completed, this is for very basic queries.
         */
        try{
            $db = $this->pdo->prepare($sql);
            foreach ($vars as $key=>$item)   //bind variables to the sql string
                $db->bindValue(":".$key, $item);

            $db->execute();

            return true;
        } catch(\PDOException $error) {

            return false;
        }
   }
   public function manipulate_db_row_return_insert_id($vars, $sql)
   {
        try{
            $db = $this->pdo->prepare($sql);
            foreach ($vars as $key=>$item)   //bind variables to the sql string
                $db->bindValue(":".$key, $item);

            $db->execute();
            $insertID = $this->pdo->lastInsertId();

            return $insertID;
        } catch(\PDOException $error) {

            return false;
        }
   }

    // Symfony Utilities Version 2.0:
    // Description: the following functions are actually utilizing the class data setters and getters to persist the data into the database rather then re-mapping it every time.
    // This should save steps in the entire process and making adding new fields as simple as adding them into the query itself.
    public function persist_class_data($dataModel, $sql, $returnIdFlag)
    {
        $_classMethods = get_class_methods($dataModel);
        //print_r($_classMethods);

        $db = $this->pdo->prepare($sql);

        foreach ($_classMethods as $methodName) {
            $tempVarName = preg_split('/(?=[A-Z])/',$methodName);

            if ($tempVarName[0] == 'get') {
                $var_name = $this->convert_camel_to_db_key($methodName);
                $var_name = explode('get_', $var_name);
                $var_name = $var_name[1];

                try {
                    $db->bindValue(":".$var_name, $dataModel->$methodName());
                } catch (\PDOException $error) {
                    // Skips the "parameter" if it's not in the actual sql query to bind too.
                }

            }
        }

        $db->execute();
        // If $returnIdFlag = true then return the lastId inserted, otherwise just return true
        if ($returnIdFlag == true)
        {
            $insertID = $this->pdo->lastInsertId();
            return $insertID;
        } else {
            return true;
        }
    }

    public function convert_camel_to_db_key($key)
    {
        /**
         * Description: this goes the other way for database arrays and converts field names into database fields with _ as space delimiters
         */
        $tempString = preg_split('/(?=[A-Z])/',$key);
        $tempKey = '';
        $counter = 0;
        foreach($tempString as $string)
        {
            if ($counter == 0)
                $tempKey .= $string;
            else
                $tempKey .= "_".lcfirst($string);

            $counter++;
        }

        return $tempKey;
    }

   public function build_array_from_ch_query($queryData, $textField, $chField)
   {
        /**
         * Description: this function converts queried data for a checkbox array, and makes it useable for the symfony form. (It comes out as a pdo object which I can't do much with.
         */
        $tempArray = array();
        while ($queryData != false && $row = $queryData->fetch(\PDO::FETCH_ASSOC)) {
            $tempArray[$row[$textField]] = $row[$chField];
        }
   
        return $tempArray;
   }
}
