<?php
session_start();
include('connection.php');

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement
    $stmt = $conn->prepare("SELECT Password FROM adminlogin WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['Password'];

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Admin login successful
            $_SESSION['AdminUsername'] = $username; // Set session variable
            header("Location: admin.php");
            exit();
        } else {
            // Admin login failed
            echo '<script>
                    window.location.href = "adminlogin.php";
                    alert("Admin login failed. Invalid username or password!!");
                  </script>';
        }
    } else {
        // Admin login failed (username not found)
        echo '<script>
                window.location.href = "adminlogin.php";
                alert("Admin login failed. Invalid username!!");
              </script>';
    }
}
?>
<html>
<head>
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <video autoplay muted loop id="video-bg">
        <source src="images/video4.mov" type="video/mp4">
    </video>
    <div id="form">
        <div class="logo-container">
            <img src="images/logo-1.png" alt="Logo" width="300" height="200">
            <h1>Admin Login</h1>
            <form name="form" action="adminlogin.php" onsubmit="return isvalid()" method="POST">
                <label>Username: </label>
                <input type="text" id="user" name="username"><br><br>
                <label>Password: </label>
                <input type="password" id="pass" name="password"><br><br>
                <input type="submit" id="btn" value="Login" name="submit">
            </form>
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
    </script>
</body>
</html>
