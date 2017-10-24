<?php 

/*
**Author: Mickey P. Somra
**Last Upated: 10/23/2017
**Purpose: This php script is meant to query the database 
and edit/update an existing value based on the table fields passed. 
The edit/update function in this script is generic and will work 
on any table provided the proper data.
*/

//Error Message for debugging
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

function edit_sql($tablename, $primary_key, $table_field_array)
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

	//Generating an empty string variable
	$comma_separated_key_val = "";

	//creating an array with only key from the table fields that is passed. 
	//The keys will corresnpond to the table column/field.
	$keys_array = array_keys($table_field_array);

	//This loop will iterate through every key and generate a comma separated 
	//key(s) and the new value(s) that is to be updated. 
	//This will allow for a generic insert query.
	for($i=0; $i < count($keys_array); $i++) 
	{
		//mysqli_real_escape_string will account for the sql escape characters 
		//to be handled properly. The comma_separated_key_val will hold 
		//a new string with key[0]=value[0], ... key[n-1]=key[n-1]
		$comma_separated_key_val .= mysqli_real_escape_string($conn,$keys_array[$i]);
		$comma_separated_key_val .= "=\"";
		$comma_separated_key_val .=mysqli_real_escape_string($conn,$table_field_array[$keys_array[$i]]);
		$comma_separated_key_val .= "\", ";
	}

	//trim(string, character) function will remove the excess characters 
	//passed as the second argument for the specific string.
	$comma_separated_key_val=trim($comma_separated_key_val,", ");
	//Generating a generic edit/update query.
	$sql_query = "Update " .$tablename. " Set $comma_separated_key_val 
				where primary_key=$primary_key;";

	//Generating a generic query.
	if (mysqli_query($conn, $sql_query)) 
	{
		//Since the primary key was passed, we will query the table 
		//based on the primary key to return the updated data from the row. 
		$sql_query = "select * from  $tablename  where primary_key=$primary_key;";
		
		$result = $conn->query($sql_query);

		if ($result->num_rows > 0) 
		{	
    		while($row = $result->fetch_assoc()) 
    		{
        		$db_response=$row;
    		}
    		//placing first row from the selected user_name to be added to an array.
    		$items = array
    		(
         		$db_response
    		);
    		//Generating a response for a successful query from the db.
			$response = array
			(
    			"action" => "edit",
    			"status" => "success" ,
    			"items" => $items
    		);
    		http_response_code(200); //Godd request
    		/*The function header("Content-type:application/json") sends the http json
    		header to the browser to inform the receiver on what the kind of data to expect.*/
    		header('Content-Type: application/json');
    	}
    	else
    	{
    		//error case for the specific primary_key
    		$response = array
			(
	    		"action" => "edit",
	    		"status" => "error" ,
	    		"user_message" => "No Record(s) found.",
	    		"internal_message" => "edit_back.php Failed to edit from 
	    				".$tablename. " table"
	    	);
	    	http_response_code(400);
	    	header('Content-Type: application/json');
    	}
	}
	else
	{
		$response = array
		(
			//Generic error case
    		"action" => "edit",
    		"status" => "error" ,
    		"user_message" => "Database error",
    		"internal_message" => "edit_back.php Failed to edit from 
    				".$tablename. " table and sql error is " . mysqli_error($conn)
    	);
    	http_response_code(400); //Bad request
    	header('Content-Type: application/json');
	}
	
	$conn->close();//Closing the db connection
	//Generating a JSON message to notify the user of the message.
	exit(json_encode($response));
}

?>