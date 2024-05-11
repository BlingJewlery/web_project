<?php
session_start();
include 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['User_id'])) {
    
    header("Location: login.php");
    exit();
}

$User_id = $_SESSION['User_id'];

if (isset($_POST['cartremovebtn'])) {
    $User_id = $_POST['User_id'];
    $id = $_POST['id'];
    $query = "DELETE FROM cart WHERE User_id = '$User_id' AND id = '$id';";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo '';
    }
}

if (isset($_POST['cartupdatebtn'])) {
    $User_id = $_POST['User_id'];
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $query = "UPDATE cart SET quantity = '$quantity' WHERE User_id = '$User_id' AND id = '$id';";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo '';
    }
}

if (isset($_POST['checkoutbtn'])) {
    // Verify if the User_id exists in the login table
    
    $userExistsQuery = "SELECT * FROM login WHERE User_id = '$User_id'";
    $userExistsResult = mysqli_query($conn, $userExistsQuery);
    if (mysqli_num_rows($userExistsResult) > 0) {
        // Retrieve order details from the cart table
        $query = "SELECT * FROM cart WHERE User_id = '$User_id'";
        $result = mysqli_query($conn, $query);

        // Insert order details into the orders table using prepared statements
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['id']; 
            $quantity = $row['quantity'];
            $price = $row['price'];
            $totalPrice = $quantity * $price; // Calculate the total price

            $insertQuery = "INSERT INTO orders (User_id, quantity, price, product_ID) VALUES ('$User_id', '$quantity', '$totalPrice', '$product_id')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if (!$insertResult) {
                echo "Error storing order details: " . mysqli_error($conn);
                exit;
            }
        }
            
            // Clear the cart for the user
            $clearCartQuery = "DELETE FROM cart WHERE User_id = '$User_id'";
            $clearCartResult = mysqli_query($conn, $clearCartQuery);

            if ($clearCartResult) {
                echo "Order details stored successfully.";
                header("Location: invoice.php"); 
                exit();
            } else {
                echo "Error clearing cart: " . mysqli_error($conn);
            }
        } 
   
     } 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bling Shop | Shopping Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    padding-top: 90px;
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
    border-radius: 8px;
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

.button-container {
    text-align: center;
    margin-bottom: 20px;
}

.button,
input[type="submit"] {
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

.button:hover,
input[type="submit"]:hover {
    background-color: #c38876;
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
    <h1>Shopping Cart <i class="fa fa-shopping-cart"></i></h1>
    <form method="post" action="cart.php">
        <table>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Description</th>
                <th>Price</th>
                <th>Remove</th>
            </tr>
            <?php

$totalPrice = 0;
$query = "SELECT c.User_id, c.id, c.quantity, p.description, p.price, p.image_url, p.category FROM cart c JOIN products p ON c.id = p.id GROUP BY c.id;";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $image_url = $row['image_url'];
    $id = $row['id'];
    $User_id = $row['User_id'];
    $description = $row['description'];
    $price = $row['price'];
    $quantity = $row['quantity'];

    echo '<tr>';
    echo '<td><img src="data:image;base64,' . base64_encode($row['image_url']) . '" alt="Image" style="width: 100px; height: 100px;"></td>';
    echo '<td>' . $row['category'] . '</td>';
    echo '<td>
            <form action="cart.php" method="post">
                <input type="hidden" name="id" value="' . $id . '">
                <input type="hidden" name="User_id" value="' . $User_id . '">
                <input type="number" name="quantity" value="' . $quantity . '" min="1">
                <input type="submit" name="cartupdatebtn" value="Update">
            </form>
        </td>';
    echo '<td>' . $description . '</td>';
    echo '<td>$' . $price . '</td>';
    echo '<td>
            <form action="cart.php" method="post">
                <input type="hidden" name="id" value="' . $id . '">
                <input type="hidden" name="User_id" value="' . $User_id . '">
                <input type="submit" name="cartremovebtn" value="Remove">
            </form>
        </td>';
    echo '</tr>';

    $totalPrice += $price * $quantity;
}

echo '<tr class="total-row">';
echo '<td colspan="4" class="total-price">Total Price:</td>';
echo '<td colspan="2" class="total-price">$' . number_format($totalPrice, 2) . '</td>';
echo '</tr>';
?>
        </table>
    </form>
    <div class="button-container">
        <a href="products.php" style="text-decoration:none;  color:white;"  class="button">Continue Shopping</a>
        <form method="post" action="cart.php">
            <input type="hidden" name="User_id" value="<?php echo $User_id; ?>">
            <input type="submit" name="checkoutbtn" class="button" value="Checkout">
        </form>
    </div>
</body>
</html>
