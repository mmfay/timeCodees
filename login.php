<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $companycode = $_POST['companycode'];

        // Your authentication logic goes here (e.g., checking against a database)
        // For demonstration, let's assume the correct username and password are "admin"
        $correct_username = 'admin';
        $correct_password = 'password';
        $correct_companycode = 'GCIA';

        // Check if username and password are correct
        if ($username === $correct_username && $password === $correct_password && $companycode === $correct_companycode) {
            // Authentication successful
            session_start();
            $_SESSION['username'] = $username; // Store username in session variable
            $_SESSION['companycode'] = $companycode;
            header("Location: home.php"); // Redirect to welcome page
            exit();
        } else {
            // Authentication failed
            echo "<script>alert('Invalid username or password');</script>"; 
        }
    }
?>