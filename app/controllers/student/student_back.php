<?php 

/*
**Author: Mickey P. Somra
**Last Upated: 10/23/2017
**Purpose: This php script is meant to return a hashed password for a specific
student via the user_name. The hashed password is stored in the NJIT's SQL
database under the student table. This script will receive two post requests. 
One that is an action which should be login and the other is the student user_name. 
The script will then query the database for the student user_name and 
return the primary key, username and hashed password. 
*/

//Error Message for debugging
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

//Reading sensitive SQL credentials from another script.
$configs = require('../config.php'); 

$servername =   $configs['servername'];
$dbusername =   $configs['dbusername'];
$password   =   $configs['password'];
$dbname     =   $configs['dbname'];

//Creates a connection from the sql server to login and access a specific database
$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) 
{
     //If there is a connection error, the script will 
    //terminate and return the error message from the database
    die("Connection failed: " . $conn->connect_error);
} 

//Extracting the post requests
$actionPost = $_POST["action"];
$usernamePost = $_POST["user_name"];

//Verifying the action request is login
if ($actionPost != "login")
{
    //Generating a response for incorrect action
    $response = array(
        "action" => "login",
        "status" => "error",
        "user_message" => "Error",
        "internal_message" => "student_back.php action is not login."
    );
    http_response_code(400); //bad request
    header('Content-Type: application/json');
    $conn->close(); //Closing the db connection
    //Since the incorrect POST was passed, an error messaged 
    //will be display in JSON format and the script will terminate.
    exit(json_encode($response));
}

//Querying the db based on the user_name passed.
$sql = "(SELECT * FROM student where user_name='$usernamePost')";
$result = $conn->query($sql);

if ($result->num_rows > 0) 
{
    while($row = $result->fetch_assoc()) 
    {
        //fetch_assoc returns an associative array that corresponds to the fetched row
        $db_response=$row;
    }
    //placing first row from the selected user_name to be added to an array.
    $items = array
    (
         $db_response
    );
    //Generating a response for a successful query from the db.
    $response = array(
        "action" => "login",
        "status" => "success",
        "items" => $items
    );  
    http_response_code(200); //Good request
    header('Content-Type: application/json');
    $conn->close(); //Closing the db connection
    //Since the db was able to return a row, a successful 
    //message will be display in JSON format with the entire row 
    //that was queried and the script will terminate.
    exit(json_encode($response));
}
else
{
    //error case
    $response = array(
        "action" => "login",
        "status" => "error",
        "user_message" => "Error",
        "internal_message" => "student_back.php Username/password not found in database."
    );
    http_response_code(401); //Bad request
    header('Content-Type: application/json');
    $conn->close(); 
    //Generating a JSON error to notify that there is no match found in the db.
    exit(json_encode($response));
}
//Will close the connection for safety precaution.
$conn->close(); 

?>