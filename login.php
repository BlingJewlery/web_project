<?php
session_start();
include('connection.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM login WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['Password'];

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            $_SESSION['User_id'] = $row['User_id']; 
            header("Location: home.php");
            exit();
        } else {
            echo '<script>
                    alert("Login failed. Invalid username or password!");
                    window.location.href = "login.php";
                  </script>';
            exit();
        }
    } else {
        echo '<script>
                alert("Login failed. Invalid username or password!");
                window.location.href = "login.php";
              </script>';
        exit();
    }
}
?>

<html>
    <head>
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            #adminBtn {
                display: inline-block;
                padding: 8px 16px;
                background-color: #c38876;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
                margin-right: 10px;
                border: none; 
            }

            #adminBtn:hover {
                background-color: #b27362;
                cursor: pointer; 
            }
        </style>
    </head>
    <body>
        <video autoplay muted loop id="video-bg">
            <source src="images/video4.mov" type="video/mp4">
        </video>
        
        <div id="form">
            <div class="logo-container">
                <img src="images/logo-1.png" alt="Logo" width="300" height="200">
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

                return true; 
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
