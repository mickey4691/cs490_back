<?php 
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function insert_sql($tablename, $table_field_array)
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

	
	$comma_separated_keys = "";
	$comma_separated_values = "";

	$keys_array = array_keys($table_field_array);

	for($i=0; $i < count($keys_array); $i++) 
	{
		$comma_separated_keys 	.= mysqli_real_escape_string($conn, $keys_array[$i]);
		$comma_separated_keys 	.= ", ";
		$comma_separated_values	.="\"";
		$comma_separated_values	.= mysqli_real_escape_string($conn, $table_field_array[$keys_array[$i]]);
		$comma_separated_values	.= "\", ";
	}

	$comma_separated_keys=trim($comma_separated_keys,", ");
	$comma_separated_values=trim($comma_separated_values,", ");

	$sql_query = "INSERT INTO " .$tablename. " ($comma_separated_keys) VALUES ($comma_separated_values);";

	if (mysqli_query($conn, $sql_query)) 
	{
		$last_id = mysqli_insert_id($conn);
		$sql_query = "select * from  $tablename  where primary_key=$last_id;";
		
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
    			"action" => "insert",
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
    		"action" => "insert",
    		"status" => "error" ,
    		"user_message" => "Database error",
    		"internal_message" => "insert_back.php New record failed to insert to ".$tablename. " table and sql error is " . mysqli_error($conn)
    	);
    	http_response_code(400);
    	header('Content-Type: application/json');
	}
	
	$conn->close();
	exit(json_encode($response));
	
	
}


?>