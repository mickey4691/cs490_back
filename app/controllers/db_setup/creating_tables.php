<?php 

//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

$configs = include('config.php'); 

$servername =	$configs['servername'];
$dbusername =	$configs['dbusername'];
$password   =	$configs['password'];
$dbname     =   $configs['dbname'];

$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} 

$table_name = "professor";
$sql_query_1 = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT, 
        user_name VARCHAR(255) NOT NULL UNIQUE,
        hash_salt VARCHAR(255) NOT NULL,
        PRIMARY KEY ( primary_key )
        )";
create_table($conn, $table_name, $sql_query_1);

$table_name = "student";
$sql_query_2 = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT,
        user_name VARCHAR(255) NOT NULL UNIQUE, 
        hash_salt VARCHAR(255) NOT NULL, 
        PRIMARY KEY ( primary_key )
        )";
create_table($conn, $table_name, $sql_query_2);

$table_name = "question";
$sql_query_3 = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT, 
        question_text VARCHAR(65535) NOT NULL, 
        func_name VARCHAR(255) NOT NULL, 
        param_names VARCHAR(255) NOT NULL,        
        topic VARCHAR(255) NOT NULL,
        difficulty VARCHAR(255) NOT NULL, 
        default_point_value INT(6) NOT NULL,
        PRIMARY KEY ( primary_key )
        )";
create_table($conn, $table_name, $sql_query_3);

$table_name = "test";
$sql_query_4 = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT, 
        professor_id INT(6) NOT NULL,
        test_name VARCHAR(255) NOT NULL,
        scores_released TINYINT(1) DEFAULT 0,
        finalized TINYINT(1) DEFAULT 0,
        start_date DATETIME, 
        end_date DATETIME,
        PRIMARY KEY ( primary_key ),
        FOREIGN KEY (professor_id) REFERENCES professor(primary_key)
        )";
create_table($conn, $table_name, $sql_query_4);

$table_name = "test_question";
$sql_query_5 = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT, 
        test_id INT(6) NOT NULL, 
        question_id INT(6) NOT NULL,
        point_value INT(6) NOT NULL, 
        PRIMARY KEY ( primary_key ),
        FOREIGN KEY (test_id) REFERENCES test(primary_key),
        FOREIGN KEY (question_id) REFERENCES question(primary_key)
        )";
create_table($conn, $table_name, $sql_query_5);

$table_name = "test_case";
$sql_query_6 = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT, 
        question_id INT(6) NOT NULL, 
        input VARCHAR(65535) NOT NULL, 
        output VARCHAR(65535) NOT NULL,
        PRIMARY KEY ( primary_key ),
        FOREIGN KEY (question_id) REFERENCES question(primary_key)
        )";
create_table($conn, $table_name, $sql_query_6);

$table_name = "question_answer";
$sql_query_7 = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT, 
        question_id INT(6) NOT NULL, 
        test_id INT(6) NOT NULL, 
        student_id INT(6) NOT NULL, 
        question_text VARCHAR(65535) NOT NULL,
        answer_text VARCHAR(65535) NOT NULL, 
        grade INT(6) NOT NULL DEFAULT 0, 
        point_value INT(6) NOT NULL,
        notes VARCHAR(65535),
        professor_notes VARCHAR(65535),
        PRIMARY KEY ( primary_key ),
        FOREIGN KEY (question_id) REFERENCES question(primary_key),
        FOREIGN KEY (test_id) REFERENCES test(primary_key),
        FOREIGN KEY (student_id) REFERENCES student(primary_key)
        )";
create_table($conn, $table_name, $sql_query_7);

$table_name = "test_score";
$sql_query_8 = "CREATE TABLE ". $table_name ." (
        primary_key INT(6) NOT NULL AUTO_INCREMENT,         
        test_id INT(6) NOT NULL, 
        test_name VARCHAR(255) NOT NULL,
        student_id INT(6) NOT NULL, 
        student_name VARCHAR(255) NOT NULL UNIQUE,
        grade INT(6) NOT NULL DEFAULT 0,
        scores_released TINYINT(1) DEFAULT 0,
        raw_points INT(6) NOT NULL,
        max_points INT(6) NOT NULL,
        PRIMARY KEY ( primary_key ),
        FOREIGN KEY (test_id) REFERENCES test(primary_key),
        FOREIGN KEY (student_id) REFERENCES student(primary_key)
        )";
create_table($conn, $table_name, $sql_query_8);


function create_table($conn, $table_name, $sql_query)
{
    //Will first check to see if table exist.
    if ($result = $conn->query("SHOW TABLES LIKE '".$table_name."'")) 
    {
        //if table exist, return a message
        if($result->num_rows == 1) 
        {
            $response = array(
            "status" => "database duplicate",
            "internal_message" => "$table_name table exists."
            );
        }
        else //create table if does not exist
        {
            //successful query, generate a good response.
            if ($conn->query($sql_query) === TRUE) 
            {
                $response = array(
                "status" => "database success",
                "message" => "$table_name table created."
                );
            }        
        }
        echo json_encode($response);
        echo "<br>\r\n";
    }
}

$conn->close(); 
?>