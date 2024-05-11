
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
.background-image {
            position: relative;
            background-image: url('images/Bling.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
}

.background-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
}           

.content {
            position: relative;
            z-index: 1;
}

header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #f4f3f1;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-logo img {
            max-height: 100%; 
            max-width: 100%; 
            margin: 0 auto; 
}

.nav-menu {
            display: flex;
            justify-content: space-between;
}

.nav-menu a {
            text-decoration: none;
            color:  #c2a976;
            margin-right: 20px;
}

.nav-menu a:hover {
            color: #c38876;
}
        

body {
            font-family: Georgia, "Times New Roman", Times, serif;
            margin: 0;
            padding: 20px;
            background-color: #dfbeb1; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
.content {
            max-width: 800px;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}
        
h1 {
            color: #c38876;
            text-align: center;
            font-size: 36px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
.menu-container {
            display: flex;
            flex-wrap: nowrap;
            justify-content: center;
            margin-top: 100px;
            overflow-x: auto;
}
        
.menu-button {
            background-color: #d9aca4; 
            color: white;
            border: none;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            margin: 10px;
            width: 200px;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
.menu-button:hover {
            background-color:#c38876;
        }
        
.menu-details {
            margin-top: 20px;
            text-align: center;
        }
        
.menu-title {
            font-size: 24px;
            color: #333;
        }
        
.menu-description {
            font-size: 16px;
            color: #777;
        }
.logout-link {
            color: #5d5c5c;
            font-weight: bold;
            text-decoration: none;
     }

.logout-link:hover {
            text-decoration: underline;
    }
        
   
    </style>
</head>
<body>
<header>
<div class="nav-menu">
            <a href="login.php" class="logout-link">Logout</a>
        </div>
    <nav>
        <div class="nav-logo">
            <img src="images/Bling.png" alt="Logo">
        </div>
        
    </nav>
</header>
    <?php
    $menuOptions = [
        [
            'title' => 'Manage Products',
            'description' => 'Add, edit, and remove products. ',
            'icon' => 'fa fa-cubes',
            'link' => 'product_management.php'
        ],
        [
            'title' => 'Manage Orders',
            'description' => 'View and manage all incoming orders.',
            'icon' => 'fa fa-shopping-cart',
            'link' => 'Orders.php'
        ],
    
    ];
    ?>

    <div class="content">
        <h1>Welcome to the Admin Page</h1>
    
        <div class="menu-container">
            <?php foreach ($menuOptions as $option) : ?>
                <a href="<?php echo $option['link']; ?>" class="menu-button">
                    <i class="<?php echo $option['icon']; ?>"></i>
                    <div class="menu-details">
                        <h2><?php echo $option['title']; ?></h2>
                        <p><?php echo $option['description']; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <footer>
        </footer>
    </div>
</body>
</html>