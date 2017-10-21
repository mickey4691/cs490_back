<?php 
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

function list_sql($tablename, $order, $order_by, $table_field_array)
{
	
	$configs = require('../config.php'); 

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
	
	$comma_separated_filter = "";

	$keys_array = array_keys($table_field_array);

	for($i=0; $i < count($keys_array); $i++) 
	{
		$comma_separated_filter .= mysqli_real_escape_string($conn,$keys_array[$i]);
		$comma_separated_filter .= "=\"";
		$comma_separated_filter .=mysqli_real_escape_string($conn,$table_field_array[$keys_array[$i]]);
		$comma_separated_filter .= "\"";
		if($i < (count($keys_array) - 1) and (count($keys_array)>1) )
		{
			$comma_separated_filter.= " AND ";
		}
	}

	//$comma_separated_filter=trim($comma_separated_filter,", ");
	
	$sql_query = "Select * from $tablename";

	if( $comma_separated_filter != "") 
		{
			$sql_query .= " where $comma_separated_filter"; 
		}
	if( $order_by != "")
	{
		$sql_query .= " Order by $order_by $order"; 
	}

	$sql_query .= ";";

	$result = $conn->query($sql_query);

	if ($result->num_rows > 0) 
	{	
		$db_response=array();
		$i=0;

    		while($row = $result->fetch_assoc()) 
    		{
        		$db_response[$i]=$row;
        		$i++;
    		}
    		//successful login
    		
			$response = array
			(
    			"action" => "list",
    			"status" => "success" ,
    			"items" => $db_response
    		);
    		http_response_code(200);
    		header('Content-Type: application/json');
	}
	else
	{
		$response = array
		(
    		"action" => "List",
    		"status" => "error" ,
    		"user_message" => "Database error",
    		"internal_message" => "list_back.php Failed to list from ".$tablename. " table and sql error is " . mysqli_error($conn)
    	);
    	http_response_code(400);
    	header('Content-Type: application/json');
	}
	
	$conn->close();
	exit(json_encode($response));
	
}

?>