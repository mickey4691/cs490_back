<?php 
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function delete_sql($tablename, $primary_key)
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

	$sql_query="Select * from $tablename where primary_key=$primary_key;";

	$result = $conn->query($sql_query);
		
	if ($result->num_rows > 0) 
	{
		$sql_query="Delete from $tablename where primary_key=$primary_key;";
		$conn->query($sql_query);
		$response = array
		(
    		"action" => "delete",
    		"status" => "success" 
    	);
    	http_response_code(200);
    	header('Content-Type: application/json');
    	
	}
	else
	{
		$response = array
		(
    		"action" => "delete",
    		"status" => "error" ,
    		"user_message" => "Record to be deleted not found",
    		"internal_message" => "delete_back.php Record for primary_key=" .$primary_key. " does not exist in ".$tablename. " table. If there's an sql error, it will follow the three dots ... " . mysqli_error($conn)
    	);
    	http_response_code(400);
    	header('Content-Type: application/json');
	}
	
	$conn->close();
	exit(json_encode($response));
	
}

?>