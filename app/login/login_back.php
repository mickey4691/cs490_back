<?php 
/*
**Author: Mickey P. Somra
**Last Upated: 09/24/2017
**Purpose: This php script receives a $_POST['username'] request from the middle and queries the SQL Database. If a username match was found, this script will return the hashed password for that user in string format. The string is returned to the middle as an echo.
Note: Passwords are not stored as plaintext for privacy purposes.
*/

//include reads the sensitive SQL Login data stored in another script and assigns it to configs via array.
$configs = include('config.php'); 

/* SQL Login Information read from another file*/
$servername	=	$configs['servername'];
$dbusername	=	$configs['dbusername'];
$password	=	$configs['password'];
$dbname     =   $configs['dbname'];

// Creates a connection from the sql server to login and access a specific database
$conn = new mysqli($servername, $dbusername, $password, $dbname);
// Check connection
if ($conn->connect_error) //If there is a connection error, the script will terminate
{
    die("Connection failed: " . $conn->connect_error);
} 

//$_POST["username"] is passed from https://web.njit.edu/~mga25/cs_490/app/login/login_middle.php
$usernamePost = $_POST["username"];

//Checking/querying only passed username from the database. The use of single and double quotes is sensitive when querying.
$sql = "(SELECT username, hashed_password FROM user_table where username='$usernamePost')";

//Result will hold the query from the connection.
$result = $conn->query($sql);

if ($result->num_rows > 0) //this will ensure that there is a match from the database.
{
	//function fetch_assoc() returns an associative array that corresponds to the fetched row or NULL if there are no more rows
    while($row = $result->fetch_assoc()) 
    {
        $hashedPassword=$row["hashed_password"];
    	break;
    }
}

/*
Echo will return the hashed password of the username sent with Post from the database;
If there is no username matching in the the database, an empty string will return to the middle.
Echo is sent to https://web.njit.edu/~mga25/cs_490/app/login/login_middle.php
*/
echo "{ \"hashed_password\": \"$hashedPassword\" }"; 

//Closing the database connection
$conn->close(); 

?>