<?php 

/*
**Author: Mickey P. Somra
**Last Upated: 10/23/2017
**Purpose: This php script is meant to querying the database in four ways. 
These are inserting, deleting, editing/updating, and listing/viewing. 
A post request in json format will be sent containing the 
action(insert/delete/edit/list), table_name and the necessary fields that needs an action. 
Based on the action received, the script will call a function 
that has it's own script to query the database accordingly.
*/

//Error Message for debugging
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

//Referencing the action scripts that are needed for the database query.
require('../auxillary_functions/insert_back.php');
require('../auxillary_functions/delete_back.php');
require('../auxillary_functions/edit_back.php');
require('../auxillary_functions/list_back.php');

//storing the value of the json passes.
$jsonData=$_POST["json_string"];

//decoding the json format as an array which will be clearer to indentify the information based on key-value pair
$jsonArray = json_decode($jsonData, true);

//This array will hold field-value for a specific table in 
//the database for insert, edit and list
$table_field_array=$jsonArray["fields"];

//extracting the action post for insert/edit/delete/list
$actionPost=$jsonArray["action"];

//extracting the tablename that will need modification/information from.
$tablename = $jsonArray["table_name"];

//This switch case will determine if the correct action is requested 
//and make the necessary function calls.
switch ($actionPost) 
{
    case "insert":
    	//call insert function from insert_back.php script
    	insert_sql($tablename, $table_field_array);    
        break;

    case "edit":
        //call edit function from edit_back.php script
        $primary_key=$jsonArray["primary_key"];
        edit_sql($tablename, $primary_key, $table_field_array);
        break;

    case "delete":
        //call delete function from delete_back.php script
        $primary_key=$jsonArray["primary_key"];        
        delete_sql($tablename, $primary_key);
        break;

    case "list":
        //call list function from list_back.php script
        $order="";
        $order_by="";
        //Will account for order and order by which may be requested optionally
        if(isset($jsonArray["order_by"]))
        {
            $order=$jsonArray["order"];
            $order_by=$jsonArray["order_by"];
        }

        list_sql($tablename, $order, $order_by, $table_field_array);
        break;

    default: //If action is not within the range, this will generate a proper response
    	$response = array(
        "action" => "unknown",
        "status" => "error",
        "user_message" => "error",
        "internal_message" => " request_parse_back.php action is not within range."
    	);
    http_response_code(400); //bad request
     /*The function header("Content-type:application/json") sends the http json
    header to the browser to inform the receiver on what the kind of data to expect.*/
    header('Content-Type: application/json');
    exit(json_encode($response)); //this will terminate the script and print a json formatted message 
}

?>