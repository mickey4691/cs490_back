<?php 

//Error Messaging (can delete after)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


$time = time();

$BACKEND_ENDPOINTS = "https://web.njit.edu/~ps592/cs_490/app/controllers/request/request_parse_back.php";

$error_count = 0;
$time = time();
/*
 * TEST INSERT FUNCTIONALITY FOR ALL TABLES
 */
echo "<h1>INSERT TEST:</h1><br/>\r\n";
$insertJsonPayloads = array(
    "question" => '{"action":"insert", "table_name":"question", "fields":{"question_text":"DummyQuestion' . $time . '", "func_name":"My dumb test function", "param_names":"7, 6, 5, 4, 3"}}',
    "test_case" => '{"action":"insert", "table_name":"test_case", "fields":{"question_id":1,"input":"DummyTestCase' . $time .'", "output":"output_val goes here, or more, ##"}}',
    "student" => '{"action":"insert", "table_name":"student", "fields":{"user_name":"DummyStudent' . $time .'", "hash_salt":"$2y$10$BBp8BHUg9rfwFrOqLLeMY.c0SimhUAyW3J8K3.qwY500lcnT1ccPGEND"}}',
    "professor" => '{"action":"insert", "table_name":"professor", "fields":{"user_name":"DummyProf' . $time .'", "hash_salt":"$2y$10$BBp8BHUg9rfwFrOqLLeMY.c0SimhUAyW3J8K3.qwY500lcnT1ccPGEND"}}',
    "test" => '{"action":"insert", "table_name":"test", "fields":{"professor_id":1,"scores_released":0,"finalized":0}}',
    "question_answer" => '{"action":"insert", "table_name":"question_answer", "fields":{"question_id":1,"test_id":1,"student_id":1,"answer_text":"DummyQuestionAnswer' . $time . '","grade":100,"notes":"lorem ipsum"}}',
    "test_question" => '{"action":"insert", "table_name":"test_question", "fields":{"test_id":1,"question_id":5}}',
    "test_score" => '{"action":"insert", "table_name":"test_score", "fields":{"student_id":1,"test_id":1,"test_name":"cs490_final","grade":100}}',
);
$insertedItems = array();
$keysArray = array_keys($insertJsonPayloads);
for ($i=0; $i<count($keysArray); $i++) {
    echo "============================\r\n";
    echo "Trying to insert record into " . $keysArray[$i] . " table...<br/>\r\n";
    $json = $insertJsonPayloads[$keysArray[$i]];
    $backend_endpoint = $BACKEND_ENDPOINTS;
    $new_post_params = array("json_string" => $json);
    $header = array(); 
    $backend_json_response = curl_to_backend($header, 
                                             $backend_endpoint, 
                                             http_build_query($new_post_params));
    $parsed_response = json_decode($backend_json_response,true);
    if ($parsed_response["status"] == "success") {
        echo "Successfully performed " . $keysArray[$i] . "<br/>\r\n";
        $indexName = $keysArray[$i];
        $insertedItems[$indexName] = $parsed_response["items"]["0"];
    } else {
        echo '<font color= "red"> ERROR </font> trying to ' . $keysArray[$i] . "<br/>\r\n";
        $error_count++;
    }
    echo "Response received from back end: <br/>\r\n";
    echo $backend_json_response;
    echo "<br/>\r\n";
}

function curl_to_backend($header, $url, $post) {       
    $curl_obj = curl_init();
    curl_setopt_array($curl_obj, array(
        CURLOPT_URL => $url,
        CURLOPT_FOLLOWLOCATION => 1,    // True - Follow HTTP 3xx redirects (probably unneeded)
        CURLOPT_MAXREDIRS => 10,        // Max no. of redirects to follow (see above)
        CURLOPT_RETURNTRANSFER => 1,    // Sets return value of curl_exec to true
        CURLOPT_ENCODING => "",         // If "", header containing all supported encoding types is sent
        CURLOPT_TIMEOUT => 30,          // In seconds
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0",
        CURLOPT_HEADER => 0,            // False - DON'T incude response header in output
        CURLOPT_HTTPHEADER => $header,  // A PHP array of HTTP header fields
        CURLOPT_POST => 1,              // True - This is a post request
        CURLOPT_POSTFIELDS => $post,    // NOTE: $post is a *query string*, NOT a PHP array
    ));
    $response_data = curl_exec($curl_obj);
    $err = curl_error($curl_obj);
    if ($err) {
        http_response_code(500);
        $curl_error = array(
            "action" => "unknown",
            "status" => "error",
            "user_message" => "An error has occured.",
            "internal_message" => "error connecting to other"
    );
        curl_close($curl);
        http_response_code(500);
        header('Content-Type: application/json');
        exit(json_encode($curl_error));
    } 
    // Return response
    curl_close($curl_obj);
    return ($response_data); 
}

?>