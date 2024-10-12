<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Side Navigation Bar</title>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
    }

    .sidenav {
        margin-top:18px;
        height: 94%;
        width: 250px;
        margin-left:20px;
        border-radius:30px;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #333;
        overflow-x: hidden;
        padding-top: 20px;
    }

    .sidenav a {
        text-align:center;
        padding: 15px 15px;
        text-decoration: none;
        font-size: 18px;
        color: #f1f1f1;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover {
        background-color: #555;
    }

    .sidenav h2 {
        text-align:center;
        color: #fff;
        padding-left: 15px;
    }

    .content {
        margin-left: 250px;
        padding: 20px;
    }
    .up{
        margin-top:60px;
    }


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
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
th {
    background-color: #f2f2f2;
}

/* Popup form styles */
.popup {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.popup-content {
    background-color: #fefefe;
    margin: 5% auto;
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
    <!-- Add button to open the popup form -->
    <button onclick="openFormPopup()">Add Service</button>
    </div>
</div>

<div class="content">
<div class="container">
    <h2>Service Management</h2>
    <!-- Popup form for Service Upload -->
    <div id="uploadFormPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeFormPopup()">&times;</span>
            <h3>Add Service</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <label for="service_name">Service Name:</label>
                <input type="text" name="service_name" id="service_name" required><br><br>
                
                <label for="service_image">Service Image:</label>
                <input type="file" name="service_image" id="service_image" required><br><br>
                
                <input type="submit" value="Add" name="add_service">
            </form>
        </div>
    </div>
</div>
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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
        // Create Operation - Service Add
        $service_name = $_POST['service_name'];

        // Process service image
        $service_image = $_FILES['service_image']['name'];
        $service_image_temp = $_FILES['service_image']['tmp_name'];
        move_uploaded_file($service_image_temp, "service_images/$service_image");

        $sql = "INSERT INTO service (image, name) VALUES ('$service_image', '$service_name')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>New service added successfully</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }

    // Read Operation - Display Services
    $sql = "SELECT * FROM service";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h3>Services</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Service Name</th><th>Service Image</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td><img src='service_images/" . $row["image"] . "' alt='Service Image' style='max-width: 100px; max-height: 100px;'></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No services found</p>";
    }

    $conn->close();
    ?>
</div>

<script>
// JavaScript function to open the popup form
function openFormPopup() {
    document.getElementById("uploadFormPopup").style.display = "block";
}

// JavaScript function to close the popup form
function closeFormPopup() {
    document.getElementById("uploadFormPopup").style.display = "none";
}
</script>

</body>
</html>
