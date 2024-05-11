<?php 
include("connection.php");

$errorMsg = "";
$submitted = false;

if(isset($_POST['submit'])) {
    $submitted = true;
    
    // Sanitize and validate inputs
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST["password"]; 

    $emailQuery = "SELECT * FROM login WHERE email = ?";
    $emailStmt = mysqli_prepare($conn, $emailQuery);
    mysqli_stmt_bind_param($emailStmt, "s", $email);
    mysqli_stmt_execute($emailStmt);
    mysqli_stmt_store_result($emailStmt);

    $usernameQuery = "SELECT * FROM login WHERE username = ?";
    $usernameStmt = mysqli_prepare($conn, $usernameQuery);
    mysqli_stmt_bind_param($usernameStmt, "s", $username);
    mysqli_stmt_execute($usernameStmt);
    mysqli_stmt_store_result($usernameStmt);

    function validatePassword($password) {
        if (strlen($password) < 6) {
            return false;
        }

        // Check for at least one uppercase letter, one lowercase letter, and one special character
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
            return false;
        }

        return true;
    }

    // Function to validate first name, last name, and username
    function validateName($name) {
        // Check if the name contains only alphabets
        if (!preg_match('/^[a-zA-Z]+$/', $name)) {
            return false;
        }

        return true;
    }

    function validateUsername($username) {
        // Check if the username contains only alphabets, numbers, and underscores
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return false;
        }

        return true;
    }

    if(empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password)){
        $errorMsg = "<script>alert('All fields are required.');</script>";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorMsg = "<script>alert('Invalid email format.');</script>";
    }
    elseif(mysqli_stmt_num_rows($emailStmt) > 0){
        $errorMsg = "<script>alert('This email is already registered.');</script>";
    }
    elseif(mysqli_stmt_num_rows($usernameStmt) > 0){
        $errorMsg = "<script>alert('This username is already taken.');</script>";
    }
    elseif(!validateName($firstName) || !validateName($lastName)){
        $errorMsg = "<script>alert('First name and last name should contain only alphabets.');</script>";
    }
    elseif(!validateUsername($username)){
        $errorMsg = "<script>alert('Username should contain only alphabets, numbers, and underscores.');</script>";
    }
    elseif(!validatePassword($password)){
        $errorMsg = "<script>alert('Password must be at least 6 characters long and contain at least one uppercase letter, one lowercase letter, and one special character.');</script>";
    }
    if(empty($errorMsg)){
        // Escape data before inserting into the database
        $firstName = mysqli_real_escape_string($conn, $firstName);
        $lastName = mysqli_real_escape_string($conn, $lastName);
        $email = mysqli_real_escape_string($conn, $email);
        $username = mysqli_real_escape_string($conn, $username);
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO login (firstName, lastName, email, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $email, $username, $hashedPassword);
        mysqli_stmt_execute($stmt);

        // Redirect to login.php after successful signup
        header("Location: login.php");
        exit();
    }
}


$conn->close();
?>

<html>
    <head>
        <title>Sign Up</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
    .error-msg {
        color: red;
        margin-top: 10px;
    }
        </style>
    </head>
    <body>
        <?php if($submitted && !empty($errorMsg)): ?>
            <div class="error-msg"><?php echo $errorMsg; ?></div>
            <?php endif; ?>
        <video autoplay muted loop id="video-bg">
            <source src="images/video4.mov" type="video/mp4">
        </video>
        <div id="form">
        <div class="logo-container">
        <img src="images/logo-1.png" alt="Logo" width="300" height="200">
            <h1>Sign Up Form</h1>
            <form name="signupForm" action="" method="POST">
                <label>First Name: </label>
                <input type="text" id="firstName" name="firstName"><br><br>
                <label>Last Name: </label>
                <input type="text" id="lastName" name="lastName"><br><br>
                <label>Email: </label>
                <input type="email" id="email" name="email"><br><br>
                <label>Username: </label>
                <input type="text" id="username" name="username"><br><br>
                <label>Password: </label>
                <input type="password" id="password" name="password"><br><br>
                <input type="submit" id="btn" value="Sign Up" name="submit">
                <a href="login.php" class="forgot-password-btn">Back to Login</a>
            </form>
        </div>
    </body>
</html>