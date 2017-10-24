<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


$configs = include('config.php'); 

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

$table_name="mickey_table";


if ($result = $conn->query("SHOW TABLES LIKE '".$table_name."'")) 
{
    if($result->num_rows == 1) 
    {
        echo "Table exists";
    }
    else //create table
    {
        $sql = "CREATE TABLE ". $table_name ." (
        uuid INT(6) NOT NULL AUTO_INCREMENT, 
        user_name VARCHAR(255) UNIQUE,
        question_text VARCHAR(65535) NOT NULL,
        answer_text VARCHAR(65535) NOT NULL, 
        PRIMARY KEY ( uuid )
        )";

        if ($conn->query($sql) === TRUE) 
        {
            echo "Table \"" . $table_name . "\" created successfully<br>";
        }
    }
}

$conn->close(); 


?>