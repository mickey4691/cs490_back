<?php 
//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

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
/*
$table_name="professor";

if ($result = $conn->query("SHOW TABLES LIKE '".$table_name."'")) 
{
    if($result->num_rows == 1) 
    {
        //echo "Table exists";
    }
    else 
    {
        $sql = "CREATE TABLE " .$table_name." (
        test_id INT(6) NOT NULL AUTO_INCREMENT, 
        professor_id INT(6),
        scores_released TINYINT(1) DEFAULT 0,
        finalized TINYINT(1) DEFAULT 0,
        start_date DATETIME, 
        end_date DATETIME,
        PRIMARY KEY ( test_id )
        )";

        if ($conn->query($sql) === TRUE) 
        {
            echo "Table \"" . $table_name . "\" created successfully<br/>";
        }
    }
}

*/

/*
$sql = "CREATE TABLE test (
test_id INT(6) NOT NULL AUTO_INCREMENT, 
professor_id INT(6),
scores_released TINYINT(1) DEFAULT 0,
finalized TINYINT(1) DEFAULT 0,
start_date DATETIME, 
end_date DATETIME,
PRIMARY KEY ( test_id )
)";
*/
/*
if ($conn->query($sql) === TRUE) 
{
    echo "Table user_table created successfully<br/>";
}
*/
/*
$conn->close(); 
?>
*/
/*
else
	echo "Table exists.<br/>";
*/


$sql = "(SELECT user_name,hash_salt FROM professor)";


$result = $conn->query($sql);

if ($result->num_rows > 0) 
{
    while($row = $result->fetch_assoc()) 
    {
        $db_response=$row;
    }
    //successful login
    //echo json_encode($db_response);
    
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
        "internal_message" => "Username not found in database."
    );
    exit(json_encode($response));
}


//Returns json



$conn->close(); 

?>