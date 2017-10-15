<?php 
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

$jsonData=$_POST["json_string"];

$jsonArray = json_decode($jsonData, true);

$table_field_array=$jsonArray["fields"];

$actionPost=$jsonArray["action"];

$tablename=$jsonArray["table_name"];

switch ($actionPost) {
    case "insert":
    	require('auxillary_functions/insert_back.php');
        //add function goes here for checking the data sent"

    	//call function
    	insert_sql($tablename, $table_field_array);
    
        break;
    case "edit":
        //edit function goes here"
        break;
    case "delete":
        //delete function goes here"
        break;
    case "list":
        //list function goes here"
        break;
    default:
    	$response = array(
        "action" => "unknown",
        "status" => "error",
        "user_message" => "Error",
        "internal_message" => "action is not within range."
    	);
    http_response_code(400);
    header('Content-Type: application/json');
    exit(json_encode($response));
}


?>