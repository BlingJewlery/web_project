<!DOCTYPE html>
<html>
<head>
    
    <link rel="stylesheet" type="text/css" href="headerStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header class="main-header">
        <img src="images/logo.png" class="logo">
        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="products_main.php">Shop</a>
            <?php
            if (session_status() == PHP_SESSION_NONE) {
            session_start();
            };
            if (isset($_SESSION['username'])) {
            echo '<a href="logout.php">Logout</a>';
            } else {
            echo '<a href="login.php">Login</a>';
            }
            ?>
            <a href="cart.php"><i class="fa fa-shopping-cart"></i></a>
            <a href="profile.php"><i class="fa fa-user"></i></a>
        </nav>
        
    </header>
</body>
</html>
