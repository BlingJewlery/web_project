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

    .container {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        margin-top: 20px;
        padding-left: 20px;
    }
    nav {
           
        background-color: #dfbeb1;
        color: #d9aca4;
        padding: 10px;
        }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        text-align: center;
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
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        margin-top: 20px;
        padding-left: 20px;
        }

    .add-product, .product-table-container {
        padding: 20px;
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    tr:hover {background-color: #CCCCCC;}


    .add-product {
        width: 30%;
        }

    .product-table-container {
        width: 120%;
        flex-grow: 1;
        }

    h1 {
        color: #333333;
        text-align: center;
        }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        background-color: #fff; 
        }

    th, td {
        text-align: left;
        padding: 10px;
        border-bottom: 1px solid #333333;
         }
    

    th {
        background-color: #ece4dc;
        }
  
    tr:nth-child(even) {background-color: #f2f2f2;}



    .message-box {
        background-color: #A5A09Bff;
        color: #721c24;
        padding: 10px;
        margin-top: 10px;
          }

    form {
        margin-top: 20px;
        text-align: center;
         }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        }

    input[type="text"], input[type="number"], input[type="file"] {
        width: calc(100% - 22px);
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #cccccc;
        border-radius: 3px;
    }

    input[type="submit"] {
        background-color: #d9aca4;
        color: #ffffff;
        border: none;
        padding: 8px 15px;
        cursor: pointer;
        font-size: 14px;
        border-radius: 3px;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #c38876;
    }

    .my-button {
        background-color: #d9aca4;
        color: #ffffff;
        border: none;
        padding: 8px 15px;
        cursor: pointer;
        font-size: 14px;
        border-radius: 3px;
        transition: background-color 0.3s;
    }

    .my-button:hover {
        background-color: #c38876;
    }



    .product-table-body {
        overflow-y: auto;
        max-height: 550px;
    }

    .product-table-header {
        overflow: hidden;
    }
    </style>
</head>
<body>
<nav>
    <ul>
        <li><a href="admin.php">Main</a></li>
    </ul>
</nav>
<h1>Order Management</h1>
<div class="container">
    <div class="product-table-container">
        <h2>Orders List</h2>
        <div class="product-table-body">
            <table>
                <thead class="product-table-header">
                    <tr>
                        <th>Order ID</th>
                        <th>User ID </th>
                        <th>quantity </th>
                        <th>Price</th>
                        <th>Order Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

<?php 
include 'connection.php';

$sql = "SELECT * FROM orders";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['Order_id'] . '</td>';
        echo '<td>' . $row['User_id'] . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . $row['price'] . '</td>';
        echo '<td>' . $row['Order_Status'] . '</td>';
        echo '<td>';
        $orderDetailsURL = "order_management.php?id=" . $row['Order_id'];


        echo '<form method="post" action="' . $_SERVER["PHP_SELF"] . '">';
        echo '<input type="hidden" name="order_id" value="' . $row['Order_id'] . '">';
        echo '<input type="submit" name="manage_order" value="Manage the order" formaction="' . $orderDetailsURL . '">';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }
}
mysqli_close($conn);
            ?>
        </tbody>
        </table>
    </div>
</div>
</body>
</html>
