<?php 
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

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


$y = $_POST["val"];

echo "Line 1 <br/>";

$x = 10;  
//$y = 6;
$z = $x * $y;
echo $z;

$conn->close(); 

?>