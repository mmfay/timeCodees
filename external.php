<?php
    session_start();
    function getDatabaseConnection() {
        $servername = "localhost:3306";
        $username1 = "root";
        $password1 = "vaXjev98";
        $dbname = "TIMECODES";
        $conn = new mysqli($servername,$username1,$password1,$dbname);
        return $conn;
    }
    function getTimeCodesTable() {

        $conn = getDatabaseConnection();
        // create select string
        $sql = "SELECT TIMECODE, DESCRIPTION, ISACTIVE FROM TIMECODES WHERE COMPANYCODE = '" . $_SESSION["COMPCODE"] . "' ORDER BY TIMECODE ASC, ISACTIVE ASC;";

        // prepare/execute statement
        $result = $conn->query($sql); 

        // loop through results and build sidenav list/links
        if($result->num_rows > 0) {
            echo "<tr><th>Time Code</th><th>Description</th><th>Active</th><th>Delete</th></tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr><td>" . $row["TIMECODE"] . "</td><td>" . $row["DESCRIPTION"] . "</td>";
                if ($row["ISACTIVE"] == 1) {
                    echo "<td><input id='" . $row["TIMECODE"] . "' type='checkbox' onclick='updateActive(" . "\"" . $row["TIMECODE"] . "\"" . ")' checked /></td><td><button type='button' onclick='deleteRecord(" . "\"" . $row["TIMECODE"] . "\"" . ")'>Delete</button></td></tr>";
                } else {
                    echo "<td><input id='" . $row["TIMECODE"] . "' type='checkbox' onclick='updateActive(" . "\"" . $row["TIMECODE"] . "\"" . ")'/></td><td><button type='button' onclick='deleteRecord(" . "\"" . $row["TIMECODE"] . "\"" . ")'>Delete</button></td></tr>";
                }
            }
        } 
    }
    function checkCredentials($un, $pw, $cc) {
    
        $conn = getDatabaseConnection();
        
        if ($conn->connect_error) {
            die("connection failed: " . $conn->connect_error);
        }
    
        // sql string
        $sql = "SELECT 
                    USERNAME
                    ,PASSWORD 
                FROM USERINFO 
                WHERE 
                    1=1 
                    AND USERNAME = ? 
                    AND COMPANYCODE = ?";
        $sqlSecurity = "SELECT 
                            SECURITYACCESS 
                        FROM USERSECURITY WHERE USERNAME = ? AND COMPANYCODE = ?";
        // prepare/execute statement while binding parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("ss", $un, $cc);
        $stmt->execute();
    
        // get the results
        $result = $stmt->get_result(); // get the mysqli result
        $user = $result->fetch_assoc(); // fetch data
    
        if (password_verify($pw,$user["PASSWORD"])) {
            $_SESSION["AUTHENTICATED"] = "TRUE";
            $_SESSION["LOGINATTEMPT"] = "TRUE";
            $_SESSION["USERID"] = $un;
            $_SESSION["PASSWORD"] = $pw; 
            $_SESSION["COMPCODE"] = $cc; 
            $stmt = $conn->prepare($sqlSecurity); 
            $stmt->bind_param("ss", $un, $cc);
            $stmt->execute();
            $result = $stmt->get_result(); // get the mysqli result
            $user = $result->fetch_assoc(); // fetch data
            $_SESSION["SECURITY"] = $user["SECURITYACCESS"];
            return TRUE;
        } else {
            $_SESSION["AUTHENTICATED"] = "FALSE";
            $_SESSION["LOGINATTEMPT"] = "TRUE";
            return FALSE;
        }
        
        $conn->close();
    }
    function printOptions() {
        // create select string
        $sql = "SELECT TIMECODE, DESCRIPTION FROM TIMECODES WHERE ISACTIVE = 1 AND COMPANYCODE = '" . $_SESSION["COMPCODE"] . "' ORDER BY TIMECODE ASC;";
    
        // prepare/execute statement
        $result = getDatabaseConnection()->query($sql); 
    
        // loop through results and build sidenav list/links
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                echo "<option id='" . $row["TIMECODE"] . "'>" . $row["TIMECODE"] . " - " . $row["DESCRIPTION"] . "</option>";
            }
        } else {
            echo "<option id='NA'>No Options</option>";
        }
    }
    function adminList() {
        if(strcmp($_SESSION["SECURITY"],"ADMIN") == 0) {
            echo "<li><a href='userManagement.php'>User Management</a></li>";
            echo "<li><a href='timeCodes.php'>Time Codes</a></li>";
            echo "<li><a href='reporting.php'>Reporting</a></li>";
        }
    }
    function authentication($isNotLogin) {
        // check if user has authenticated, if not, check to see if this is a request and look to pass credentials
        if ($isNotLogin) {
            if (!(strcmp($_SESSION["AUTHENTICATED"], "TRUE") == 0)) {
                $un = $_REQUEST["user"];
                $pw = $_REQUEST["password"];
                $cc = $_REQUEST["compCode"];
                if (!checkCredentials($un,$pw,$cc)) {
                    header('Location: login.php');
                } 
            } 
        } else {
            if ((strcmp($_SESSION["AUTHENTICATED"], "TRUE") == 0)) {
                header('Location: home.php');
                 
            } else {
               
            }
        }
    }
    function loginAttempt() {
        if (strcmp($_SESSION["LOGINATTEMPT"],"TRUE") == 0) {
            echo "<label>Login Failed</label>";
            session_destroy();
        } 
    }
    function dailyCodes() {

        // create select string
        $sql = "SELECT 
                    TIMECODE
                    ,TIMECODESTART
                    ,TIMECODEEND
                    ,TIMESTAMPDIFF(HOUR, TIMECODESTART, TIMECODEEND) AS DURATION 
                FROM TIMECODESLOGGING 
                WHERE USERNAME = '" . $_SESSION["USERID"] . "' AND COMPANYCODE = '" . $_SESSION["COMPCODE"] . "' ORDER BY TIMECODESTART DESC;";

        // prepare/execute statement
        $result = getDatabaseConnection()->query($sql); 

        if($result->num_rows > 0) {
            echo "<tr>
            <th>Time Code</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration (Hrs)</th>
            </tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr><td>" . $row["TIMECODE"] . "</td><td>" . $row["TIMECODESTART"] . "</td><td>" . $row["TIMECODEEND"] . "</td><td>" . $row["DURATION"] . "</td>";
            }
        }

    }
    function modifyCodes() {

        // create select string
        $sql = "SELECT 
                    TIMECODE
                    ,TIMECODESTART
                    ,TIMECODEEND
                    ,TIMESTAMPDIFF(HOUR, TIMECODESTART, TIMECODEEND) AS DURATION 
                FROM TIMECODESLOGGING 
                WHERE USERNAME = '" . $_SESSION["USERID"] . "' AND COMPANYCODE = '" . $_SESSION["COMPCODE"] . "' ORDER BY TIMECODESTART DESC;";

        // prepare/execute statement
        $result = getDatabaseConnection()->query($sql); 

        if($result->num_rows > 0) {
            echo "<tr>
            <th>Time Code</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration (Hrs)</th>
            </tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr><td contenteditable='true'>" . $row["TIMECODE"] . "</td><td contenteditable='true'>" . $row["TIMECODESTART"] . "</td><td contenteditable='true'>" . $row["TIMECODEEND"] . "</td><td contenteditable='true'>" . $row["DURATION"] . "</td>";
            }
        }

    }
    function userList() {
        $conn = getDatabaseConnection();
        // create select string
        $sql = "SELECT USERNAME, FIRSTNAME, LASTNAME, PHONE, EMAIL FROM USERINFO WHERE USERNAME <> 'admin' AND COMPANYCODE = '" . $_SESSION["COMPCODE"] . "';";

        // prepare/execute statement
        $result = $conn->query($sql); 
        echo "<tr>
        <th>User ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Phone</th>
        <th>Email</th>
        </tr>";
        // loop through results and build sidenav list/links
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                echo "<tr><td>" . $row["USERNAME"] . "</td><td>" . $row["FIRSTNAME"] . "</td><td>" . $row["LASTNAME"] . "</td><td>" . $row["PHONE"] . "</td><td>" . $row["EMAIL"] . "</td>";
            }
        } 
    }
    function printReportYearOptions() {
        $sql = "SELECT DISTINCT YEAR(TIMECODESTART) AS _YEAR FROM TIMECODESLOGGING WHERE COMPANYCODE = '" . $_SESSION["COMPCODE"] . "';";

        $result = getDatabaseConnection()->query($sql);

        // loop through results and build options
        if($result->num_rows > 0) {
            
            while($row = $result->fetch_assoc()){
                echo "<option id='" . $row['_YEAR'] . "'>" . $row['_YEAR'] . "</option>";
                
            }
            echo "<option id='ALL' selected>ALL</option>";
        } 

    }
    function printUserNameOptions() {
        $sql = "SELECT DISTINCT USERNAME, FIRSTNAME, LASTNAME FROM USERINFO WHERE USERNAME <> 'admin' AND COMPANYCODE ='" . $_SESSION["COMPCODE"] . "';";

        $result = getDatabaseConnection()->query($sql);

        // loop through results and build options
        if($result->num_rows > 0) {
            
            while($row = $result->fetch_assoc()){
                echo "<option id='" . $row['USERNAME'] . "'>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</option>";
                
            }
            echo "<option id='ALL' selected>ALL</option>";
        } 
    }
    function printWeekEndingOptions() {
        $sql = "SELECT DISTINCT 
                    DATE(T.TIMECODEEND + INTERVAL (6 - WEEKDAY(T.TIMECODEEND)) DAY) AS _WEEKEND 
                FROM TIMECODESLOGGING T 
                    WHERE 
                        1=1
                        AND USERNAME <> 'admin' 
                        AND COMPANYCODE ='" . $_SESSION["COMPCODE"] . "';";

        $result = getDatabaseConnection()->query($sql);

        // loop through results and build options
        if($result->num_rows > 0) {
            echo "<option>ALL</option>";
            while($row = $result->fetch_assoc()){
                echo "<option>". $row["_WEEKEND"] . "</option>";
            }
        } 
    }
    function buildReportTable($time, $user) {
        switch ($time) {
            case "ALL":
                switch ($user) {
                    case "ALL":
                        runAllTimeAllUser();
                        break;
                    default:
                        runAllTimeSpecificUser($user);
                        break;
                }
                break;
            default:
                switch ($user) {
                    case "ALL":
                        runSpecificTimeAllUser($time);
                        break;
                    default:
                        runSpecificTimeSpecificUser($time, $user);
                        break;
                }
                break;
        }
    }
    function runAllTimeAllUser() {
        $sql = "SELECT 
                    USERNAME
                    ,TIMECODE
                    ,YEAR(TIMECODESTART) AS _YEAR
                    ,MONTHNAME(TIMECODESTART) AS _MONTH
                    ,SUM(TIMESTAMPDIFF(HOUR, TIMECODESTART, TIMECODEEND)) AS _DURATION 
                FROM TIMECODESLOGGING 
                WHERE COMPCODE = '" . $_SESSION["COMPCODE"] . "'" . 
                "GROUP BY USERNAME, TIMECODE, YEAR(TIMECODESTART), MONTHNAME(TIMECODESTART) 
                ORDER BY MONTHNAME(TIMECODESTART), YEAR(TIMECODESTART) DESC;";
        $result = getDatabaseConnection()->query($sql);

        // loop through results and build options
        if($result->num_rows > 0) {
            echo "<tr>
            <th>User ID</th>
            <th>Time Code</th>
            <th>Year</th>
            <th>Month</th>
            <th>Duration (Hrs)</th>
            </tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr><td>" . $row["USERNAME"] . "</td><td>" . $row["TIMECODE"] . "</td><td>" . $row["_YEAR"] . "</td><td>" . $row["_MONTH"] . "</td><td>" . $row["_DURATION"] . "</td>";
            }
        }
    }

    function runAllTimeSpecificUser($user) {
        $sql = "SELECT 
                    USERNAME
                    ,TIMECODE
                    ,YEAR(TIMECODESTART) AS _YEAR
                    ,MONTHNAME(TIMECODESTART) AS _MONTH
                    ,SUM(TIMESTAMPDIFF(HOUR, TIMECODESTART, TIMECODEEND)) AS _DURATION 
                FROM TIMECODESLOGGING 
                WHERE 
                    1=1
                    AND USERNAME = '" . $user . "' AND COMPANYCODE '=" . $_SESSION["COMPCODE"] . "'" . 
                "GROUP BY USERNAME, TIMECODE, YEAR(TIMECODESTART), MONTHNAME(TIMECODESTART) 
                ORDER BY MONTHNAME(TIMECODESTART), YEAR(TIMECODESTART) DESC;";
        $result = getDatabaseConnection()->query($sql);

        // loop through results and build options
        if($result->num_rows > 0) {
            echo "<tr>
            <th>User ID</th>
            <th>Time Code</th>
            <th>Year</th>
            <th>Month</th>
            <th>Duration (Hrs)</th>
            </tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr><td>" . $row["USERNAME"] . "</td><td>" . $row["TIMECODE"] . "</td><td>" . $row["_YEAR"] . "</td><td>" . $row["_MONTH"] . "</td><td>" . $row["_DURATION"] . "</td>";
            }
        }
    }
    function runSpecificTimeAllUser($time) {
        $sql = "SELECT 
                    USERNAME
                    ,TIMECODE
                    ,YEAR(TIMECODESTART) AS _YEAR
                    ,MONTHNAME(TIMECODESTART) AS _MONTH
                    ,SUM(TIMESTAMPDIFF(HOUR, TIMECODESTART, TIMECODEEND)) AS _DURATION 
                FROM TIMECODESLOGGING 
                WHERE 
                    1=1
                    AND YEAR(TIMECODESTART) = " . $time . " AND COMPANYCODE '=" . $_SESSION["COMPCODE"] . "'" . " GROUP BY USERNAME, TIMECODE, YEAR(TIMECODESTART), MONTHNAME(TIMECODESTART) 
                ORDER BY MONTHNAME(TIMECODESTART), YEAR(TIMECODESTART) DESC;";
        $result = getDatabaseConnection()->query($sql);

        // loop through results and build options
        if($result->num_rows > 0) {
            echo "<tr>
            <th>User ID</th>
            <th>Time Code</th>
            <th>Year</th>
            <th>Month</th>
            <th>Duration (Hrs)</th>
            </tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr><td>" . $row["USERNAME"] . "</td><td>" . $row["TIMECODE"] . "</td><td>" . $row["_YEAR"] . "</td><td>" . $row["_MONTH"] . "</td><td>" . $row["_DURATION"] . "</td>";
            }
        }
    }
    function runSpecificTimeSpecificUser($time, $user) {
        $sql = "SELECT 
                    USERNAME
                    ,TIMECODE
                    ,YEAR(TIMECODESTART) AS _YEAR
                    ,MONTHNAME(TIMECODESTART) AS _MONTH
                    ,SUM(TIMESTAMPDIFF(HOUR, TIMECODESTART, TIMECODEEND)) AS _DURATION 
                FROM TIMECODESLOGGING 
                WHERE 
                    1=1
                    AND YEAR(TIMECODESTART) = " . $time . " AND USERNAME = '" . $user . " AND COMPANYCODE '=" . $_SESSION["COMPCODE"] . "' GROUP BY USERNAME, TIMECODE, YEAR(TIMECODESTART), MONTHNAME(TIMECODESTART) 
                ORDER BY MONTHNAME(TIMECODESTART), YEAR(TIMECODESTART) DESC;";
        $result = getDatabaseConnection()->query($sql);

        // loop through results and build options
        if($result->num_rows > 0) {
            echo "<tr>
            <th>User ID</th>
            <th>Time Code</th>
            <th>Year</th>
            <th>Month</th>
            <th>Duration (Hrs)</th>
            </tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr><td>" . $row["USERNAME"] . "</td><td>" . $row["TIMECODE"] . "</td><td>" . $row["_YEAR"] . "</td><td>" . $row["_MONTH"] . "</td><td>" . $row["_DURATION"] . "</td>";
            }
        }
    }
    function getSecurityList() {
        $sql = "SELECT LISTFIELD FROM TYPELISTS WHERE TYPEFIELD = 1";
        $result = getDatabaseConnection()->query($sql);
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                echo "<option id='" . $row["LISTFIELD"] . "'>" . $row["LISTFIELD"] . "</option>";
            }
        }
    }
    // data review
    function buildDataReview($timeRange) {
        $host = 'localhost:3306';
        $dbname = 'TIMECODES';
        $username = 'root';
        $password = 'vaXjev98';

        // PDO connection string
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        // Attempt to connect
        try {
            // Create a new PDO instance
            $pdo = new PDO($dsn, $username, $password);

            // Set PDO to throw exceptions on errors
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($timeRange == "ALL") {
                $stmt = $pdo->prepare(" SELECT 
                                            USERNAME
                                            ,TIMECODE
                                            ,DATE(TIMECODESTART)    AS _START_DATE
                                            ,TIME(TIMECODESTART)    AS _START_TIME
                                            ,DATE(TIMECODEEND)      AS _END_DATE
                                            ,TIME(TIMECODEEND)      AS _END_TIME 
                                            ,DATE(TIMECODEEND + INTERVAL (6 - WEEKDAY(TIMECODEEND)) DAY)  AS _WEEKENDING
                                        FROM TIMECODESLOGGING");
            } else {
                $stmt = $pdo->prepare("SELECT 
                                            USERNAME
                                            ,TIMECODE
                                            ,DATE(TIMECODESTART)    AS _START_DATE
                                            ,TIME(TIMECODESTART)    AS _START_TIME
                                            ,DATE(TIMECODEEND)      AS _END_DATE
                                            ,TIME(TIMECODEEND)      AS _END_TIME 
                                            ,DATE(TIMECODEEND + INTERVAL (6 - WEEKDAY(TIMECODEEND)) DAY)  AS _WEEKENDING
                                        FROM TIMECODESLOGGING 
                                        WHERE 
                                            1=1
                                            AND (DATE(TIMECODEEND + INTERVAL (6 - WEEKDAY(TIMECODEEND)) DAY) = :value1)");
                $stmt->bindParam(':value1', $timeRange, PDO::PARAM_STR);
            }
            $stmt->execute();
            echo "<tr>
                        <th>User</th>
                        <th>TimeCode</th>
                        <th>Start Date</th>
                        <th>Start Time</th>
                        <th>End Date</th>
                        <th>End Time</th>
                        <th>Week Ending</th>
                    </tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['USERNAME']}</td>
                        <td>{$row['TIMECODE']}</td>
                        <td>{$row['_START_DATE']}</td>
                        <td>{$row['_START_TIME']}</td>
                        <td>{$row['_END_DATE']}</td>
                        <td>{$row['_END_TIME']}</td>
                        <td>{$row['_WEEKENDING']}</td>
                        </tr>";
            }
            $stmt->closeCursor();
            // Additional PDO configurations if needed
            // $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            //echo "Connected successfully";
        } catch (PDOException $e) {
            // Handle connection errors
            die("Connection failed: " . $e->getMessage());
        }
  
 
    }

?>