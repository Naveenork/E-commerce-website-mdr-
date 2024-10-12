<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Management</title>
<style>
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

label {
    font-weight: bold;
}

input[type="text"], textarea, input[type="file"] {
    width: 100%;
    padding: 8px;
    margin: 5px 0;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}
</style>
</head>
<body>
<div class="container">
    <h2>Product Management</h2>
    <!-- Form for Product Upload -->
    <h3>Upload Product</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" required><br><br>
        
        <label for="product_brand">Product Brand:</label>
        <input type="text" name="product_brand" id="product_brand" required><br><br>
        
        <label for="product_color">Product Color:</label>
        <input type="text" name="product_color" id="product_color"><br><br>
        
        <label for="description">Description:</label><br>
        <textarea name="description" id="description" rows="4" cols="50" required></textarea><br><br>
        
        <label for="price">Price:</label>
        <input type="text" name="price" id="price" required><br><br>
        
        <label for="product_image">Product Image:</label>
        <input type="file" name="product_image[]" id="product_image" multiple required><br><br>
        
        <label for="additional_images">Additional Images:</label>
        <input type="file" name="additional_images[]" id="additional_images" multiple><br><br>
        
        <input type="submit" value="Upload" name="upload_product">
    </form>

    <?php
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

    // CRUD Operations
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_product'])) {
        // Create Operation - Product Upload
        $product_name = $_POST['product_name'];
        $product_brand = $_POST['product_brand'];
        $product_color = $_POST['product_color'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        $product_images = array();
        $additional_images = array();

        // Process product images
        if (!empty($_FILES['product_image']['name'][0])) {
            foreach ($_FILES['product_image']['tmp_name'] as $key => $tmp_name) {
                $product_image = $_FILES['product_image']['name'][$key];
                $product_image_temp = $_FILES['product_image']['tmp_name'][$key];

                $product_images[] = $product_image;

                move_uploaded_file($product_image_temp, "uploads/$product_image");
            }
        }

        // Process additional images
        if (!empty($_FILES['additional_images']['name'][0])) {
            foreach ($_FILES['additional_images']['tmp_name'] as $key => $tmp_name) {
                $additional_image = $_FILES['additional_images']['name'][$key];
                $additional_image_temp = $_FILES['additional_images']['tmp_name'][$key];

                $additional_images[] = $additional_image;

                move_uploaded_file($additional_image_temp, "uploads/$additional_image");
            }
        }

        $product_images_str = implode(',', $product_images);
        $additional_images_str = implode(',', $additional_images);

        $sql = "INSERT INTO products (product_image, additional_images, product_name, product_brand, product_color, description, price) VALUES ('$product_images_str', '$additional_images_str', '$product_name', '$product_brand', '$product_color', '$description', '$price')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>New product uploaded successfully</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }

    // Read Operation - Display Products
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h3>Products</h3>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "<strong>Product Name:</strong> " . $row["product_name"] . " | ";
            echo "<strong>Product Brand:</strong> " . $row["product_brand"] . " | ";
            echo "<strong>Product Color:</strong> " . $row["product_color"] . " | ";
            if (isset($row["description"])) {
                echo "<strong>Description:</strong> " . $row["description"] . " | ";
            } else {
                echo "<strong>Description:</strong> Not available | ";
            }
            echo "<strong>Price:</strong> $" . $row["price"];
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No products found</p>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
