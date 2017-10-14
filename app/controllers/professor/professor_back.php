<?php 
/*
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

$configs = include('../config.php'); 

$servername	=	$configs['servername'];
$dbusername	=	$configs['dbusername'];
$password	=	$configs['password'];
$dbname     =   $configs['dbname'];

$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} 

$actionPost = $_POST["action"];
$usernamePost = $_POST["user_name"];

if ($actionPost != "login")
{
    //error case
    $response = array(
        "action" => "login",
        "status" => "error",
        "user_message" => "Error",
        "internal_message" => "action is not login."
    );
    exit(json_encode($response));
}

$sql = "(SELECT user_name, hash_salt FROM professor where user_name='$usernamePost')";
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
    exit(json_encode($response)); 
}

else
{
    //error case
    $response = array(
        "action" => "login",
        "status" => "error",
        "user_message" => "Error",
        "internal_message" => "Username/password not found in database."
    );
    exit(json_encode($response));
}

$conn->close(); 

?>