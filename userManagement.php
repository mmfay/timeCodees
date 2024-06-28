<?php
    include 'external.php';
    require_once 'session_check.php';
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
        <li><a href="reviewData.php">Review Data</a></li>
        <?php
            adminList();
        ?>
        <li><a onclick='logout()' href="logout.php">Logout</a></li>
    </ul>
    <div class="headers">
        <h1>Add Worker</h1>
    </div>
    <div class="form-container">
        <form class="form" id="userCreationForm">
            <label>User ID
                <input type="text" id="username"></input>
            </label>
            <label>First Name
                <input type="text" id="firstname"></input>
            </label>
            <label>Last Name
                <input type="text" id="lastname"></input>
            </label>
            <label>Phone
                <input type="text" id="phone"></input>
            </label>
            <label>Email
                <input type="text" id="email"></input>
            </label>
            <label>Password
                <input type="text" id="password"></input>
            </label>
            <label>Security
                <select name="security" id="security">
                    <?php 
                        getSecurityList();
                    ?>
                </select>
            </label>
            <button type="submit">Submit</button>
        </form>
    </div>
    <div class="headers">
        <h1>Manage Workers</h1>
    </div>
    <div class="table-container">
        <table class="timecodes" id="phpTest">
            <?php 
                userList();
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
        userCreationForm.addEventListener("submit", (e) => {
            e.preventDefault();

            var postData = {
                title: "InsertUser",
                name: "user",
                username: document.getElementById("username").value,
                firstname: document.getElementById("firstname").value,
                lastname: document.getElementById("lastname").value,
                phone: document.getElementById("phone").value,
                email: document.getElementById("email").value,
                password: document.getElementById("password").value,
                security: document.getElementById("security").value
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
        });
        </script>
</body>