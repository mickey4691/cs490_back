<?php 

/*
**Author: Mickey P. Somra
**Last Upated: 10/23/2017
**Purpose: This php script is meant to querying the database and insert new record(s) based on the table fields passed. The insert function in this script is generic and will work on any table provided the proper data.
*/

//Error Message for debugging
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

function insert_sql($tablename, $table_field_array)
{
	//Reading sensitive SQL credentials from another script.
	$configs = include('../config.php'); 

	$servername =   $configs['servername'];
	$dbusername =   $configs['dbusername'];
	$password   =   $configs['password'];
	$dbname     =   $configs['dbname'];
	// Creates a connection from the sql server to login and access a specific database
	$conn = new mysqli($servername, $dbusername, $password, $dbname);

	// Check connection
	if ($conn->connect_error) 
	{
		//If there is a connection error, the script will terminate and return the error message from the database
 	   die("Connection failed: " . $conn->connect_error);
	} 

	//Generating empty string variables
	$comma_separated_keys = "";
	$comma_separated_values = "";

	//creating an array with only key from the table fields that is passed. The keys will corresnpond to the table column/field.
	$keys_array = array_keys($table_field_array);

	//This loop will iterate through every key and generate a comma separated keys and it's corresponding comma separated values in another string. This will allow for a generic insert query.
	for($i=0; $i < count($keys_array); $i++) 
	{
		//mysqli_real_escape_string will account for the sql escape characters to be handled properly.
		$comma_separated_keys 	.= mysqli_real_escape_string($conn, $keys_array[$i]); //appends next the key
		$comma_separated_keys 	.= ", "; //appends a comma after the key
		$comma_separated_values	.="\"";//appends quotation mark before the value
		$comma_separated_values	.= mysqli_real_escape_string($conn, $table_field_array[$keys_array[$i]]);
		$comma_separated_values	.= "\", ";//appends quotation mark after the value follwed with a comma
	}

	//trim(string, character) function will remove the excess characters passed as the second argument for the specific string.
	$comma_separated_keys=trim($comma_separated_keys,", ");
	$comma_separated_values=trim($comma_separated_values,", ");
	
	//Generating a generic insert query.
	$sql_query = "INSERT INTO " .$tablename. " ($comma_separated_keys) VALUES ($comma_separated_values);";

	//Quering the database based on the connection.
	if (mysqli_query($conn, $sql_query)) 
	{
		//If the query was succesfuly, mysqli_insert_id() function will return the last primary_key value that was added to the table. In this way, the database is queried again to return the entire row of the newly inserted items.
		$last_id = mysqli_insert_id($conn);
		$sql_query = "select * from  $tablename  where primary_key=$last_id;";
		
		$result = $conn->query($sql_query);

		if ($result->num_rows > 0) 
		{	//fetch_assoc returns an associative array that corresponds to the fetched row
    		while($row = $result->fetch_assoc()) 
    		{

        		$db_response=$row;
    		}
    		//placing first row from the selected data to be added to an array.
    		$items = array
    		(
         		$db_response
    		);
    		//Generating a response for a successful query from the db.
			$response = array
			(
    			"action" => "insert",
    			"status" => "success" ,
    			"items" => $items
    		);
    		http_response_code(200); //Godd request
    		//The function header("Content-type:application/json") sends the http json header to the browser to inform the receiver on what the kind of data to expect.
    		header('Content-Type: application/json');
    	}
	}
	else
	{	//error case
		$response = array
		(
    		"action" => "insert",
    		"status" => "error" ,
    		"user_message" => "Database error",
    		"internal_message" => "insert_back.php New record failed to insert to ".$tablename. " table and sql error is " . mysqli_error($conn)
    	);
    	http_response_code(400); //Bad request
    	header('Content-Type: application/json');
	}
	$conn->close(); //Closing the db connection
	//Generating a JSON message to notify the user of the message.
	exit(json_encode($response));
}

?>