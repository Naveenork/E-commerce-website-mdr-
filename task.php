<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Image Upload Dashboard</title>
<style>
/* Reset default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Global styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
}

.sidenav {
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #333;
    padding-top: 20px;
    overflow-y: auto; /* Add scrollbar for overflow */
}

.sidenav h2 {
    text-align: center;
    color: #fff;
}

.sidenav a {
    display: block;
    padding: 15px;
    text-decoration: none;
    color: #f1f1f1;
    transition: background-color 0.3s;
}

.sidenav a:hover {
    background-color: #555;
}

.content {
    margin-left: 250px;
    padding: 20px;
}

.container {
    width: 80%;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
}

form {
    text-align: center;
}

label {
    font-weight: bold;
}

input[type="file"] {
    margin-bottom: 10px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

.message {
    text-align: center;
    margin-top: 20px;
}

.content img {
    max-width: 30%;
    height: 100px;
    margin-left: 30px;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px; /* Add margin for separation */
}

table th, table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

table th {
    background-color: #f2f2f2;
}

/* Responsive styles for smaller screens */
@media screen and (max-width: 768px) {
    .sidenav {
        width: 100%; /* Take full width on smaller screens */
        height: auto;
        overflow-y: auto;
    }

    .content {
        margin-left: 0;
        padding: 20px;
    }
}

</style>
</head>
<body>

<div class="sidenav">
    <h2>MDR Dealers</h2>
    <div class="up">
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Portfolio</a>
        <a href="#">Contact</a>
    </div>
</div>

<div class="content">
    <div class="container">
        <h2>Image Upload Dashboard</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="image">Image 1:</label><br>
            <input type="file" name="image" id="image"><br>
            
            <label for="image1">Image 2:</label><br>
            <input class="red" type="file" name="image1" id="image1"><br>
            
            <label for="image2">Image 3:</label><br>
            <input type="file" name="image2" id="image2"><br>
            
            <input type="submit" value="Upload" name="submit">
        </form>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testing";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert record
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $target_dir = "uploads/";
            $image = basename($_FILES["image"]["name"]);
            $image1 = basename($_FILES["image1"]["name"]);
            $image2 = basename($_FILES["image2"]["name"]);

            move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
            move_uploaded_file($_FILES["image1"]["tmp_name"], $target_dir . $image1);
            move_uploaded_file($_FILES["image2"]["tmp_name"], $target_dir . $image2);

            $sql = "INSERT INTO card (image, image1, image2) VALUES ('$image', '$image1', '$image2')";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='message'>Images uploaded successfully.</div>";
            } else {
                echo "<div class='message'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }
        }

        // Display records
        $sql = "SELECT * FROM card";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Image 1</th><th>Image 2</th><th>Image 3</th><th>Action</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><img src='uploads/" . $row["image"] . "' alt='Image'></td>";
                echo "<td><img src='uploads/" . $row["image1"] . "' alt='Image'></td>";
                echo "<td><img src='uploads/" . $row["image2"] . "' alt='Image'></td>";
                echo "<td><a href='delete.php?id=" . $row["id"] . "'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='message'>No images uploaded yet.</div>";
        }

        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
