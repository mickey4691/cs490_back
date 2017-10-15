<?php 
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function edit_sql($tablename, $primary_key, $table_field_array)
{
	
	$configs = include('../config.php'); 

	$servername =   $configs['servername'];
	$dbusername =   $configs['dbusername'];
	$password   =   $configs['password'];
	$dbname     =   $configs['dbname'];

	$conn = new mysqli($servername, $dbusername, $password, $dbname);

	// Check connection
	if ($conn->connect_error) 
	{
 	   die("Connection failed: " . $conn->connect_error);
	} 

	/*
	$comma_separated_keys = "";
	$comma_separated_values = "";
	*/

	$comma_separated_key_val = "";

	$keys_array = array_keys($table_field_array);

	for($i=0; $i < count($keys_array); $i++) 
	{
		$comma_separated_key_val .= $keys_array[$i];
		$comma_separated_key_val .= "=\"";
		$comma_separated_key_val .=$table_field_array[$keys_array[$i]];
		$comma_separated_key_val .= "\", ";
	}

	$comma_separated_key_val=trim($comma_separated_key_val,", ");
	

	$sql_query = "Update " .$tablename. " Set $comma_separated_key_val where primary_key=$primary_key;";

	if (mysqli_query($conn, $sql_query)) 
	{
		
		$sql_query = "select * from  $tablename  where primary_key=$primary_key;";
		
		$result = $conn->query($sql_query);

		if ($result->num_rows > 0) 
		{	
    		while($row = $result->fetch_assoc()) 
    		{
        		$db_response=$row;
    		}
    		//successful login
    		$items = array
    		(
         		$db_response
    		);

			$response = array
			(
    			"action" => "edit",
    			"status" => "success" ,
    			"items" => $items
    		);
    		http_response_code(200);
    		header('Content-Type: application/json');
    	}
	}
	else
	{
		$response = array
		(
    		"action" => "edit",
    		"status" => "error" ,
    		"user_message" => "Database error",
    		"internal_message" => "Failed to edit from ".$tablename. " table"
    	);
    	http_response_code(400);
    	header('Content-Type: application/json');
	}
	
	$conn->close();
	exit(json_encode($response));
	
	
}


?>