<?php 
    include("connection.php");
    include("login.php");
?>

<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
        <style>
            #adminBtn {
                display: inline-block;
                padding: 8px 16px;
                background-color: #c38876;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
                margin-right: 10px;
                border: none; /* Remove the border for the admin button */
            }

            #adminBtn:hover {
                background-color: #b27362; /* Change the background color on hover */
                cursor: pointer; /* Show pointer cursor on hover */
            }
        </style>
    </head>
    <body>
        <video autoplay muted loop id="video-bg">
            <source src="video4.mov" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
        <div id="form">
            <div class="logo-container">
                <img src="logo.png" alt="Logo" width="300" height="200">
                <h1>Login Form</h1>
                <form name="form" action="login.php" onsubmit="return isvalid()" method="POST">
    <label>Username: </label>
    <input type="text" id="user" name="username"></br></br>
    <label>Password: </label>
    <input type="password" id="pass" name="password"></br></br>
    <input type="submit" id="btn" value="Login" name="submit"/>
    <input type="button" id="adminBtn" value="Login as Admin" onclick="loginAsAdmin()">
</form>

                <div class="additional-options">
                    <a href="signup.php" class="signup-btn">Sign up</a>
                    <a href="forgot_password.php" class="forgot-password-btn">Forgot password?</a>
                </div>
            </div>
        </div>

        <script>
            function isvalid() {
                var user = document.form.user.value;
                var pass = document.form.pass.value;

                // Sanitize user and pass inputs to prevent XSS attacks
                user = sanitizeInput(user);
                pass = sanitizeInput(pass);

                if (user.length === 0 && pass.length === 0) {
                    alert("Username and password fields are empty!");
                    return false;
                } else if (user.length === 0) {
                    alert("Username field is empty!");
                    return false;
                } else if (pass.length === 0) {
                    alert("Password field is empty!");
                    return false;
                }

                return true; // Proceed with form submission
            }

            // Function to sanitize input values
            function sanitizeInput(input) {
                // Replace '<' and '>' characters with HTML entities
                return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            }

            // Function to handle "Login as Admin" button click
            function loginAsAdmin() {
                // Redirect or perform admin login action here
                window.location.href = "adminlogin.php";
            }
        </script>
    </body>
</html>
