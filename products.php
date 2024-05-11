<?php
include 'connection.php';

session_start();
if (!isset($_SESSION['User_id']))
{
    header("Location: login.php");
    exit();
}
include ('include/header.php');


$User_id = $_SESSION['User_id'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['addcartsubmitbtn'])) {
    
    $productId = $_POST['id'];
    $price = $_POST['price'];



    $sql = "INSERT INTO cart (id, User_id, price) VALUES ('$productId', '$User_id', '$price')";

    if ($conn->query($sql) === TRUE) {
        header("Location: cart.php");
    
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

$category = isset($_GET['category']) ? $_GET['category'] : 'Rings';

$sql = "SELECT id, description, price, image_url FROM products WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bling Shop | <?php echo htmlspecialchars($category); ?></title>
    <link rel="stylesheet" type="text/css" href="css/headerStyle.css"> 

    <style type="text/css">
        
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
        }
    
        .container {
            display: flex;
            flex-direction: row;
        }

        
        .sidebar {
            width: 200px;
            background-color: #dfbeb1;
            padding: 20px;
            border-right: 1px solid #e9dcd3;
        }

        .sidebar a {
            display: block;
            padding: 10px 0;
            color: #c38876;
            text-decoration: none;
            font-weight: bold;
        }

        .sidebar a:hover {
            color: #d9aca4;
        }

        
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .auto-style4 {
            text-align: center;
            padding: 20px 0;
            font-size: xx-large;
            font-weight: bold;
            color: #c38876;
            font-family: "Courier New", Courier, monospace;
            letter-spacing: 4px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .product {
            text-align: center;
            background-color: #ece4dc;
            border-radius: 8px;
            padding: 10px;
            transition: transform 0.2s ease;
            border: 1px solid #e9dcd3;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product img {
            width: 70%;
            height: auto;
            border-radius: 8px;
            margin: 0 auto;
        }

        .auto-style2 {
            font-family: "Courier New", Courier, monospace;
            font-size: medium;
            color: #C38876;
            margin-top: 10px;
            text-align: center;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-price {
            font-size: large;
            color: #d9aca4;
            margin: 0;
        }

        .btn {
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

        .btn:hover {
            background-color: #c38876;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #eadcdc;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="sidebar">
            <a href="?category=Necklaces">Necklaces</a><br>
            <a href="?category=Bracelets">Bracelets</a><br>
            <a href="?category=Earrings">Earrings</a><br>
            <a href="?category=Rings">Rings</a><br>
        </div>

        <div class="main-content">
            <div class="auto-style4"><?php echo htmlspecialchars($category); ?></div>

            <div class="product-grid">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product">';
                        echo '<img src="data:image;base64,' . base64_encode($row['image_url'])  . '" alt="' . htmlspecialchars($row['description']) . '">';
                        echo '<div class="auto-style2">' . htmlspecialchars($row['description']) . '</div>';
                        echo '<div class="product-footer">';
                        echo '<div class="product-price">' . htmlspecialchars($row['price']) . ' SR</div>';
                        echo '<div class="card-footer">';
                        echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
                        echo '<input type="hidden" name="id" value="' . $row['id'] . '"/>';
                        echo '<input type="hidden" name="User_id" value="' . $User_id . '"/>';
                        echo '<input type="hidden" name="price" value="' . $row['price'] . '"/>';
                        echo '<input type="submit" name="addcartsubmitbtn" value="Add to Cart" class="btn btn-info"/>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No products available in this category.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>