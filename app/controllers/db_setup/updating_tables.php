<?php 

//Error Messaging 
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

$configs = include('config.php'); 

$servername =   $configs['servername'];
$dbusername =   $configs['dbusername'];
$password   =   $configs['password'];
$dbname     =   $configs['dbname'];
// Creates a connection from the sql server to login and access a specific database
$conn = new mysqli($servername, $dbusername, $password, $dbname);

$sql1="INSERT INTO `question` (`primary_key`, `question_text`, `func_name`, `param_names`, `topic`, `difficulty`, `default_point_value`) VALUES
(244, 'Define a function called factorial that takes a single integer parameter called n and returns n factorial (that is, n!)', 'factorial', 'n', 'Recursion', 'Hard', 15),
(250, 'INTENTIONALLY WRONG - 1 of 4 test cases is incorrect: Implement a function called smells that takes a string parameter called name and returns name concatenated with \" smells\".', 'smells', 'name', 'Strings', 'Easy', 5),
(251, 'Create a function called arraySum that takes an array of numbers passed as parameter arr and returns the sum of the numbers', 'arraySum', 'arr', 'Arrays', 'Medium', 10),
(252, 'Create a function called fizzBuzz that takes an integer parameter x and returns \"fizz\" if divisible by 3, \"buzz\" if divisible by 5, \"fizzbuzz\" if divisible by both 3 and 5, and the number itself if not divisible by either 3 or 5.', 'fizzBuzz', 'x', 'Conditionals', 'Hard', 15),
(253, 'Create a function called breakout that takes a positive integer argument x an returns an array of numbers from 0 to x, inclusive', 'breakout', 'x', 'Loops', 'Medium', 10);";

if (mysqli_query($conn, $sql1)) 
{
    $response= array(
        'action' => 'insert into question table',
        'status' => 'success'
         );
} 
else 
{    
    $response= array(
        'action' => 'insert into question table',
        'status' => 'error',
        'error msg' => mysqli_error($conn)
         );
}
echo(json_encode($response));
echo "\r\n<br>";

$sql2="INSERT INTO `test_case` (`primary_key`, `question_id`, `input`, `output`) VALUES 
(249, 244, '4', '24'),
(250, 244, '1', '1'),
(251, 244, '2', '2'),
(252, 244, '0', '1'),
(253, 244, '3', '6'),
(271, 250, '\"Mickey\"', '\"Mickey smells\"'),
(272, 250, '\"The nose\"', '\"The nose smells\"'),
(273, 250, '\"\"', '\" smells\"'),
(274, 250, '\"MC\"', '\"MC STINKS wrongOO\"'),
(275, 251, '[100,-100,5]', '5'),
(276, 251, '[0]', '0'),
(277, 251, '[1,2,3,4]', '10'),
(278, 251, '[0.5,3.1,6]', '9.6'),
(279, 252, '11', '11'),
(280, 252, '60', '\"fizzbuzz\"'),
(281, 252, '1', '1'),
(282, 252, '25', '\"buzz\"'),
(283, 252, '5', '\"buzz\"'),
(284, 252, '3', '\"fizz\"'),
(285, 253, '0', '[0]'),
(286, 253, '1', '[0, 1]'),
(287, 253, '3', '[0, 1, 2, 3]'),
(288, 253, '5', '[0, 1, 2, 3, 4, 5]');";

if (mysqli_query($conn, $sql2)) 
{
    $response= array(
        'action' => 'insert into test_case table',
        'status' => 'success'
         );
} 
else 
{    
    $response= array(
        'action' => 'insert into test_case table',
        'status' => 'error',
        'error msg' => mysqli_error($conn)
         );
}

$conn->close(); //Closing the db connection
//Generating a JSON message to notify the user of the message.
echo(json_encode($response));

?>