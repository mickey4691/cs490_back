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

$table_name="question";

if ($result = $conn->query("SHOW TABLES LIKE '".$table_name."'")) 
{
    if($result->num_rows == 1) 
    {
        //echo "Table exists";
    }
    else //create table
    {
        $sql = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT, 
        question_text VARCHAR(65535) NOT NULL, 
        func_name VARCHAR(255) NOT NULL, 
        param_names VARCHAR(255) NOT NULL,
        PRIMARY KEY ( primary_key )
        )";

        if ($conn->query($sql) === TRUE) 
        {
            echo "Table \"" . $table_name . "\" created successfully<br/>";
        }
        else
        {
            echo mysqli_error($conn);
        }
    }
}

$conn->close(); 

?>