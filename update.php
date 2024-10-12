<?php
// Check if form is submitted and product ID is provided
if(isset($_POST['update_product']) && isset($_POST['product_id'])) {
    // Retrieve form data
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_brand = $_POST['product_brand'];
    $price = $_POST['price'];

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

    // Update product details in the database
    $sql = "UPDATE updateproduct SET product_name='$product_name', product_brand='$product_brand', price='$price' WHERE product_id=$product_id";

    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error updating product: " . $conn->error;
    }

    $conn->close();
} else {
    // Redirect to edit.php if form is not submitted or product ID is not provided
    header("Location: edit.php");
    exit;
}
?>
