<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/dealer-portal/assets/css/index.css">
    <script src="https://kit.fontawesome.com/4feafd16e4.js" crossorigin="anonymous"></script>
    <title>Tom's Manufacturing</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        .product-item {
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .product-item img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }
    </style>
</head>

<body>
    <div class="nav-block" id="header">
        <div class="title">
            <a class="heading" href="/dealer-portal/index.php">
                <h1 class="lobster-regular">Tom's Manufacturing</h1>
            </a>
        </div>
        <div class="nav-buttons">
            <ul class='nav-list'>
                <li class="nav-icon"><i id="home" class="fa-solid fa-house"></i>
                    <h3 class="roboto-medium">Home</h3>
                </li>
                <li class="nav-icon"><i id="orders" class="fa-solid fa-truck"></i>
                    <h3 class="roboto-medium">Orders</h3>
                </li>
                <li class="nav-icon"><i id="history" class="fa-solid fa-file-invoice"></i>
                    <h3 class="roboto-medium">History</h3>
                </li>
                <li class="nav-icon"><i id="profile" class="fa-solid fa-user"></i>
                    <h3 class="roboto-medium">Profile</h3>
                </li>
                <li class="nav-icon"><i id="cart" class="fa-solid fa-cart-shopping"></i>
                    <h3 class="roboto-medium">Cart</h3>
                </li>
            </ul>
        </div>
    </div>

    <div>
        <div class="search-block">
            <i class="fa-solid fa-magnifying-glass search-icon"></i><input class="search-input" type="text" placeholder="Search..." name="" id="">
        </div>
    </div>

    <div class="intro-text">
        <p class="roboto-regular">
            Lorem ipsum dolor, 
            sit amet consectetur adipisicing elit. 
            Cupiditate numquam nesciunt consequatur 
            reiciendis sunt eius nisi natus repellendus 
            vero reprehenderit! Dolorem atque sapiente 
            similique earum illo molestias unde, impedit mollitia.
            Lorem ipsum dolor, 
            sit amet consectetur adipisicing elit. 
            Cupiditate numquam nesciunt consequatur 
            reiciendis sunt eius nisi natus repellendus 
            vero reprehenderit! Dolorem atque sapiente 
            similique earum illo molestias unde, impedit mollitia.
        </p>
    </div>

    <?php
    include 'config.php';

    $conn = getDBConnection();

    // Fetch product details
    $sql = "SELECT name, image_path, description FROM products LIMIT 6";
    $result = $conn->query($sql);

    $products = [];

    if ($result->num_rows > 0) {
        // Store product details in an array
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>">
                <h5><?php echo $product['name']; ?></h5>
                <p><?php echo $product['description']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
