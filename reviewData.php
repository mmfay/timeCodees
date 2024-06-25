<?php
    include 'external.php';
    session_start();
    authentication(TRUE);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>timecodes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheets/timecodes.css">
</head>
<style>
</style>
<body>
    <h1 class="titleheader"> timecodes </h1>
    <ul>
        <li><a href="home.php">Data Entry</a></li>
        <li><a href="#timecodeentry">Review Data</a></li>
        <?php
            adminList();
        ?>
        <li><a onclick='logout()' href="login.php">Logout</a></li>
    </ul>
    <div class="headers">
        <h1>Data Entry Review</h1>
    </div>
    <div class="reporting-container">
        <form class="form" id="dataRequest">
            <select id="weekEnding" name="weekEnding">
                <?php 
                    printWeekEndingOptions();
                ?>
            </select>
            <button type="submit" class="test1">Get Data</button>
        </form>
    </div>
    <div class="table-container">
        <table class="timecodes" id="dataTable">
        </table>
    </div>
    <script>
        dataRequest.addEventListener("submit", (e) => {
            e.preventDefault();
   
            
            var postData = {
                title: "Select",
                name: "dataReview",
                timeRange: document.getElementById("weekEnding").value
            }
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "clientReq.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.send(JSON.stringify(postData));
            xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                document.getElementById("dataTable").innerHTML = xhr.responseText;
            } else {
                document.getElementById("dataTable").innerHTML = "<h1>ERROR</h1>";
            }
        }
        });
        </script>
</body>