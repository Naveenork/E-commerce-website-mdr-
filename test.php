<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Management</title>
  <style>
    .container {
      max-width: 600px;
      margin: auto;
    }
    .form-group {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Add Product</h2>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Product Image:</label>
      <input type="file" name="product_image" required>
    </div>
    <div class="form-group">
      <label>Product Name:</label>
      <input type="text" name="product_name" required>
    </div>
    <div class="form-group">
      <label>Product Brand:</label>
      <input type="text" name="product_brand" required>
    </div>
    <div class="form-group">
      <label>Product Color:</label>
      <input type="text" name="product_color" required>
    </div>
    <div class="form-group">
      <label>Description:</label>
      <textarea name="description" required></textarea>
    </div>
    <div class="form-group">
      <label>Price:</label>
      <input type="text" name="price" required>
    </div>
    <input type="submit" name="submit" value="Add Product">
  </form>

  <?php
  // Database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "your_database_name";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Handle form submission
  if (isset($_POST['submit'])) {
      $product_image = $_FILES['product_image']['name'];
      $product_name = $_POST['product_name'];
      $product_brand = $_POST['product_brand'];
      $product_color = $_POST['product_color'];
      $description = $_POST['description'];
      $price = $_POST['price'];

      // Upload product image
      $targetDir = "uploads/";
      $targetFilePath = $targetDir . $product_image;
      move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath);

      // Insert data into database
      $sql = "INSERT INTO products (product_image, product_name, product_brand, product_color, description, price)
              VALUES ('$product_image', '$product_name', '$product_brand', '$product_color', '$description', '$price')";

      if ($conn->query($sql) === TRUE) {
          echo "<p>Product added successfully.</p>";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
  }

  // Display products from database
  $sql = "SELECT * FROM products";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      echo "<h2>Products</h2>";
      while($row = $result->fetch_assoc()) {
          echo "<div>";
          echo "<img src='uploads/" . $row['product_image'] . "' alt='" . $row['product_name'] . "'><br>";
          echo "<strong>Name:</strong> " . $row['product_name'] . "<br>";
          echo "<strong>Brand:</strong> " . $row['product_brand'] . "<br>";
          echo "<strong>Color:</strong> " . $row['product_color'] . "<br>";
          echo "<strong>Description:</strong> " . $row['description'] . "<br>";
          echo "<strong>Price:</strong> $" . $row['price'] . "<br>";
          echo "</div>";
      }
  } else {
      echo "<p>No products found.</p>";
  }

  $conn->close();
  ?>
</div>

</body>
</html>
