<?php 
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require('../auxillary_functions/insert_back.php');
require('../auxillary_functions/delete_back.php');
require('../auxillary_functions/edit_back.php');
require('../auxillary_functions/list_back.php');

$jsonData=$_POST["json_string"];

$jsonArray = json_decode($jsonData, true);

$table_field_array=$jsonArray["fields"];

$actionPost=$jsonArray["action"];

$tablename = $jsonArray["table_name"];

switch ($actionPost) {
    case "insert":
    	//call function
    	insert_sql($tablename, $table_field_array);    
        break;
    case "edit":
        $primary_key=$jsonArray["primary_key"];

        edit_sql($tablename, $primary_key, $table_field_array);

        break;
    case "delete":
        $primary_key=$jsonArray["primary_key"];
        
        delete_sql($tablename, $primary_key);

        break;
    case "list":
        $order="";
        $order_by="";

        if(isset($jsonArray["order_by"]))
        {
            $order=$jsonArray["order"];
            $order_by=$jsonArray["order_by"];
        }

        list_sql($tablename, $order, $order_by, $table_field_array);
        
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