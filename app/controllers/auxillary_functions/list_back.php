<?php 

/*
**Author: Mickey P. Somra
**Last Upated: 10/23/2017
**Purpose: This php script is meant to querying the database and list data based on the table fields and order passed. The list function in this script is generic and will work on any table provided the proper data.
*/

//Error Message for debugging
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

function list_sql($tablename, $order, $order_by, $table_field_array)
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
		//If there is a connection error, the script will terminate and return the error message from the database
 	   die("Connection failed: " . $conn->connect_error);
	} 
	
	//Generating empty string for the order_by type
	$comma_separated_filter = "";

	//creating an array with only key from the table fields that is passed. The keys will corresnpond to the table column/field.
	$keys_array = array_keys($table_field_array);

	//This loop will iterate through every key and generate a comma separated filter_by and it's corresponding comma separated value. This will allow for a generic list query.
	for($i=0; $i < count($keys_array); $i++) 
	{
		//mysqli_real_escape_string will account for the sql escape characters to be handled properly. The comma_separated_filer will hold a string with key[0]=value[0], ... , key[n-2]=key[n-2] AND key[n-1]=key[n-1] 
		$comma_separated_filter .= mysqli_real_escape_string($conn,$keys_array[$i]);
		$comma_separated_filter .= "=\"";
		$comma_separated_filter .=mysqli_real_escape_string($conn,$table_field_array[$keys_array[$i]]);
		$comma_separated_filter .= "\"";
		if($i < (count($keys_array) - 1) and (count($keys_array)>1) )
		{
			$comma_separated_filter.= " AND ";
		}
	}

	//Since the query will have an optional field, that is filter by ascending/descending order and the order type, a query will be required to account for that.
	//The query will select all data from the specified tablename
	$sql_query = "Select * from $tablename";

	//script will then check if the filter by field is empty or not
	if( $comma_separated_filter != "") 
		{
			//if it is not empty, then we apply the dilter to the query
			$sql_query .= " where $comma_separated_filter"; 
		}
	//script will also check if the order by field is empty or not
	if( $order_by != "")
	{
		//if it is not empty, then we apply the order to the query
		$sql_query .= " Order by $order_by $order"; 
	}

	$sql_query .= ";"; //end of sql query

	//will query the database
	$result = $conn->query($sql_query);
	//if more than 0 row(s) is returned, then we can list all the rows.
	if ($result->num_rows > 0) 
	{	
		$db_response=array();
		$i=0;	//initialize variable
			//fetch_assoc returns an associative array that corresponds to the fetched row
    		while($row = $result->fetch_assoc()) 
    		{
    			//placing each row from the selected queries to be added to an array, iterating through each
        		$db_response[$i]=$row;
        		$i++; //Increment counter for the array
    		}
    		//Generating a successful response with all the rows as an array returned from the database.    		
			$response = array
			(
    			"action" => "list",
    			"status" => "success" ,
    			"items" => $db_response
    		);
    		http_response_code(200); //Godd request
    		//The function header("Content-type:application/json") sends the http json header to the browser to inform the receiver on what kind of data to expect.
    		header('Content-Type: application/json');
	}
	else
	{
		//error case for failure to list from a specific table
		$response = array
		(
    		"action" => "list",
    		"status" => "error" ,
    		"user_message" => "Database error",
    		"internal_message" => "list_back.php Failed to list from ".$tablename. " table and sql error is " . mysqli_error($conn)
    	);
    	http_response_code(400); //Bad request
    	header('Content-Type: application/json');
	}
	
	$conn->close();//Closing the db connection
	//Generating a JSON message to notify the user of the message.	
	exit(json_encode($response));
	
}

?>