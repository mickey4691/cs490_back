<?php 

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

$table_name="student";

if ($result = $conn->query("SHOW TABLES LIKE '".$table_name."'")) 
{
    if($result->num_rows == 1) 
    {
        //echo "Table exists";
    }
    else //create table
    {
        $sql = "CREATE TABLE ". $table_name ." (
        student_id INT(6) NOT NULL AUTO_INCREMENT,
        user_name VARCHAR(255) NOT NULL, 
        hash_salt VARCHAR(255) NOT NULL, 
        PRIMARY KEY ( student_id )
        )";

        if ($conn->query($sql) === TRUE) 
        {
            echo "Table \"" . $table_name . "\" created successfully<br/>";
        }
    }
}

$conn->close(); 

?>