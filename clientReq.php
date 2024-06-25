<?php 
    include 'external.php';
    /*
        Name: logout()
        Description: Handles ending a user session.
    */
    function logout() {
        session_start();
        $_SESSION = array();
        session_destroy();
    }
    /*
        Name: insertRecord()
        Description: Handles adding a new timecode to database.
    */
    function insertRecord($code, $desc) {
        $query = "INSERT INTO TIMECODES (TIMECODE, DESCRIPTION, ISACTIVE, COMPANYCODE) VALUES ('" . $code . "','" . $desc . "',1,'" . $_SESSION["COMPCODE"] . "');";
        executeSQL($query);
        getTimeCodesTable();
    }
    /*
        Name: insertUser()
        Description: Handles adding a new user to a database.
    */
    function insertUser($user, $fname, $lname, $phone, $email, $password, $security) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO USERINFO (USERNAME, FIRSTNAME, LASTNAME, PHONE, EMAIL, PASSWORD, COMPANYCODE) VALUES ('" . $user . "','" . $fname . "','" . $lname . "','" . $phone . "','" . $email . "','" . $password . "','" . $_SESSION["COMPCODE"] . "');";
        executeSQL($query);
        $sqlInsert = "INSERT INTO USERSECURITY (USERNAME, SECURITYACCESS, COMPANYCODE) VALUES ('" . $user . "','" . $security . "','" . $_SESSION["COMPCODE"] . "');";
        executeSQL($sqlInsert);
        userList();
    }
    /*
        Name: insertTimeCode()
        Description: Handles adding a new timecode log to a database.
    */
    function insertTimeCode($timeCode, $type) {
        updateOpenTimeCodes();
        if (strcmp($type,'submit') == 0) {
            $query = "INSERT INTO TIMECODESLOGGING (USERNAME, TIMECODE, TIMECODESTART, COMPANYCODE) VALUES ('" . $_SESSION['USERID'] . "','" . $timeCode . "', now(), '" . $_SESSION["COMPCODE"] . "');";
            executeSQL($query);
        }
        dailyCodes();
    }
    /*
        Name: updateOpenTimeCodes()
        Description: Handles updating timecode log in a database.
    */
    function updateOpenTimeCodes() {
        $query = "UPDATE TIMECODESLOGGING SET TIMECODEEND = NOW() WHERE USERNAME = '" . $_SESSION['USERID'] . "' AND TIMECODEEND is null AND COMPANYCODE = '" . $_SESSION["COMPCODE"] . "';";
        executeSQL($query);
    }
    /*
        Name: updateRecord()
        Description: Handles updating timecode in a database.
    */
    function updateRecord($code, $active) {
        $query = "UPDATE TIMECODES SET ISACTIVE = " . intval($active) . " WHERE TIMECODE = '" . $code . "' AND COMPANYCODE = '" . $_SESSION["COMPCODE"] . "';";
        executeSQL($query);
    }
    /*
        Name: deleteTimeCode()
        Description: Handles deleting a timecode in a database.
    */
    function deleteTimeCode($timeCode) {
        $query = "DELETE FROM TIMECODES WHERE TIMECODE = '" . $timeCode . "' AND COMPANYCODE = '" . $_SESSION["COMPCODE"] . "';";
        executeSQL($query);
        getTimeCodesTable();
    }
    /*
        Name: executeSQL()
        Description: Handles executing sql statements in a database.
    */
    function executeSQL($sqlQuery) {
        $conn = getDatabaseConnection();
        if ($conn->connect_error) {
            die("connection failed: " . $conn->connect_error);
        }
        // prepare statement
        $sql = $conn->prepare($sqlQuery);
        // execute
        $sql->execute();
        $conn->close();
    }
    // Retrieve the raw POST data
    $jsonData = file_get_contents('php://input');
    // Decode the JSON data into a PHP associative array
    $data = json_decode($jsonData, true);
    // Check if decoding was successful
    if ($data !== null) {
    // Access the data and perform operations
    //$name = $data['name'];
    //$active = $data['isActive'];
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
                            break;

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