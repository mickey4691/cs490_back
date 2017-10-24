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

$table_name="question_answer";

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
        question_id INT(6) NOT NULL, 
        test_id INT(6) NOT NULL, 
        student_id INT(6) NOT NULL, 
        answer_text VARCHAR(65535) NOT NULL, 
        grade INT NOT NULL, 
        notes VARCHAR(65535),
        PRIMARY KEY ( primary_key ),
        FOREIGN KEY (question_id) REFERENCES question(primary_key),
        FOREIGN KEY (test_id) REFERENCES test(primary_key),
        FOREIGN KEY (student_id) REFERENCES student(primary_key)
        )";

        if ($conn->query($sql) === TRUE) 
        {
            echo "Table \"" . $table_name . "\" created successfully<br/>";
        }
    }
}

$conn->close(); 

?>