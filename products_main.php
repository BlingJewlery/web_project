<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION['username']))
{
    header("Location: login.php");
    exit();
}

include ('include/header.php');
$host = 'localhost';
$dbname = 'bling_shop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$query = $pdo->query('SELECT name, image, link FROM categories');
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bling Shop | Products </title>
    <link rel="stylesheet" type="text/css" href="css/headerStyle.css"> 
    <style type="text/css">

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
        }

        .auto-style1 {
            text-align: center;
            font-family: Georgia, "Times New Roman", Times, serif;
            font-size: x-large;
            color: #FFFFFF;
            margin: 0;
            padding: 8px 0;
            letter-spacing: 3pt;
        }
        .auto-style2 {
            background-color: #ece4dc;
            width: 100%;
            padding: 10px 0;
            text-align: center;
            margin-top: 0;
        }
        .header-image {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            position: relative;
        }
        .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #FFFFFF;
            font-family: Georgia, "Times New Roman", Times, serif;
            font-size: large;
            text-align: center;
            padding: 10px;
        }
        .auto-style3 {
            width: 100%;
            object-fit: cover;
            display: block;
        }
        .sections-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto;
            width: 100%;
            margin: 0;
        }
        .section {
            font-family: Georgia, "Times New Roman", Times, serif;
            font-size: large;
            color: #000000;
            cursor: pointer;
            padding: 20px;
            text-align: center;
            background-color: #FFF;
            background-image: url('images/img/background.jpeg');
            background-size: cover;
            width: 100%;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            transition: filter 0.3s ease;
            text-decoration: none;
        }
        .section:hover {
            filter: blur(2px);
            color: #DED2BC;
        }
        .section-necklaces {
            background-image: url('images/img/neck.jpeg');
        }
        .section-earrings {
            background-image: url('images/img/earings.jpeg');
        }
        .section-bracelets {
            background-image: url('images/img/brac.jpeg');
        }
        .section-rings {
            background-image: url('images/img/rings.jpeg');
        }
    </style>
</head>
<body>
    <div class="header-image">
        <img alt="Header image" class="auto-style3" src="images/img/background.jpeg" height="260">
        <div class="overlay-text">
            "Unveil your style with our creations, crafted for every moment and every heart."
        </div>
    </div>
    <div class="auto-style2">
        <p class="auto-style1">Our Products</p>
    </div>
    <div class="sections-container">
        <?php
        foreach ($categories as $category) {
            
            echo '<a href="products.php?category=' . urlencode($category['name']) . '" class="section" style="background-image: url(images/' . $category['image'] . ');">' . htmlspecialchars($category['name']) . '</a>';
        }
        ?>
    </div>
    <?php include 'include/footer.html'; ?>

</body>
</html>
