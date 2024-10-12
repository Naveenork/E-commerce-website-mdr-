<?php
session_start();

// Include database connection code here
$db = new mysqli("localhost", "root", "", "testing");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Sample login/logout functionality
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['user_id']);
    header('Location:login.php');
    exit();
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Fetch distinct colors from the database
$color_query = "SELECT DISTINCT product_color FROM products";
$color_result = $db->query($color_query);
$colors = [];
if ($color_result && $color_result->num_rows > 0) {
    while ($row = $color_result->fetch_assoc()) {
        $colors[] = $row['product_color'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="card1.css">
    <link rel="stylesheet" href="navbar_1.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Product List</title>
</head>

<body>
    <div class="topnav" id="myTopnav">
        <!-- Your navigation menu goes here -->
    </div>

    <div class="filter-form">
        <form action="two.php" method="get">
            <label for="search">Search:</label>
            <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

            <label for="min_price">Min Price:</label>
            <input type="text" name="min_price" id="min_price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">

            <label for="max_price">Max Price:</label>
            <input type="text" name="max_price" id="max_price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">

            <label for="brand">Brand:</label>
            <select name="brand" id="brand">
                <option value="">Select Brand</option>
                <option value="Brand 1" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Brand 1') ? 'selected' : ''; ?>>Brand 1</option>
                <option value="Brand 2" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 'Brand 2') ? 'selected' : ''; ?>>Brand 2</option>
                <!-- Add more options if needed -->
            </select>

            <label for="color">Color:</label>
            <select name="color" id="color">
                <option value="">Select Color</option>
                <?php foreach ($colors as $color) : ?>
                    <option value="<?php echo $color; ?>" <?php echo (isset($_GET['color']) && $_GET['color'] == $color) ? 'selected' : ''; ?>><?php echo $color; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Apply Filters</button>
        </form>
    </div>

    <div class="products-grid">
        <?php
        // Apply filters if submitted
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $product_query = "SELECT * FROM products WHERE 1";

            if (isset($_GET['search'])) {
                $search = sanitizeInput($_GET['search']);
                $product_query .= " AND (product_name LIKE '%$search%' OR product_brand LIKE '%$search%')";
            }

            if (isset($_GET['min_price'])) {
                $min_price = (float)$_GET['min_price'];
                $product_query .= " AND price >= $min_price";
            }

            if (isset($_GET['max_price'])) {
                $max_price = (float)$_GET['max_price'];
                $product_query .= " AND price <= $max_price";
            }

            if (isset($_GET['brand'])) {
                $brand = sanitizeInput($_GET['brand']);
                $product_query .= " AND product_brand = '$brand'";
            }

            if (isset($_GET['color'])) {
                $color = sanitizeInput($_GET['color']);
                $product_query .= " AND product_color = '$color'";
            }

            $product_query .= " ORDER BY product_id DESC";

            $product_result = $db->query($product_query);

            if ($product_result && $product_result->num_rows > 0) {
                while ($product = $product_result->fetch_assoc()) {
                    echo '<div class="product-card">
                        <a href="two.php?product_id=' . $product['product_id'] . '">
                        <img class="image" src="next_img/' . $product['product_image'] . '">
                            <h3>' . $product['product_name'] . '</h3>
                            <p class="price">' . $product['product_brand'] . '</p>
                            <p class="price" >★★★★★</p>
                            <p class="price">$' . $product['price'] . '</p>
                        </a>
                    </div>';
                }
            } else {
                echo "No products available.";
            }
        }
        ?>
    </div>

</body>

</html>
