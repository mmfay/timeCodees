<!DOCTYPE html>
<html>
<head>
    <title>timeCodes</title>
</head>
<style>
    .login {
        margin: auto;
        width: 30%;
        background-color: black;
        padding: 10px;
        text-align: center;
        color: white;
        margin-top: 20%;
        border-radius: 2%;
    }
    input[type=text], select {
        width: 100%;
        padding: 12px 2px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
</style>
<body>
    <div class="login">
    <form action="/home.php" method="post">
        <label>User Name</label>
        <input type="text" name="user" id="userNameField">
        <label>Password</label>
        <input type="text" name="password" id="passwordField">
        <input type="submit" value="Login">
    </form>
    </div>
    <script>

    </script>

</body>