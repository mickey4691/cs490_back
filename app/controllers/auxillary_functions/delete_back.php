<?php 

/*
**Author: Mickey P. Somra
**Last Upated: 10/23/2017
**Purpose: This php script is meant to query the database 
and delete an existing row based on the primary_key passed. 
The delete function in this script is generic and will work 
on any table provided the proper data.
*/

//Error Message for debugging
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

function delete_sql($tablename, $primary_key)
{
	//Reading sensitive SQL credentials from another script.
	$configs = require('../config.php'); 

	$servername =   $configs['servername'];
	$dbusername =   $configs['dbusername'];
	$password   =   $configs['password'];
	$dbname     =   $configs['dbname'];
	// Creates a connection from the sql server to login and access a specific database
	$conn = new mysqli($servername, $dbusername, $password, $dbname);

	// Check connection
	if ($conn->connect_error) 
	{
		//If there is a connection error, the script will 
		//terminate and return the error message from the database
		die("Connection failed: " . $conn->connect_error);
	} 

	//Generating a generic list query.
	$sql_query="Select * from $tablename where primary_key=$primary_key;";

	$result = $conn->query($sql_query);
	//First we want to ensure that the primary_key exist in the specified table.	
	if ($result->num_rows > 0) 
	{
		//Once the primary_key exist, then we can proceeed to query the 
		//database and delete that row.
		$sql_query="Delete from $tablename where primary_key=$primary_key;";
		$conn->query($sql_query);
		//Generating a response for a successful delete query
		$response = array
		(
    		"action" => "delete",
    		"status" => "success" 
    	);
    	http_response_code(200);//Good request
    	/*The function header("Content-type:application/json") sends the http json
    	header to the browser to inform the receiver on what the kind of data to expect.*/
    	header('Content-Type: application/json');
    	
	}
	else
	{
		//error case where the primary_key does not exist
		$response = array
		(
    		"action" => "delete",
    		"status" => "error" ,
    		"user_message" => "Record to be deleted not found",
    		"internal_message" => "delete_back.php Record for primary_key=" 
    				.$primary_key. " does not exist in ".$tablename. " table. 
    				If there's an sql error, it will follow the three dots ... "
    				. mysqli_error($conn)
    	);
    	http_response_code(400); //Bad request
    	header('Content-Type: application/json');
	}
	
	$conn->close();//Closing the db connection
	//Generating a JSON message to notify the user of the message.	
	exit(json_encode($response));
	
}

?>