<?php
session_start(); 

include 'connection.php';

if (!isset($_SESSION['User_id'])) { 
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['User_id']; 

// Fetch user information from the login table
$query = "SELECT * FROM login WHERE User_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Fetch cart items from the database
$query = "SELECT c.Order_id, c.quantity, c.price, c.product_ID FROM orders c WHERE c.User_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = array();

while ($row = $result->fetch_assoc()) {
    $product_id = $row["product_ID"];
    $sql = "SELECT image_url FROM products WHERE id = ?";
    
    // Prepare the product query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    
    // Execute the product query and retrieve the product details
    $product_result = $stmt->execute();
    
    if ($product_result === false) {
        echo "An error occurred while executing the query.";
    } else {
        $product_row = $stmt->get_result()->fetch_assoc();
        
        if ($product_row) {
            // Combine the cart item and product details
            $cart_item = array(
                "Order_id" => $row["Order_id"],
                "quantity" => $row["quantity"],
                "price" => $row["price"],
                "image_url"=> base64_encode($product_row["image_url"])
            );
            
            $image_path = $product_row["image_url"];
            if (file_exists($image_path)) {
                $image_data = file_get_contents($image_path);
                if ($image_data !== false) {
                    $cart_item["image_url"] = base64_encode($image_data);
                }
            }
            
            // Add the cart item to the cart_items array
            $cart_items[] = $cart_item;
        }
    }
    
    $stmt->close();
}
// Calculate subtotal
$sub_total = 0;
foreach ($cart_items as $item) {
    $sub_total += $item['price'] * $item['quantity'];
}

// Calculate VAT
$vat = $sub_total * 0.15;

// Calculate total price including VAT
$total_price = $sub_total + $vat;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bling Shop | Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
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

            padding-left: 58.3%;

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
        }

        th, td {
            padding: 10px;
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
   </style>
   <script>
        function displayDateTime() {
            var dateTime = new Date();
            var date = dateTime.toDateString();
            var time = dateTime.toLocaleTimeString();

            document.getElementById("date").innerHTML = "<strong>Date:</strong> " + date;
            document.getElementById("time").innerHTML = "<strong>Time:</strong> " + time;
        }

        // Call the displayDateTime function when the page loads
        window.onload = function() {
            displayDateTime();
        };
    </script>
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
        <h1>Invoice</h1>
        <p><strong>Customer Name:</strong> <?php echo $customer['firstName'] . ' ' . $customer['lastName']; ?></p> 
        <p id="date"></p>
        <p id="time"></p>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($cart_items as $item) { ?>
    <tr>
    <td><img src="data:image/png;base64,<?php echo $item['image_url']; ?>" alt="Image" style="width: 100px; height: 100px;"></td>       <td><?php echo $item['price']; ?></td>
        <td><?php echo $item['quantity']; ?></td>
        <td><?php echo $item['price'] * $item['quantity']; ?></td>
    </tr>
<?php } ?>
<tr class="total-row">
    <td colspan="3" class="total-price">Subtotal:</td>
    <td><?php echo $sub_total; ?> SR</td>
</tr>
<tr class="total-row">
    <td colspan="3" class="total-price">VAT (15%):</td>
    <td><?php echo $vat; ?> SR</td>
</tr>
<tr class="total-row">
    <td colspan="3" class="total-price">Total Price (Including VAT):</td>
    <td><?php echo $total_price; ?> SR</td>
</tr>