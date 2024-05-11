<?php
include "connection.php";
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['User_id'])) { 
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['User_id'];

$query = "SELECT * FROM login WHERE User_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$userData = $result->fetch_assoc();

function sanitize_input($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bling Shop | User Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/headerStyle.css">
    <style type="text/css">
        body {
            font-family: Georgia, "Times New Roman", Times, serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }
  
        .page{
            width:1250px;
            margin:0 auto;
            padding-top:0px;

        }
        header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            border-bottom: 1px solid #dee0df;
            padding-bottom: 20px;
            padding-top: 0px;
            background-color: #F4F3F1ff;
        }

        header::before {
            content: "";
            display: block;
            height: 90px;
        }

        header + * {
            margin-top: 20px;
        }

        .navbar {

            padding-left: 57.5%;

        }

        .navbar a {

            float: left;

            display: block;

            padding: 5px 10px;

            color:  #7d7d7b;

            background-color:#F4F3F1ff;

            text-align: center;

            text-decoration: none;

            font-family: Georgia, "Times New Roman", Times, serif; 

            font-size: 13px;

        }

        .navbar a:hover {

            color: #c2a976;

        }



        .logo {

            padding-left: 5%;

            width: 90px; 

            height: auto;

            padding-bottom: 0%;

            padding-top: 0%;  

        }

        footer {

            background-color: #9b9b98;

            color: #fff;

            text-align: center;

            padding: 5px;

            position: absolute;

            display:flex;

            left: 0;

            width: 99.3%;

            align-items: center;

        }

        .right ul {

            padding-left:100px;

            text-align: left;

            color:#F4F3F1ff;

            font-family: Georgia, "Times New Roman", Times, serif; 

            list-style-type: none;

            padding: 0;

            position: relative;

        }

        .right ul li {

            padding-left:530px;

            font-size: 13px;

            text-align: left;

            color:#F4F3F1ff;

            line-height: 3;

            font-family: Georgia, "Times New Roman", Times, serif;

            position: relative;

        }
        .left{

            float: left;

            display:block;

            font-family: Georgia, "Times New Roman", Times, serif;

            color:#F4F3F1ff;

            text-align: center;

            padding-top:0px;

            padding-left:90px;

            position: absolute;

            padding-bottom:0%;

        }

        .soc{
            padding-left:190px;

            font-size: 12px;

            text-align: center;

            color:#F4F3F1ff;

            font-family: Georgia, "Times New Roman", Times, serif;
        }

        .soc p{

            padding-top: 0px;

            line-height: 1.5;

            text-align: center, justify;


        }

        .soc a{

            display: inline-block;

            width: 35px;

            height: 35px;

            cursor: pointer;

            background-color:  #696969;

            border-radius: 2px;

            font-size: 20px;

            color: #F4F3F1ff;

            text-align: center;

            line-height: 35px;

            margin-right: 3px;

            margin-bottom: 5px;

        }
        body, html {
            margin: 0; 
            padding: 0;}
        .main-header {
            width: 100%;
            background-color: #f2f2f2;
            padding: 10px 0;
            text-align: center; 
            margin: 0 auto;
        }

        .main-header .logo {
            width: 100px; 
            height: auto;
            display: inline-block; 
            margin-right: 20px; 
        }

        .main-header .navbar {
            display: block; 
            float: right; 
        }

        .main-header .navbar a {
            text-decoration: none;
            color: #5d5c5c; 
            margin: 0 6px;

        }
        .main-header .navbar a:hover {

            color: #c2a976;

        }

        .container {
            width: auto;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8 px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #d9aca4;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ead4cc;
            text-align: left;
        }

        th {
            background-color: #ead4cc;
        }

        tr:nth-child(even) {
            background-color: #fff;
        }

        tr:nth-child(odd) {
            background-color: #f3f3f3;
        }

        .total-row {
            font-weight: bold;
        }

        .total-price {
            color: #d9aca4;
        }

        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .button {
            background-color: #d9aca4;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 40px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 10px 10px;
        }

        .button:hover {
            background-color: #c38876;
        }

        .image-container img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <img src="images/logo.png" class="logo" style="width: 180px;">
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

    <div class="container">
        
        <h1>User Profile</h1>
        <table>
            <tr>
                <th>Full Name</th>
                <td><?php echo sanitize_input($userData['firstName']) ; echo " "; echo sanitize_input($userData['lastName'])  ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo sanitize_input($userData['email']); ?></td>
            </tr>
            
        </table>
        <div class="button-container">
            
        </div>
        
    </div>
</body>
</html>