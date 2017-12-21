<?php
// src/AppBundle/DependencyInjection/BCHelper.php
/* Custom BC Helper File for symfony applications. Just functinos that need to be packaged with every BC Symfony app for it to work.
 */
namespace AppBundle\DependencyInjection;

class BCHelper
{
    static public function test_class()
    {
        echo "Testing access to this area or function!";
    }

    /* Main Page Control Panel and Top Right Links: */
    static public function display_audience_nav_links()
    {
        echo "<ul>";
            echo "<li class='item-125 audience'><a class='fa fa-lock-right' href='https://mybc.bridgewater.edu/' target='_blank' >MyBC</a></li>";
        echo "</ul>";
    }

    /* Mobile Navigationl Header */
    static public function display_mobile_nav_links()
    {
        echo "<ul class='actions'>";
            echo "<li><a href='javascript:void(0)' class='menu-btn fa fa-bars' title='Open the menu'>Menu</a></li>";
        echo "</ul>";
        echo "<ul class='nav' style='border-bottom: none;'>";
                echo "<li class='item-1' ><a href='' >Place Holder</a></li>";
                echo "<li class='item-125 audience'><a class='fa fa-lock-right' href='https://mybc.bridgewater.edu/' target='_blank' >MyBC</a></li>";
        echo "</ul>";
    }
    /* Main Navigational Headers: */
    static public function display_main_nav_links($currentPath, $permissions)
    {	
	//Link Arrays:
	$linkArray = array();
	
        echo "<ul>";
            echo "<li id='Home' class='item-1'><a href='' >Place Holder</a></li>";
        echo "</ul>";
    }

    // Entity Builder Utility Functions (removed in production environment)
    //----------------------------------------------------------------------------------------------------------------------------------------------------
    public function convert_db_key_to_setter($key)
    {
        /**
         * Description: this function transforms return queried field names from mysql into my class setter function names. This makes
         * re-inputting saved data into a form easy.
         * Called from withing Form Entities refillData() function
         */
        $tempString = explode("_",$key);
        $tempKey = "set";
        $variableName = "";
        $command = "";
        foreach($tempString as $string) {
            $tempKey.= ucfirst($string);
        }

        //create the variable name:
        foreach($tempString as $key=>$string)
        {
            if ($key == 0)
                $variableName = $tempString[0];
            else
                $variableName.=ucfirst($string);
        }

        //create the central code:
        $command = "$"."this->".$variableName." = $".$variableName.";";
        $key = "public function ".$tempKey."($".$variableName.")</br>{</br>$command</br>}";

        return $key;
    }
    public function convert_db_key_to_getter($key)
    {
        $tempString = explode("_",$key);
        $tempKey = "get";
        $variableName = "";
        $command = "";

        foreach($tempString as $string) {
            $tempKey.= ucfirst($string);
        }
        //create the variable name:
        foreach($tempString as $key=>$string)
        {
            if ($key == 0)
                $variableName = $tempString[0];
            else
                $variableName.=ucfirst($string);
        }

        $command = "return $"."this->".$variableName.";";
        $key = "public function ".$tempKey."()</br>{</br>$command</br>}";
        return $key;
    }

    public function getterAndSetterPrint($data)
    {
        /**
         * Description: this is a development utility function that allows me to quickly build getters and setters for class objects based on database object fields.
         */
        foreach($data as $row)
        {
            foreach ($row as $k=>$v)
            {
                $functionName = $this->convert_db_key_to_setter($k);
                $functionName2 = $this->convert_db_key_to_getter($k);
                echo $functionName."</br>";
                echo $functionName2."</br>";
            }
        }

        exit();
    }

    // LAMBDA Function Caller:
    //----------------------------------------------------------------------------------------------------------------------------------------------------
    public function convert_db_key_to_camel($key)
    {
        /**
         * Description: this function transforms return queried field names from mysql into my class setter function names. This makes
         * re-inputting saved data into a form easy.
         * Called from withing Form Entities refillData() function
         */
        $tempString = explode("_",$key);
        $tempKey = "set";
        foreach($tempString as $string) {
            $tempKey.= ucfirst($string);
        }
        $key = $tempKey;
        return $key;
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

    // Utility Functions:
    //-----------------------------------------------------------------------------------------------------------------------
    /** 
     * Get Url Function 
     * Description: returns the current url, depending on the format asked to be passed back. 
     */
    function getUrl($type)
    {   
        /* Description: returns the current url to the user for usage and variable checking...
         * Examples: 
         *      getUrl('current');
         */
        if($type == 'current')
        {
            $currentPage = 'http';
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
                $currentPage .= "s";
            }
            $currentPage .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                        $currentPage .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            } else {
                        $currentPage .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }

            return $currentPage;
        }
        else if($type == 'base')        //this returns the base url for the webpage
        {
            $baseUrl = 'htpp';
            if ($_SERVER["HTTPS"] == "on") {
                $baseUrl .= "s";
            }
            $baseUrl .= "://";

            if ($_SERVER["SERVER_PORT"] != "80") {
                $baseUrl .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
            } else {
                $baseUrl .= $_SERVER["SERVER_NAME"].$_SESSION['base_url'];
            }

            return $baseUrl;
        }
        else if($type == 'dir')
        {
            $baseUrl = $_SERVER["REQUEST_URI"];
            return $baseUrl;
        }
    }

    public function reloadObject($currentObject, $previousData)
    {
        if (!empty($previousData)) {
            foreach ((array)$previousData as $k => $v) {
                $functionName = $this->convert_db_key_to_camel($k);
                $currentObject->$functionName($v);
            }
        }
    }

    public function setValuesMap($formData, $dataModel)
    {
        /**
         * Description: this creates the "valuesMap" array that's going to be passed into the database controller and used to persist the formData passed into the
         * database. It checks to see if each setter has a corresponding value in the formData, if so it will add the value in the formData field to the array.
         */
        $valuesMap = array();
        $_classMethods = get_class_methods($dataModel);

        foreach ($_classMethods as $methodName) {
            $tempVarName = preg_split('/(?=[A-Z])/',$methodName);
            $varName = '';

            // If it's a setter, get it's "variable" name from the method name
            if ($tempVarName[0] == 'set') {
                foreach ($tempVarName as $index => $str) {
                    if ($index != 0 && $index == 1)
                        $varName = lcfirst($str);
                    else if ($index != 0)
                        $varName .= $str;
                }
            }

            //if the varName is in the formData passed into this function, then it's going to be added to the valuesMap for the database input
            if (isset($formData[$varName])) {
                $tableField = $this->convert_camel_to_db_key($varName);
                $valuesMap[$tableField] = $formData[$varName]->getData();
            }
        } // End Main Methods Loop

        return $valuesMap;
    }
}
