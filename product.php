<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="main.css">
<title>Product Management</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

button {
    display: block;
    margin: 0 auto;
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
}

button:hover {
    background-color: #45a049;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
}

table th {
    background-color: #f2f2f2;
    text-align: left;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"], input[type="file"] {
    width: calc(100% - 22px);
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
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

.popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 400px;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    color: #999;
}

.close:hover {
    color: #666;
}

/* Styling for images */
.product-image {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
    border-radius: 4px;
}
</style>
</head>
<body>


<div class="sidenav">
    
    <h2>MDR Dealers</h2>
    <div class="up">
    <a href="index.php">Dashboard</a>
    <a href="task.php">Hero</a>
    <a href="#">Add On</a>
    <a href="#">Update</a>
    <a href="#">Home Product</a>
    </div>
</div>
<div class="container">
    <h2>Product Management</h2>

    <!-- Button to open popup form -->
    <button onclick="openPopup()">Add Product</button>

    <!-- Table for displaying products -->
    <table>
        <tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
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

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Handle form submission for adding new product
            if (isset($_POST["add_product"])) {
                $product_name = $_POST['product_name'];
                $product_brand = $_POST['product_brand'];
                $price = $_POST['price'];

                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["product_image"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["product_image"]["size"] > 500000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif") {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                        $sql = "INSERT INTO updateproduct (product_name, product_brand, price, product_image) VALUES ('$product_name', '$product_brand', '$price', '$target_file')";
                        if ($conn->query($sql) === TRUE) {
                            echo "<p>New product added successfully</p>";
                        } else {
                            echo "<p>Error adding product: " . $conn->error . "</p>";
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }

            // Handle form submission for updating product
            if (isset($_POST["update_product"])) {
                // Code for updating product (similar to adding new product)
                $product_id = $_POST['product_id'];
                $product_name = $_POST['product_name'];
                $product_brand = $_POST['product_brand'];
                $price = $_POST['price'];

                // Update the product details in the database
                $sql = "UPDATE updateproduct SET product_name='$product_name', product_brand='$product_brand', price='$price' WHERE product_id=$product_id";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Product updated successfully</p>";
                } else {
                    echo "<p>Error updating product: " . $conn->error . "</p>";
                }
            }
        }

        // Read Operation - Display Products
        $sql = "SELECT * FROM updateproduct";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><img src='" . $row["product_image"] . "' class='product-image' alt='Product Image'></td>";
                echo "<td>" . $row["product_name"] . "</td>";
                echo "<td>" . $row["product_brand"] . "</td>";
                echo "<td>" . $row["price"] . "</td>";
                echo "<td>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='product_id' value='" . $row['product_id'] . "'>";
                echo "<input type='submit' value='Edit' name='edit_product'>";
                echo "</form>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='product_id' value='" . $row['product_id'] . "'>";
                echo "<input type='submit' value='Delete' name='delete_product'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No products found</td></tr>";
        }

        // Delete Operation - Delete Product
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_product"])) {
            $product_id = $_POST['product_id'];
            $sql = "DELETE FROM updateproduct WHERE product_id=$product_id";

            if ($conn->query($sql) === TRUE) {
                echo "<p>Product deleted successfully</p>";
            } else {
                echo "<p>Error deleting product: " . $conn->error . "</p>";
            }
        }

        // Edit Operation - Retrieve Product Details for Editing
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_product"])) {
            $product_id = $_POST['product_id'];
            $sql = "SELECT * FROM updateproduct WHERE product_id=$product_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<div class='popup' style='display:block;'>";
                echo "<span class='close' onclick='closePopup()'>&times;</span>";
                echo "<h2>Edit Product</h2>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                echo "<input type='hidden' name='product_id' value='" . $row['product_id'] . "'>";
                echo "<label for='product_name'>Product Name:</label>";
                echo "<input type='text' name='product_name' value='" . $row['product_name'] . "' required><br><br>";
                echo "<label for='product_brand'>Product Brand:</label>";
                echo "<input type='text' name='product_brand' value='" . $row['product_brand'] . "' required><br><br>";
                echo "<label for='price'>Price:</label>";
                echo "<input type='text' name='price' value='" . $row['price'] . "' required><br><br>";
                echo "<input type='submit' value='Update Product' name='update_product'>";
                echo "</form>";
                echo "</div>";
            } else {
                echo "<p>No product found</p>";
            }
        }

        $conn->close();
        ?>
    </table>
</div>

<!-- Popup form for adding new product -->
<div id="popup" class="popup" style="display: none;">
    <span class="close" onclick="closePopup()">&times;</span>
    <h2>Add Product</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required><br><br>
        <label for="product_brand">Product Brand:</label>
        <input type="text" name="product_brand" required><br><br>
        <label for="price">Price:</label>
        <input type="text" name="price" required><br><br>
        <label for="product_image">Product Image:</label>
        <input type="file" name="product_image" accept="image/*" required><br><br>
        <input type="submit" value="Add Product" name="add_product">
    </form>
</div>

<script>
function openPopup() {
    document.getElementById("popup").style.display = "block";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}
</script>
</body>
</html>
