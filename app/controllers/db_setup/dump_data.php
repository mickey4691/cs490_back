<?php 

/*
This script will query and delete all data from tables except login info
*/

//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

$configs = include('config.php'); 

$servername	=	$configs['servername'];
$dbusername	=	$configs['dbusername'];
$password	=	$configs['password'];
$dbname     =   $configs['dbname'];

$conn = new mysqli($servername, $dbusername, $password, $dbname);

$tables=array("test_score","test_question","test_case","question_answer","test","question");

for ($i=0; $i < count($tables); $i++)
{
    list_table($conn, $tables[$i]);
}

function list_table($conn, $tablename)
{
    echo "\r\n<br>Table = $tablename\r\n<br>";
    $sql_query="Select * from $tablename;";

    $result = $conn->query($sql_query);
    //if more than 0 row(s) is returned, then we can list all the rows.
    if ($result->num_rows > 0) 
    {   
        $db_response=array();
        $i=0;   
            while($row = $result->fetch_assoc()) 
            {
                $db_response[$i]=$row;
                $i++;
            }
        for ($i=0; $i < count($db_response); $i++)
        {
            $primary_key=($db_response[$i]["primary_key"]);
            delete_sql($conn, $tablename, $primary_key);        
        }
    }
    else
    {
        $response = array
        (
            "status" => "no data to delete"
        );
    }
    echo json_encode($response);

}

function delete_sql($conn, $tablename, $primary_key)
{
    //Generating a generic list query.
    $sql_query="Select * from $tablename where primary_key=$primary_key;";

    $result = $conn->query($sql_query);
    //First we want to ensure that the primary_key exist in the specified table.    
    if ($result->num_rows > 0) 
    {
        //Once the primary_key exist, then we can proceeed to query the 
        //database and delete that row.
        $sql_query="Delete from $tablename where primary_key=$primary_key;";
        $conn->query($sql_query);
        //Generating a response for a successful delete query
        $response = array
        (
            "action" => "delete",
            "status" => "success" 
        );
        http_response_code(200);//Good request
        /*The function header("Content-type:application/json") sends the http json
        header to the browser to inform the receiver on what the kind of data to expect.*/
        header('Content-Type: application/json');
    }
    else
    {
        //error case where the primary_key does not exist
        $response = array
        (
            "action" => "delete",
            "status" => "error" ,
            "user_message" => "Record to be deleted not found",
            "internal_message" => "delete_back.php Record for primary_key=" 
                    .$primary_key. " does not exist in ".$tablename. " table. 
                    If there's an sql error, it will follow the three dots ... "
                    . mysqli_error($conn)
        );
        http_response_code(400); //Bad request
        header('Content-Type: application/json');
    }
     echo json_encode($response);
}

$conn->close(); 

?>