<?php 

//This script will drop all tables.

//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

$actionPost = $_POST["action"];

if ($actionPost != "super_drop")
{
    //error case
    $response = array(
        "action" => "drop",
        "status" => "error",
        "user_message" => "Error",
        "internal_message" => "dropping_tables.php failed to run for some reason."
    );
    http_response_code(400);
    header('Content-Type: application/json');
    exit(json_encode($response));
}

$configs = include('config.php'); 

$servername	=	$configs['servername'];
$dbusername	=	$configs['dbusername'];
$password	=	$configs['password'];
$dbname     =   $configs['dbname'];

$conn = new mysqli($servername, $dbusername, $password, $dbname);

$tables=array("test_score","test_question","test_case","question_answer","test","question","student","professor");

for ($i=0; $i < count($tables); $i++)
{
    drop_table($conn, $tables[$i]);
}

function drop_table($conn, $table_name)
{
    $sql_query=("DROP TABLE $table_name");
    if ($conn->query($sql_query) === TRUE) 
    {
        $response = array(
            "db action" => "drop",
            "status" => "success",
            "message" => "$table_name table dropped successfully."
            );
    }
    else
    {
        $response = array(
            "db action" => "drop",
            "status" => "error",
            "message" => "$table_name table failed to drop, sql error: " . mysqli_error($conn)
        );        
    }
        echo json_encode($response);
        echo "<br>\r\n";
}

$conn->close(); 

?>