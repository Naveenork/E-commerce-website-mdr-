<?php
// Database configuration
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

// Check if ID parameter is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve image file names from the database
    $sql = "SELECT image, image1, image2 FROM card WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Delete individual image files
        while($row = $result->fetch_assoc()) {
            unlink("uploads/" . $row["image"]);
            unlink("uploads/" . $row["image1"]);
            unlink("uploads/" . $row["image2"]);
        }

        // Delete record from the database
        $sql_delete = "DELETE FROM card WHERE id = $id";
        if ($conn->query($sql_delete) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "No record found with the provided ID";
    }
} else {
    echo "No ID parameter provided";
}

$conn->close();
?>
