<?php 
    include 'external.php';

    // Retrieve the raw POST data
    $jsonData = file_get_contents('php://input');
    // Decode the JSON data into a PHP associative array
    $data = json_decode($jsonData, true);
    // Check if decoding was successful
    if ($data !== null) {
    // Access the data and perform operations
    // Perform further processing or respond to the request
            $type = $data['title'];
            switch ($type) {
                case "Insert":
                    $code = $data['code'];
                    $desc = $data['desc'];
                    insertRecord($code, $desc);
                    break;
                case "InsertUser":
                    $user = $data['username'];
                    $fname = $data['firstname'];
                    $lname = $data['lastname'];
                    $phone = $data['phone'];
                    $email = $data['email'];
                    $password = $data['password'];
                    $security = $data['security'];
                    insertUser($user, $fname, $lname, $phone, $email, $password, $security);
                    break;
                case "InsertTimeCode":
                    $timeCode = $data['timeCode'];
                    $type = $data['type'];
                    insertTimeCode($timeCode, $type);
                    break;
                case "Update":
                    $name = $data['name'];
                    $active = $data['isActive'];
                    updateRecord($name, $active);
                    break;
                case "Delete":
                    $name = $data['name'];
                    deleteTimeCode($name);
                    break;
                case "Select":
                    $name = $data['name'];
                    switch($name) {
                        case "dataReview":
                            $timeRange = $data['timeRange'];
                            buildDataReview($timeRange);
                            break;
                        case "reporting":
                            $userRange = $data['userRange'];
                            $timeRange = $data['timeRange'];
                            buildReportTable($timeRange, $userRange);
                            break;   
                        default:
                            echo "error";

                    }
                case "Logout":
                    logout();
                    break;
                default:
                    echo "error";
            }

    } else {
        // JSON decoding failed
        http_response_code(400); // Bad Request
        echo "Invalid JSON data";
    }

?>