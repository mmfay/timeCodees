<?php
    include 'external.php';
    session_start();
    authentication(TRUE);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>Time Codes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheets/timecodes.css">
</head>
<style>
</style>
<body>
    <h1 class="titleheader"> timeCodes </h1>
    <ul>
        <li><a href="home.php">Data Entry</a></li>
        <li><a href="#timecodeentry">Review Data</a></li>
        <?php
            adminList();
        ?>
    </ul>
    <div class="headers">
        <h1>Create Time Codes</h1>
    </div>
    <div class="form-container">
        <form class="form" id="submissionForm">
            <label>Time Code
                <input type="text" id="newTC"></input>
            </label>
            <label>Description
                <input type="text" id="newDesc"></input>
            </label>
            <button type="submit">Submit</button>
        </form>
    </div>
    <div class="headers">
        <h1>Time Code List</h1>
    </div>
    <div class="table-container">
        <table class="timecodes" id="phpTest">
            <tr>
                <th>Time Code</th>
                <th>Description</th>
                <th>Active</th>
                <th>Delete</th>
            </tr>
            <?php 
                getTimeCodesTable();
            ?>
        </table>
    </div>
    
    <script>
        function updateActive(test) {
            var active = "";
            if (document.getElementById(test).checked) {
                active = "1";
            } else {
                active = "0";
            }
            var postData = {
            title: "Update",
            name: test,
            isActive: active
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
        submissionForm.addEventListener("submit", (e) => {
            e.preventDefault();

            var postData = {
                title: "Insert",
                name: "timeCode",
                code: document.getElementById("newTC").value,
                desc: document.getElementById("newDesc").value,
            }
            var xhr = new XMLHttpRequest();

            xhr.open("POST", "clientReq.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.send(JSON.stringify(postData));
            xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                document.getElementById("phpTest").innerHTML = xhr.responseText;
            }
        }
        });
        </script>
</body>