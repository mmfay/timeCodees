<?php
    include 'external.php';
    require_once 'session_check.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>timecodes</title>    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../stylesheets/timecodes.css">
</head>
<body>
    <h1 class="titleheader"> timecodes </h1>
    <ul>
        <li><a href="#home">Data Entry</a></li>
        <li><a href="reviewData.php">Review Data</a></li>
        <?php
            adminList();
        ?>
        <li><a onclick='logout()' href="login.php">Logout</a></li>
    </ul>
    <div class="headers">
        <h1>Time Submission</h1>
    </div>
    <div class="form-container">
        <form class="form" id="timeCodeSubmit">
            <label>Time Code
                <select name="timeCodes" id="timeCodes">
                    <?php 
                        printOptions();
                    ?>
                </select>
            </label>
            <button onclick="submitTime('submit')">Submit</button>
            <button onclick="submitTime('stop')">Stop</button>
        </form>
    </div>
    <div class="table-container">
        <table class="timecodes" id="phpTest">
        <colgroup width="150">
        </colgroup>
            <?php 
                dailyCodes();
            ?>
        </table>
    </div>
    <script>
        function submitTime(type) {
            var postData = {
                title: "InsertTimeCode",
                name: "timecode",
                timeCode: timeCodes.options[timeCodes.selectedIndex].id,
                type: type
            }
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "clientReq.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.send(JSON.stringify(postData));
            xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                //no table to update now, just posting.
                document.getElementById("phpTest").innerHTML = xhr.responseText;
            }
            }
        }
        function logout() {
            var postData = {
            title: "Logout"
            };
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "clientReq.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.send(JSON.stringify(postData));
            xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 201) {
          
            } else {
         
            }
            };
        }

    </script>
</body>