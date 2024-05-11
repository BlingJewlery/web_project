<?php
session_start();
if (!isset($_SESSION['AdminUsername']))
{
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>

 <title>Order Management</title>
    <style>
body {
    font-family: Georgia, "Times New Roman", Times, serif;
    background-color: #f3f3f3;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

nav {
    background-color: #dfbeb1;
    color: #fff;
    padding: 10px;
    text-align: center;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
}

nav ul li a:hover {
    text-decoration: underline;
}

.container {
    max-width: 800px;
    margin: 20px auto; 
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1, h2 {
    color: #333;
}

form {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 10px;
}

select,
input[type="submit"] {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #d9aca4;
    color: #fff;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #c38876;
}
.message-box {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
footer {
    text-align: center;
    margin-top: auto; 
    padding: 10px;
    background-color: #dfbeb1;
    color: #fff;
        }

    </style>
</head>
<body>
 <nav>
    <ul>
        <li><a href="Orders.php">&#8592; Back</a></li>
        <li><a href="admin.php">Main</a></li>
    </ul>
 </nav>
    <div class="container">
        <h1>Order Management</h1>
        <div>
            <h2>Update Order Status</h2>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <input type="hidden" name="order_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">

                <label for="new_status">New Status:</label>
                <select name="new_status" id="new_status" required>
                    <option value="pending">Pending</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <input type="submit" name="update_status" value="Update Status">
            </form>
        </div>

        <div>
            <h2>Delete Order</h2>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <input type="hidden" name="order_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">

                <input type="submit" name="delete_order" value="Delete Order">
            </form>
        </div>
    </div>

<?php
include 'connection.php';

$orderID = isset($_GET['id']) ? $_GET['id'] : '';

if (isset($_POST["update_status"])) {
    $orderID = $_POST['order_id']; 
    $newStatus = $_POST['new_status'];

    // Check if $orderID is not empty and is numeric
    if (!empty($orderID) && is_numeric($orderID)) {
        // Update the order status in the database
        $sql = "UPDATE `orders` SET `Order_Status`= '$newStatus' WHERE `Order_id` = $orderID";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo '<div class="message-box success">Order status updated successfully.</div>';
        } else {
            echo '<div class="message-box error">Error updating order status: ' . mysqli_error($conn) . '</div>';
        }
    } else {
        echo '<div class="message-box error">Invalid or missing order ID.</div>';
    }
}


if (isset($_POST["delete_order"])) {
    $orderID = $_POST['order_id'];

    // Delete the order from the database
    $sql =" DELETE FROM orders WHERE `Order_id` = $orderID";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<div class="message-box success">Order deleted successfully</div>';
    } else {
        echo '<div class="message-box error">Error deleting order: ' . mysqli_error($conn) . '</div>';
    } 
}
mysqli_close($conn);
?>
</div>
</body>
</html>
