<?php
// Check if product ID is provided in the URL
if(isset($_GET['product_id'])) {
    // Get the product ID from the URL parameter
    $product_id = $_GET['product_id'];

    // Database Configuration
    $servername = "localhost";
    $username = "root"; // Your MySQL username
    $password = ""; // Your MySQL password
    $dbname = "testing"; // Your MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch product details from the database
    $sql = "SELECT * FROM updateproduct WHERE product_id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Product found, display the edit form
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Product</title>
        </head>
        <body>
            <h2>Edit Product</h2>
            <form action="update.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" value="<?php echo $row['product_name']; ?>" required><br><br>
                
                <label for="product_brand">Product Brand:</label>
                <input type="text" name="product_brand" value="<?php echo $row['product_brand']; ?>" required><br><br>
                
                <label for="price">Price:</label>
                <input type="text" name="price" value="<?php echo $row['price']; ?>" required><br><br>
                
                <input type="submit" value="Update Product" name="update_product">
            </form>
        </body>
        </html>
        <?php
    } else {
        // Product not found
        echo "Product not found.";
    }

    $conn->close();
} else {
    // Product ID not provided in the URL
    echo "Product ID not provided.";
}
?>
