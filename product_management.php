<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['AdminUsername']))
{
    header("Location: login.php");
    exit();
}
function sanitizeInput($input) {
    // Remove leading and trailing whitespace
    $input = trim($input);
    // Escape special characters to prevent XSS
    $input = htmlspecialchars($input, ENT_QUOTES);
    return $input;
}
function containsXSS($input) {
    // List of common words used in XSS attacks
    $blacklist = array("script", "onload", "onerror", "alert", "prompt", "confirm", "javascript", "iframe", "img", "svg", "onmouseover", "onmouseout", "onfocus", "onblur");
    
    // Check if the input contains any of the words in the blacklist
    foreach ($blacklist as $word) {
        if (stripos($input, $word) !== false) {
            return true;
        }
    }
    return false;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
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
    tr:hover {background-color: #eadcdc;}


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
            padding: 6px 15px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 3px;
            transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
            background-color: #c38876;
    }

    .my-button {
            background-color: #A5A09Bff;
            color: #ffffff;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 3px;
            transition: background-color 0.3s;
    }

    .my-button:hover {
            background-color: #A5A09Bff;
    }
    
    .custom-button {
            background-color: #d9aca4;
            color: #ffffff;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 3px;
            transition: background-color 0.3s;
    }
    .custom-button:hover {
            background-color: #c38876;
    }


    .product-table-body {
            overflow-y: auto;
            max-height: 550px;
    }
    .product-table-header {
            overflow: hidden;
    }
    
    .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

    .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

    .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

    .close:hover,
    .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
    input[type="number"],
    select {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #CCCCCC;
            margin-bottom: 20px;
            width: 250px;
            color: #333333;
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
            <li><a href="admin.php">Main</a></li>
        </ul>
    </nav>
    <script>
    function validateForm() {
        var imageInput = document.getElementById("image");
        var imageFile = imageInput.files[0];   
        if (!imageFile) {
            alert("Please select an image.");
            return false;
        }

        var validExtensions = ["image/jpeg", "image/png"];

        if (!validExtensions.includes(imageFile.type)) {
            alert("Please select a JPEG or PNG image.");
            return false;
        }  
        var productName = document.getElementById("product_name").value;
        var productPrice = document.getElementById("product_price").value;
        var productStock = document.getElementById("product_stock").value;
        var productDescription = document.getElementById("message").value;

        if (!/^[a-zA-Z\s]+$/.test(productDescription)) {
            alert("Product description should contain only alphabetic characters.");
        return false;
        }

        if (productName === "") {
            alert("Please select a product category.");
            return false;
        }

        if (productPrice === "" || isNaN(productPrice)) {
            alert("Please enter a valid product price.");
            return false;
        }

        if (productStock === "" || isNaN(productStock)) {
            alert("Please enter a valid product stock.");
            return false;
        }

        return true;
    }
</script>

<h1>Product Management</h1>
<div class="container">
    <div class="add-product">
        <h2>Add Product</h2>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label for="image">Product Image (JPEG or PNG only):</label>
            <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png" required><br>

            <label for="product_name">Product category:</label>
            <select name="product_name" id="product_name" required>
                <option value="">Select a category</option>
                <option value="Necklaces">Necklaces</option>
                <option value="Earrings">Earrings</option>
                <option value="Bracelets">Bracelets</option>
                <option value="Rings">Rings</option>
            </select><br>

            <label for="product_price">Product Price:</label>
            <input type="number" name="product_price" id="product_price" required><br>

            <label for="product_stock">Product Stock:</label>
            <input type="number" name="product_stock" id="product_stock" required><br>

            <label for="message">Product Description:</label><br>
            <textarea id="message" name="Description" rows="7" cols="50" required></textarea><br>


            <input type="submit" name="add_product" value="Add Product">
        </form>
    </div>
        <div class="product-table-container">
            <h2>Product List</h2>
            <div class="product-table-body">
                <table>
                    <thead class="product-table-header">
                        <tr>
                            <th>Product Image</th>
                            <th>Product category</th>
                            <th>Product ID</th>
                            <th>Product Price</th>
                            <th>Product Stock</th>
                            <th>Product description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                        // Fetch products from the database
                        $sql = "SELECT * FROM products";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Output sanitized and encoded product details
                                echo '<tr>';
                                echo '<td><img src="data:image;base64,' . base64_encode($row['image_url']) . '" alt="Image" style="width: 100px; height: 100px;"></td>';
                                echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['product_stock']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                echo '<td>';

                                echo '<button onclick="openModal(' . htmlspecialchars($row['id']) . ')" class="custom-button">Edit</button>';
                                echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                                echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row['id']) . '">';
                                echo '<input type="submit" name="delete_product" value="Delete">';
                                echo '</form>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                 
                        
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST["update_product"])) {
                            // Update product
                            $product_id = $_POST["product_id"];
                            $price = $_POST["product_price"];
                            $stock = $_POST["product_stock"];
                            $stmt = $conn->prepare("UPDATE products SET price = ?, product_stock = ? WHERE id = ?");
                            $stmt->bind_param("dii", $price, $stock, $product_id);

                             if ($stmt->execute()) {
                                  echo '<div class="message-box success">Product updated successfully!</div>';
                            } else {
                                  echo '<div class="message-box error">Error: ' . $stmt->error . '</div>';
                                }

                            // Retrieve the updated product details
                             $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                             $stmt->bind_param("i", $product_id);
                             $stmt->execute();
                             $result = $stmt->get_result();
                             $row = $result->fetch_assoc();
                        }
                        if (isset($_POST["delete_product"])) {
                            $product_id = $_POST["product_id"];
                            $sql = "DELETE FROM products WHERE id = ?";
                            $stmt = mysqli_prepare($conn, $sql);
                            mysqli_stmt_bind_param($stmt, "i", $product_id);
                            
                            if (mysqli_stmt_execute($stmt)) {
                                echo '<div class="message-box success">Product deleted successfully!</div>';
                            } else {
                                echo '<div class="message-box error">Error: ' . mysqli_stmt_error($stmt) . '</div>';
                            }
                            
                            mysqli_stmt_close($stmt);
                        } 
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST["add_product"])) {
                                // Sanitize form inputs before storing in the database
                                $name = sanitizeInput($_POST["product_name"]);
                                $price = sanitizeInput($_POST["product_price"]);
                                $stock = sanitizeInput($_POST["product_stock"]);
                                // Use strip_tags to remove any HTML tags from the description
                                $description = strip_tags($_POST["Description"]);
                        
                                // Check for XSS in the description
                                if (containsXSS($description)) {
                                    echo '<div class="message-box error">Error: Detected potential XSS attack.</div>';
                                    exit();
                                }
                        
                                // Read the image file as binary data
                                $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
                        
                                // Prepare the SQL statement using prepared statement to prevent SQL injection
                                $stmt = $conn->prepare("INSERT INTO products (category, description, price, image_url, product_stock) VALUES (?, ?, ?, ?, ?)");
                                $stmt->bind_param("ssdss", $name, $description, $price, $imageData, $stock);
                        
                                if ($stmt->execute()) {
                                    echo '<div class="message-box success">Product added successfully.</div>';
                                } else {
                                    echo '<div class="message-box error">Error: ' . $stmt->error . '</div>';
                                }
                           
                            }
                         
                        }
                    } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Product</h2>
            <div class="edit-form">
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <input type="hidden" id="edit_product_id" name="product_id">
                    <label for="product_price">Product Price:</label>
                    <input type="number" name="product_price" id="product_price" ><br>
                    <label for="product_stock">Product Stock:</label>
                    <input type="number" name="product_stock" id="product_stock" ><br>
                    <input type="submit" name="update_product" value="Update">
                    <input type="submit" name="cancel_edit" value="Cancel">
                </form>
            </div>
        </div>
        
    </div>

    <script>
    function openModal(productId) {
            document.getElementById('edit_product_id').value = productId;
            document.getElementById('editModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }
   </script>


</body>
</html>