<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include the CSS file -->
    <style>
        /* Additional inline styles can be added here if necessary */
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
        <button class="button" onclick="openFormPopup()">Add Service</button>
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
                    
                    <input type="submit" value="Add" name="add_service" class="button">
                </form>
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

        // Read Operation - Display Services
        $sql = "SELECT * FROM service";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h3>Services</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Service Name</th><th>Service Image</th><th>Actions</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td><img src='service_images/" . $row["image"] . "' alt='Service Image' style='max-width: 100px; max-height: 100px;'></td>";
                echo "<td><form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                
                // Check if 'id' key is set before using it
                if(isset($row["id"])) {
                    echo "<input type='hidden' name='service_id' value='" . $row["id"] . "'>";
                } else {
                    echo "<input type='hidden' name='service_id' value=''>";
                }
                
                echo "<input type='text' name='new_service_name' placeholder='New Name' required>";
                echo "<input type='submit' value='Update' name='update_service' class='button'>";
                echo "</form>";
                
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
                
                // Check if 'id' key is set before using it
                if(isset($row["id"])) {
                    echo "<input type='hidden' name='service_id' value='" . $row["id"] . "'>";
                } else {
                    echo "<input type='hidden' name='service_id' value=''>";
                }
                
                echo "<input type='submit' value='Delete' name='delete_service' class='button' onclick='return confirm(\"Are you sure you want to delete this service?\")'>";
                echo "</form></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No services found</p>";
        }

        // Create Operation - Add Service
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
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

        // Update Operation - Update Service
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_service'])) {
            if(isset($_POST['service_id'])) {
                $service_id = $_POST['service_id'];
                $new_service_name = $_POST['new_service_name'];

                $sql = "UPDATE service SET name='$new_service_name' WHERE id=$service_id";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Service updated successfully</p>";
                } else {
                    echo "<p>Error updating service: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Error: Service ID not provided</p>";
            }
        }

        // Delete Operation - Delete Service
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_service'])) {
            if(isset($_POST['service_id'])) {
                $service_id = $_POST['service_id'];

                $sql = "DELETE FROM service WHERE id=$service_id";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Service deleted successfully</p>";
                } else {
                    echo "<p>Error deleting service: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Error: Service ID not provided</p>";
            }
        }

        $conn->close();
        ?>
    </div>
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
