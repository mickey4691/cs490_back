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
        question_answer_id INT(6) NOT NULL AUTO_INCREMENT, 
        question_id INT(6), 
        test_id INT(6), 
        student_id INT(6), 
        answer_text VARCHAR(65535), 
        grade INT NOT NULL, 
        notes VARCHAR(65535),
        PRIMARY KEY ( question_answer_id ),
        FOREIGN KEY (question_id) REFERENCES question(question_id),
        FOREIGN KEY (test_id) REFERENCES test(test_id),
        FOREIGN KEY (student_id) REFERENCES student(student_id)
        )";

        if ($conn->query($sql) === TRUE) 
        {
            echo "Table \"" . $table_name . "\" created successfully<br/>";
        }
    }
}

$conn->close(); 

?>