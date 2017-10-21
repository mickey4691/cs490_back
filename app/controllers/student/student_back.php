<?php 

/*
**Author: Mickey P. Somra
**Last Upated: 10/17/2017
**Purpose: This php script is meant to return a hashed password for a specific student. The hashed password is stored in the NJIT's SQL database under the student table. This script will receive two post requests. One that is action which should be login and the other is the student username. The script will then query the database for the student username and return the primary key, username and hashed password.
*/


//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

//Reading sensitive SQL credentials from another script.
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

//Extracting the posts requests.
$actionPost = $_POST["action"];
$usernamePost = $_POST["user_name"];

//Verifying the action request is login; if not, then the script will terminate.
if ($actionPost != "login")
{
    //error case
    $response = array(
        "action" => "login",
        "status" => "error",
        "user_message" => "Error",
        "internal_message" => "student_back.php action is not login."
    );
    http_response_code(400);
    header('Content-Type: application/json');
    $conn->close(); 
    exit(json_encode($response));
}

$sql = "(SELECT * FROM student where user_name='$usernamePost')";
$result = $conn->query($sql);

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
    
    $response = array(
        "action" => "login",
        "status" => "success",
        "items" => $items
    );  
    http_response_code(200);
    header('Content-Type: application/json');
    $conn->close(); 
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
    http_response_code(401);
    header('Content-Type: application/json');
    $conn->close(); 
    exit(json_encode($response));
}

$conn->close(); 

?>