<?php 


$configs = include('config.php'); //include reads the sensitive data stored in another script and assigns it to configs via array.

/* SQL Login Information*/
$servername = $configs['servername'];
$dbusername = $configs['dbusername'];
$password 	= $configs['password'];
$dbname 	= $configs['dbname'];


// Create connection
$conn = new mysqli($servername, $dbusername, $password, $dbname);
// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error . "<br/>");
} 


// sql to create table
$sql = "CREATE TABLE user_table (
uuid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(30),
hashed_password VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) 
{
    echo "Table user_table created successfully<br/>";
    //Hard coding the first username into the database. Notice the use of single vs. double
    $data = 'INSERT INTO user_table (username, hashed_password)
	VALUES ("mga25", "$2y$10$G2KQsQjOAFMuoIWh04pFR.MQo2fWYlqQ6qNlMS/3jZgl9BNHsJrLC");';

	if (mysqli_query($conn, $data)) 
	{
    	echo "New record created successfully<br/>";
	} 
	else 
	{
    	echo "Error: " . $data . "<br>" . mysqli_error($conn);
	}
}
 
else 
{
    echo "Table has been set.<br/>";
}

$conn->close(); //close database connection

?>